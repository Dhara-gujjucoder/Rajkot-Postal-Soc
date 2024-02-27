<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\LoanEMI;
use App\Models\LoanMaster;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MemberFixedSaving;
use App\Traits\UpdateMemberShare;
use Illuminate\Support\Facades\Auth;
use App\Models\LoanCalculationMatrix;
use Illuminate\Http\RedirectResponse;
use App\Traits\UpdateMemberFixedSaving;

class LoanMasterController extends Controller
{
    use UpdateMemberShare,UpdateMemberFixedSaving;
    /*check permission*/
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-loan_matrix|edit-loan_matrix|delete-loan_matrix|view-loan_matrix', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-loan_matrix', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-loan_matrix', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-loan_matrix', ['only' => ['destroy']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['loans'] = LoanMaster::get();
        $data['page_title'] = __('Loan');
        if ($request->ajax()) {
            $data = LoanMaster::where('year_id', $this->current_year->id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $show_btn = '<a href="' . route('loan.show', $row->id) . '"
                    class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i> ' . __('Show') . '</a>';
                    // $edit_btn = '<a href="' . route('loan.edit', $row->id) . '"
                    // class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>' . __('Edit') . '</a>';onclick="return confirm(`'.__('Do you want to delete this user?').'`);"
                    $delete_btn = ($row->getRawOriginal('status') == 1 ? '<button type="button"  class="btn btn-outline-danger btn-sm" onclick="load_member_details('.$row->member_id.')" data-bs-toggle="modal" data-bs-target="#loan_settle"><i class="bi bi-trash"></i>' . __('Close') . '</button>&nbsp;' : '');
                    $action_btn = '';
                    // (Auth::user()->can('view-ledger_account')) ? $action_btn.= $show_btn : '';
                    // (Auth::user()->can('edit-loan')) ? $action_btn .= $edit_btn : '';
                    (Auth::user()->can('delete-loan')) ? $action_btn .= $delete_btn : '';
                    (Auth::user()->can('view-loan')) ? $action_btn .= $show_btn : '';
                    return $action_btn;
                })
                ->filterColumn('member_id', function ($query, $search) {
                    $query->whereHas('member', function ($q) use ($search) {
                        $q->whereHas('user', function ($qr) use ($search) {
                            $qr->where('name', 'Like', '%' . $search . '%');
                        });
                        // $q->where('name', $search);
                    });
                })
                ->editColumn('member_id', function ($row) {
                    return $row->member->name;
                })
                ->orderColumn('member_id', function ($query, $order) {
                    $query->whereHas('member', function ($q) use ($order) {
                        $q->orderBy('name', $order);
                    });
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('loan.index', $data);
    }


    public function create()
    {

        $data['members'] = Member::with('shares')->get();
        // foreach ($data['members'] as $key => $value) {
        //     $value->fixed_saving_ledger_account()->update(['current_balance' => $value->fixed_saving()->sum('fixed_amount') ?? 0]);
        //     $value->share_ledger_account()->update(['current_balance' => $value->share_total_price ?? 0]);
        // }
        $data['loans'] = LoanCalculationMatrix::get();
        $data['page_title'] = __('Add New Loan');
        return view('loan.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->remaining_loan_amount);
        $request->validate([
            'loan_id' => 'required',
            'emi_amount' => 'required',
            'member_id' => 'required',
            'g1_member_id' => 'required',
            'g2_member_id' => 'required',
            'stamp_duty' => 'required',
            'g2_member_id' => 'required',
            'payment_type' => 'required',
            'bank_name' => 'required_if:payment_type,cheque',
            'cheque_no' => 'required_if:payment_type,cheque',
            'gcheque_no' => 'required|numeric',
            'gbank_name' => 'string'
        ]);
        $loan_no = str_pad((LoanMaster::count()) + 1, 2, '0', STR_PAD_LEFT) . '/' . $this->current_year->start_year . '-' . $this->current_year->end_year;
        $member = Member::find($request->member_id);

        $loan_master = new LoanMaster;
        $loan_master->fill($request->all());
        $loan_master->ledger_account_id = $member->loan_ledger_account->id;
        $loan_master->loan_no = $loan_no;
        $loan_master->year_id = $this->current_year->id;
        $loan_master->month = $request->month;
        $loan_master->start_month = $request->emi_month[0];
        $loan_master->end_month = Arr::last($request->emi_month);
        $loan_master->status = 1;
        $loan_master->principal_amt = LoanCalculationMatrix::find($request->loan_id)->first()->amount;
        $loan_master->save();

        //settle old loan
        if ($request->remaining_loan_amount > 0) {
            $loan_master->is_old_loan_settled = 1;
            $loan_master->save();
            $this->settle_old_loan($member->id);
        }

        //update share
        if ($request->remaining_share > 0) {
            $no_of_share = $member->total_share + $request->remaining_share;
            $this->update_member_share($member, $no_of_share);
        }

        //update fixed saving
        if ($request->remaining_fixed_saving > 0) {
            $this->update_fixed_Saving($member, $request->remaining_fixed_saving);
        }


        foreach ($request->emi_month as $key => $value) {
            LoanEMI::create([
                'loan_master_id' => $loan_master->id,
                'month' => $value,
                'member_id' => $loan_master->member_id,
                'ledger_account_id' => $member->loan_ledger_account->id,
                'principal_amt' => $loan_master->principal_amt,
                'interest' => current_loan_interest()->loan_interest,
                'interest_amt' => $request->emi_interest[$key],
                'emi' => $request->emi_amt[$key],
                'installment' => $request->installment[$key],
                'rest_principal' => $request->rest_principal[$key],
                'status' => 1,
            ]);
        }
        return redirect()->route('loan.index')
            ->withSuccess(__('New Loan is added successfully.'));
    }

    public function settle_old_loan($member_id)
    {
        $old_loan = LoanMaster::where('member_id', $member_id)->active()->first();
        $old_loan->update(['status' => 3]);
        $old_loan->loan_emis()->where('status',1)->update(['status' => 3]);
    }


    public function show(string $id)
    {
        $loan = LoanMaster::findOrFail($id);
        return view('loan.show', [
            'loan' => $loan,
            'page_title' => __('View Loan')
        ]);
    }

    public function edit(string $id)
    {
        $loan = LoanMaster::findOrFail($id);
        return view('loan.edit', [
            'loan' => $loan,
            'page_title' => __('Edit Loan')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanMaster $loan_matrix): RedirectResponse
    {
        // $request->validate(['amount' => 'required', 'minimum_emi' => 'required', 'required_share' => 'required']);
        // $input = $request->only('amount', 'minimum_emi', 'required_share', 'status');
        // $loan_matrix->update($input);

        // return redirect()->route('loan.index')
        //     ->withSuccess(__('Loan is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,LoanMaster $loan)
    {
        $request->validate([
            'amount' => 'required',
            'payment_type'=>'required',
            'bank_name' => 'required_if:payment_type,cheque',
            'cheque_no' => 'required_if:payment_type,cheque'
        ]);
        if($request->amount < 200){
            return response()->json([
                'errors' => ['amount' => __('The :attribute must be greater than :value.',['attribute' =>'amount','value' => $loan->member->loan_remaining_amount])],
                'message' => 'The given data was invalid.',
            ], 422);
        }
        if ($request->amount > 0) {
            $loan->is_old_loan_settled = 1;
            $loan->loan_settlement_amt = $request->amount;
            $loan->bank_name = $request->bank_name;
            $loan->cheque_no = $request->cheque_no;
            $loan->payment_type = $request->payment_type;
            $loan->save();
            $this->settle_old_loan($loan->member_id);
        }
        if ($request->ajax()) {
            return response()->json(['success' => true, 'msg' => __('Loan Closed SucccessFully')]);
        }
        return redirect()->route('loan.index')
            ->withSuccess(__('Loan is deleted successfully.'));
    }

    /**
     * get guarntor count  of specified resource.
     */
    public function guarantor_count(Member $member)
    {
        $guarantor_exist = LoanMaster::with('member.user')->where('g1_member_id', $member->id)->orWhere('g2_member_id', $member->id)->get();
        return response()->json(['success' => true, 'guarantor_exist' => $guarantor_exist]);
    }
}