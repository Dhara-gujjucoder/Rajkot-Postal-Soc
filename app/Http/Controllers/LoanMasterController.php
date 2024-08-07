<?php

namespace App\Http\Controllers;

use DateTime;
use DateInterval;
use App\Models\Member;
use App\Models\LoanEMI;
use App\Models\LoanMaster;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use App\Models\MemberFixedSaving;
use App\Traits\UpdateMemberShare;
use Illuminate\Support\Facades\Auth;
use App\Models\LoanCalculationMatrix;
use Illuminate\Http\RedirectResponse;
use App\Traits\UpdateMemberFixedSaving;
use Illuminate\Support\Facades\Validator;

class LoanMasterController extends Controller
{
    use UpdateMemberShare, UpdateMemberFixedSaving;
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
        // dd(url('/'));
        $data['loans'] = LoanMaster::get();
        $data['page_title'] = __('Loan');
        $data['members'] = Member::with('shares')->get();

        if ($request->ajax()) {
            $data = LoanMaster::query();                  //(runningLoan();<--- get active loan only)
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $aa = '_settle';
                    $action_btn = '';

                    $show_btn = '<a href="' . route('loan.show', $row->id) . '"
                    class="btn btn-outline-info btn-sm me-2"><i class="bi bi-eye"></i> ' . __('Show') . '</a>';
                    // $edit_btn = '<a href="' . route('loan.edit', $row->id) . '"
                    // class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>' . __('Edit') . '</a>';onclick="return confirm(`'.__('Do you want to delete this user?').'`);"

                    $delete_btn = '<button type="button"  class="btn btn-outline-danger btn-sm me-2" onclick="load_member_details(' . $row->member_id . ',`_settle`)" data-bs-toggle="modal" data-bs-target="#loan_settle"><i class="bi bi-trash"></i>' . __('Close') . '</button>&nbsp;';

                    $pay_loan_btn = '<button type="button" data-bs-toggle="modal" data-bs-target="#loan_pay" onclick="load_member_details(' . $row->member_id . ',`_pay`)"
                    class="btn btn-outline-warning btn-sm me-2"><i class="bi bi-eye"></i> ' . __('Pay') . '</button>';
                    // (Auth::user()->can('view-ledger_account')) ? $action_btn.= $show_btn : '';
                    // (Auth::user()->can('edit-loan')) ? $action_btn .= $edit_btn : '';

                    $guarantor_add_btn  = '&nbsp;<button type="button" class="btn btn-outline-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#guarentor_add" onclick="set_member_id(' . $row->member_id . ')"><i class="bi bi-pencil"></i> ' . __('Edit') . '</button>';


                    (Auth::user()->can('view-loan')) ? $action_btn .= $show_btn : '';
                    $action_btn .= ($row->getRawOriginal('status') == 1 ?  $pay_loan_btn : '');

                    (Auth::user()->can('delete-loan') && $row->getRawOriginal('status') == 1) ? $action_btn .= $delete_btn : '';
                    (Auth::user()->can('edit-loan') && ($row->g1_member_id == null  || $row->g2_member_id == null)) ? $action_btn .= $guarantor_add_btn : '';


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


    public function guarantor_store(Request $request, member $member)
    {
        // dd($member->id.'this');
        $validator = Validator::make(
            $request->all(),
            [
                'g1_member_id' => 'required',
                'g2_member_id' => 'required',
                'gcheque_no' => 'required|numeric',
                'gbank_name' => 'string'
            ]
        );

        if ($validator->fails()) {
            // if validation failure
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {

            $guarantor = LoanMaster::where('member_id', $member->id)->active()->first();
            $guarantor->update([
                'g1_member_id' => $request->g1_member_id,
                'g2_member_id' => $request->g2_member_id,
                'gcheque_no' => $request->gcheque_no,
                'gbank_name' => $request->gbank_name,
            ]);
            return response()->json(['message' => __('Loan updated successfully'), 'success' => true]);
        }
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'loan_id' => 'required',
            'date' => 'required',
            'emi_amount' => 'required',
            'member_id' => 'required',
            'g1_member_id' => 'required',
            'g2_member_id' => 'required',
            // 'stamp_duty' => 'required',
            // 'payment_comment' => 'required',
            // 'g2_member_id' => 'required',
            'payment_type' => 'required',
            // 'bank_name' => 'required_if:payment_type,cheque',
            'cheque_no' => 'required_if:payment_type,cheque',
            'gcheque_no' => 'required|numeric',
            'gbank_name' => 'string'
        ], [
            'g1_member_id.required' => __('Guarantor 1 field is required'),
            'g2_member_id.required' => __('Guarantor 2 field is required'),
        ]);

        $loan_no = str_pad((LoanMaster::count()) + 1, 2, '0', STR_PAD_LEFT) . '/' . $this->current_year->start_year . '-' . substr($this->current_year->end_year, -2);
        // dd($loan_no);


        $yourDate = strtotime($request->month . ' -1 month'); // substract 1 month from that date and converting it into timestamp
        $desiredDate = date("Y-m-d", $yourDate);


        $member = Member::find($request->member_id);

        //settle old loan
        if ($request->remaining_loan_amount > 0) {
            $old_loan = LoanMaster::where('member_id', $member->id)->active()->first();
            $old_loan->update(['payment_comment' => $request->payment_comment]);
            $this->settle_old_loan($member->id);
        }

        $request_all = $request->all();
        unset($request_all['payment_comment']);
        $loan_master = new LoanMaster;
        $loan_master->fill($request_all);
        $loan_master->ledger_account_id = $member->loan_ledger_account->id;
        $loan_master->loan_no = $loan_no;                         // $loan_no
        $loan_master->year_id = $this->current_year->id;
        $loan_master->month =  $desiredDate;                          //$request->month;
        $loan_master->start_month = $request->emi_month[0];
        $loan_master->end_month = Arr::last($request->emi_month);
        $loan_master->is_old_loan_settled = $request->remaining_loan_amount > 0 ? 1 : 0;
        $loan_master->status = 1;
        $loan_master->principal_amt = LoanCalculationMatrix::find($request->loan_id)->amount;

        if ($request->payment_type == 'cheque') {
            $loan_master->payment_type_status = bank_ledger_name() . 'cheque-' . $loan_master->cheque_no;
            $loan_master->ledger_group_id = 10;
            $loan_master->save();
        } else {
            $loan_master->payment_type_status = 'Cash';
            $loan_master->ledger_group_id = 4;
            $loan_master->save();
        }

        $loan_master->save();


        //update share
        if ($request->remaining_share > 0) {
            $no_of_share = $member->total_share + $request->remaining_share;
            $this->update_member_share($member, $no_of_share);
        }

        //update fixed saving
        if ($request->remaining_fixed_saving > 0) {
            $this->update_fixed_Saving($member, $request->remaining_fixed_saving, $loan_master->month);
        }
        // set member's current balance to loan principal amt
        $member->loan_ledger_account->update(['current_balance' => ($member->loan_ledger_account->current_balance + $loan_master->principal_amt)]);

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
                'principal' => $request->principal[$key],
                'rest_principal' => $request->rest_principal[$key],
                'status' => 1,
            ]);
        }
        return redirect()->route('loan.index')
            ->withSuccess(__('New Loan is added successfully.'));
    }

    public function settle_old_loan($member_id, $date = null)
    {
        $old_loan = LoanMaster::where('member_id', $member_id)->active()->first();
        $member = Member::find($member_id);
        $old_loan->update(['status' => 3,'loan_settlement_amt'=> $member->loan_remaining_amount,
         'loan_settlment_month' => ($date ?? date('Y-m-d'))]);
        $old_loan->loan_emis()->where('status', 1)->update(['status' => 3]);
        $member->loan_ledger_account->update(['current_balance' => 0]);


        // if ($resign->payment_type == 'cheque') {

        //     $ledger_account = LedgerAccount::where('ledger_group_id', 10)->first();
        //     $ledger_account->update(['current_balance' => ($ledger_account->current_balance +  $resign->total_amount)]);
        // } else {

        //     $ledger_account = LedgerAccount::where('ledger_group_id', 4)->first();
        //     $ledger_account->update(['current_balance' => ($ledger_account->current_balance +  $resign->total_amount)]);
        // }


    }


    public function show(string $id, Request $request)
    {
        // dd($request->all());
        $loan = LoanMaster::findOrFail($id);

        return view('loan.show', [
            'loan' => $loan,
            'loan_show_dashboard' => $request->loan_show_dashboard,
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
    public function destroy(Request $request, LoanMaster $loan)
    {
        // dd($request->amount<=$loan->member->loan_remaining_amount);
        $request->validate([
            'amount' => 'required|gt:0',
            'payment_type' => 'required',
            // 'bank_name' => 'required_if:payment_type,cheque',
            'cheque_no' => 'required_if:payment_type,cheque'
        ]);
        if ($request->amount < $loan->member->loan_remaining_amount) {
            return response()->json([
                'errors' => ['amount' => __('The :attribute must be greater than :value.', ['attribute' => 'amount', 'value' => $loan->member->loan_remaining_amount])],
                'message' => 'The given data was invalid.',
            ], 422);
        }
        // dd($request->all());
        if ($request->amount > 0) {

            // set dynamic date
            $loan_settlment_month = $request->loan_settlment_month ?? date('d-m-Y');           //date('d-m-Y');



            $loan->is_old_loan_settled = 1;
            $loan->loan_settlement_amt = $request->amount;
            $loan->bank_name = $request->bank_name;
            $loan->cheque_no = $request->cheque_no;
            $loan->payment_type = $request->payment_type;

            if ($request->payment_type == 'cheque') {
                $loan->payment_type_status = bank_ledger_name() . 'cheque-' . $loan->cheque_no;
                $loan->ledger_group_id = 10;
                $loan->save();
            } else {
                $loan->payment_type_status = 'Cash';
                $loan->ledger_group_id = 4;
                $loan->save();
            }

            $loan->payment_comment = $request->payment_comment;  //add
            $loan->save();
            $this->settle_old_loan($loan->member_id,  $loan_settlment_month );
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

    /**
     * Partially Payment of loan
     */
    public function partial_pay(Request $request, LoanMaster $loan)
    {
        // dd($request->all());
        $request->validate([
            'amount' => 'required',
            'payment_type' => 'required',
            // 'bank_name' => 'required_if:payment_type,cheque',
            'cheque_no' => 'required_if:payment_type,cheque'
        ]);
        $member = $loan->member;
        if ($request->amount > 0) {
            //half payment
            // dd($request->all());
            $loan_emi  = new LoanEMI();
            $loan_emi->loan_master_id = $loan->id;
            $loan_emi->month = date('m-Y');
            $loan_emi->member_id = $loan->member_id;
            $loan_emi->ledger_account_id = $member->loan_ledger_account->id;
            $loan_emi->principal_amt =  $loan->principal_amt;
            $loan_emi->interest = current_loan_interest()->loan_interest;
            $loan_emi->interest_amt = 0;
            $loan_emi->emi = 0;
            $loan_emi->principal = $request->amount;
            $loan_emi->rest_principal = $loan->member->loan_remaining_amount - $request->amount;
            $loan_emi->status = 2;
            $loan_emi->is_half_paid = 1;
            $loan_emi->payment_month = date('d-m-Y');
            $loan_emi->cheque_no = $request->cheque_no;
            $loan_emi->payment_type = $request->payment_type;

            if ($request->payment_type == 'cheque') {
                // dd('d');
                $loan_emi->payment_type_status = bank_ledger_name() . 'cheque-' . $loan_emi->cheque_no;
                $loan_emi->ledger_group_id = 10;
                $loan_emi->save();
            } else {
                $loan_emi->payment_type_status = 'Cash';
                $loan_emi->ledger_group_id = 4;
                $loan_emi->save();
            }
            $loan_emi->payment_comment = $request->payment_comment;
            $loan_emi->save();



            $member = Member::find($loan->member_id);
            if (($loan->member->loan_remaining_amount - $request->amount) > 0) {

                //all remaining emi Entries

                $emi_amount = $loan->emi_amount;
                $loan_amt = $loan->member->loan_remaining_amount - $request->amount;
                $loan_amount = $loan_amt;
                $no_of_emi = ($loan_amt / $loan->emi_amount);
                $emi_c = getLoanParam()[0];
                $emi_d = getLoanParam()[1];
                $rate = current_loan_interest()->loan_interest;

                $dmonth = date('d-m-Y');
                //  $dmonth = date('d-m-Y',strtotime('01-03-2024'));

                $member->loan_ledger_account->update(['current_balance' => $loan_amt]);

                $loan->loan_emis()->pending()->delete();

                while ($loan_amt > 0) {
                    // for ($i = 1; $i <= $no_of_emi; $i++) {
                    $emi_interest = intval($loan_amt * $rate / 100 * $emi_c / $emi_d);
                    $date = new DateTime($dmonth);
                    $date->add(new DateInterval('P1M'));
                    $dmonth = $date->format('d-m-Y');

                    if ($loan_amt > 0) {
                        if ($loan_amt < $emi_amount) {
                            $emi_amount = $loan_amt;
                            $loan_amt = 0;
                        } else {

                            $loan_amt = intval($loan_amt - ($emi_amount - $emi_interest));
                        }
                        // console.log($emi_amount, $emi_interest);
                        LoanEMI::create([
                            'loan_master_id' => $loan->id,
                            'month' => $date->format('m-Y'),
                            'member_id' => $loan->member_id,
                            'ledger_account_id' => $member->loan_ledger_account->id,
                            'principal_amt' => $loan_amount,
                            'interest' => current_loan_interest()->loan_interest,
                            'interest_amt' => $emi_interest,
                            'emi' => $emi_amount,
                            'principal' => $emi_amount - $emi_interest,
                            'rest_principal' => $loan_amt,
                            'status' => 1,
                        ]);
                    }
                }
            } else {
                $loan->loan_emis()->pending()->delete();
                // $loan->bank_name = $request->bank_name;
                $loan->cheque_no = $request->cheque_no;
                $loan->payment_type = $request->payment_type;
                $loan->payment_comment = $request->payment_comment;
                $loan->status = 2;
                $loan->save();
                $member->loan_ledger_account->update(['current_balance' => 0]);
            }
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 'msg' => __('Loan Paid SucccessFully')]);
        }
        return redirect()->route('loan.index')
            ->withSuccess(__('Loan is deleted successfully.'));
    }
}
