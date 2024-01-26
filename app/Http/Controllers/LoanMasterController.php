<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\LoanMaster;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\LoanCalculationMatrix;
use Illuminate\Http\RedirectResponse;

class LoanMasterController extends Controller
{
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
                    $show_btn = '<a href="'.route('loan.show', $row->id).'"
                    class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i> ' .__('Show').'</a>';
                    // $edit_btn = '<a href="' . route('loan.edit', $row->id) . '"
                    // class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>' . __('Edit') . '</a>';
                    // $delete_btn = '<form action="' . route('loan.destroy', $row->id) . '" method="post"><button type="submit" class="btn btn-outline-danger btn-sm"
                    // onclick="return confirm(' . __('Do you want to delete this salary deduction?') . ';"><i class="bi bi-trash"></i>' . __('Delete') . '</button></form>';
                    $action_btn = '';
                    // (Auth::user()->can('view-ledger_account')) ? $action_btn.= $show_btn : '';
                    // (Auth::user()->can('edit-loan')) ? $action_btn .= $edit_btn : '';
                    // (Auth::user()->can('delete-loan')) ? $action_btn .= $delete_btn : '';
                    (Auth::user()->can('view-loan')) ? $action_btn .= $show_btn : '';
                    return $action_btn;
                })
                ->filterColumn('member_id', function ($query, $search) {
                    $query->whereHas('member', function ($q) use ($search) {
                        $q->whereHas('user', function ($qr) use ($search) {
                            $qr->where('name', 'Like','%'.$search.'%');
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
        $data['loans'] = LoanCalculationMatrix::get();
        $data['members'] = Member::with('shares')->get();
        $data['page_title'] = __('Add New Loan');
        return view('loan.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
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
            'gbank_name'=>'string'
        ]);
        $loan_no = str_pad((LoanMaster::count()) + 1, 2, '0', STR_PAD_LEFT).'/'.$this->current_year->start_year.'-'.$this->current_year->end_year;
        LoanMaster::create([
            'loan_no' => $loan_no,
            'year_id' => $this->current_year->id,
            'month' => date('m-Y'),
            'member_id'  => $request->member_id,
            'start_month' => date('m-Y'),
            'end_month' => date('m-Y'),
            'loan_id' => $request->loan_id,
            'principal_amt' => LoanCalculationMatrix::find($request->loan_id)->first()->amount,
            'emi_amount' => $request->emi_amount,
            'loan_settlement_amt' => $request->loan_settlement_amt,
            'total_share_amt' => $request->total_share_amt,
            'stamp_duty' => $request->stamp_duty,
            'fixed_saving' => $request->fixed_saving,
            'total_amt' => $request->total_amt,
            'payment_type' => $request->payment_type,
            'bank_name' => $request->bank_name,
            'cheque_no' => $request->cheque_no,
            'g1_member_id' => $request->g1_member_id,
            'g2_member_id' => $request->g2_member_id,
            'gcheque_no' => $request->gcheque_no,
            'gbank_name' => $request->gbank_name,
            'status' => 1
        ]);
        return redirect()->route('loan.index')
            ->withSuccess(__('New Loan is added successfully.'));
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
        $request->validate(['amount' => 'required', 'minimum_emi' => 'required', 'required_share' => 'required']);
        $input = $request->only('amount', 'minimum_emi', 'required_share', 'status');
        $loan_matrix->update($input);

        return redirect()->route('loan.index')
            ->withSuccess(__('Loan is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanMaster $loan_matrix): RedirectResponse
    {
        $loan_matrix->delete();
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
