<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\LoanEMI;
use App\Models\Receipt;
use App\Models\BulkEntry;
use App\Models\BulkMaster;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use App\Models\BulkEntryMaster;
use App\Models\SalaryDeduction;
use App\Models\MemberFixedSaving;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\BulkEntry as ExportsBulkEntry;
use App\Models\LoanMaster;

class BulkEntryController extends Controller
{
    /**
     * check permission
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-bulk_entries|edit-bulk_entries|delete-bulk_entries|view-bulk_entries', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-bulk_entries', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-bulk_entries', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-bulk_entries', ['only' => ['destroy']]);
        parent::__construct();
    }


    public function index()
    {

        // $entries = BulkEntry::where('month','04-2024')->get();
        // foreach($entries as $entry){
        //     $member = Member::find( $entry->member_id);
        //     $fixed_saving_entry = MemberFixedSaving::create([
        //                 'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
        //                 'member_id' => $member->id,
        //                 'month' => '04-2024',
        //                 'fixed_amount' => $entry->fixed,
        //                 'year_id' => $this->current_year->id,
        //                 'status' => 1
        //             ]);
        //             $member_fixed_saving = $member->fixed_saving_ledger_account->opening_balance + $member->fixed_saving()->sum('fixed_amount');
        //             $member->fixed_saving_ledger_account->update(['current_balance' => $member_fixed_saving]);
        // }


        $user = Auth::user();
        $months = getMonthsOfYear($this->current_year->id);
        $data['bulk_entries'] = BulkMaster::orderBy('id', 'desc')->get();
        // dd($data['bulk_entries'] );
        $data['page_title'] = __('Bulk Entries');
        return view('bulk_entries.index', $data);
    }


    public function create()
    {
        $data['page_title'] = __('Add New Bulk Entry');
        $data['departments'] = Department::whereNot('id', 5)->get();
        $data['ledger_type'] = ['principal', 'interest', 'fixed', 'ms', 'total_amount'];
        $data['total']['principal'] = 0;
        $data['total']['interest'] = 0;
        $data['total']['fixed'] = 0;
        $data['total']['ms'] = 0;
        $data['total']['total_amount'] = 0;

        $data['months'] = getMonthsOfYear($this->current_year->id);
        // dd(date('m-Y',strtotime('01-'.(currentYear()->start_month - 1) . '-' . currentYear()->start_year)));
        $data['previous_month'] = BulkEntryMaster::get()->last()->month ??
        date('m-Y', strtotime('01-'.(currentYear()->start_month - 1) . '-' . currentYear()->start_year));
        $data['next_month'] = date('m-Y', strtotime(date('01-' . $data['previous_month']) . " +1 month"));
        foreach ($data['months'] as $key => $month) {
            $entry = BulkMaster::where('month', $month['value'])->first();
            $entry ? $data['months'][$key]['is_disable'] = 1 : $data['months'][$key]['is_disable'] = 0;
        }
        foreach ($data['departments'] as $key => $department) {
            $members = $department->members()->orderBy('uid', 'asc')->get();
            $department->principal_total = 0;
            $department->interest_total = 0;
            $department->fixed_total = 0;
            $department->ms_total = 0;
            $department->total_amount_total = 0;
            //   dd($data['previous_month']);
            $members->map(function ($item, $subkey) use ($data, $department) {
                $prefill = BulkEntry::where('user_id', $item->user_id)->where('department_id', $department->id)->where('month', $data['previous_month'])->first();
                // dump($item->id);
                // dd($data['next_month']);
                $loan_emi = LoanEMI::where('member_id', $item->id)->where('month', $data['next_month'])->where('status', 1)->first(); //->where('status', 1)
                $item->principal = $loan_emi->principal ?? 0;
                $item->emi_id = $loan_emi->id ?? '';
                $item->interest = $loan_emi->interest_amt ?? 0;
                $item->fixed = $prefill->fixed ?? 0;
                $fixed_saving = MemberFixedSaving::where('member_id', $item->id)->where('month', $data['next_month'])->where('status', 1)->first();
                if($fixed_saving){
                    $item->fixed = $fixed_saving->fixed_amount;
                }

                $item->ms =  0;
                $item->total_amount += $item->principal + $item->interest + $item->fixed + $item->ms;
                // $department->{$value . '_total'} += $item->{$value};
                foreach ($data['ledger_type'] as $key => $value) {
                    // for get department wise total
                    $department->{$value . '_total'} += $item->{$value};
                    // for prefill previous month value
                    // $item->{$value} = $prefill->{$value} ?? 0;
                    // if($loan_emi ){
                    // }
                }
            });
            $department->members = $members;

            // for get all field total
            $data['total']['principal'] += $department->principal_total;
            $data['total']['interest'] += $department->interest_total;
            $data['total']['fixed'] += $department->fixed_total;
            $data['total']['ms'] += $department->ms_total;
            $data['total']['total_amount'] += $department->total_amount_total;
            $data['bulk_master'] = BulkMaster::where('month', $data['previous_month'])->first();
            $department->is_ms_applicable = $data['bulk_master']->is_ms_applicable ?? 0;
            $department->ms_value = $data['bulk_master']->ms_value ?? 0;
        }
        $data['prefill'] = SalaryDeduction::get()->all();
        return view('bulk_entries.create', $data);
    }


    public function store(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'month' => 'required',
        //     'status' => 'required'
        // ]);
        try {
            $month_total = 0;
            $principal_total = 0;
            $interest_total = 0;
            $fixed_saving_total = 0;
            $ms_total = 0;

            $data['departments'] = Department::whereNot('id', 5)->get();
            foreach ($data['departments'] as $dept) {
                $month_total += $request->{'summary_total_amount_total_' . $dept->id};
                $principal_total += $request->{'summary_principal_total_' . $dept->id};
                $interest_total += $request->{'summary_interest_total_' . $dept->id};
                $fixed_saving_total += $request->{'summary_fixed_total_' . $dept->id};
                $ms_total += $request->{'summary_ms_total_' . $dept->id};
            }
            $bulk_master = BulkMaster::create([
                'year_id' => $this->current_year->id,
                'month' =>  $request->month,
                'is_ms_applicable' =>  $request->is_ms_applicable ?? 0,
                'ms_value' => $request->ms_value ?? 0,
                'created_by' => Auth::user()->id,
                'total' =>  $month_total,
                'status' => $request->status,
                'principal_total' =>  $principal_total,
                'interest_total' => $interest_total,
                'fixed_saving_total' => $fixed_saving_total,
                'ms_total' => $ms_total,
                'ledger_ac_id' => LedgerAccount::where('ledger_group_id', 10)->first()->id,
            ]);

            foreach ($data['departments'] as $key => $department) {

                $receipt_no  = $bulk_master->id . $department->id . '0' . (Receipt::latest()->first() ? Receipt::latest()->first()->id + 1 : 1);
                $receipt = Receipt::create([
                    'year_id' => $this->current_year->id,
                    'month' => $bulk_master->month,
                    'department_id' => $department->id,
                    // 'bulk_entry_master_id' => $bulk_entry_master[$key]->id,
                    'receipt_no' => $receipt_no,
                    'cheque_no' => $request->{'cheque_no_' . $department->id},
                    'exact_amount' => $request->{'exact_amount_' . $department->id} ?? 0
                ]);
                $bulk_entry_master = BulkEntryMaster::create([
                    'department_id' => $department->id,
                    'department_name'=> $department->department_name,
                    'bulk_master_id' =>  $bulk_master->id,
                    'year_id' => $this->current_year->id,
                    'month'    => $request->month,
                    'receipt_id' => $receipt->id,
                    'rec_no' => $receipt_no,
                    'exact_amount' =>  $request->{'exact_amount_' . $department->id} ?? 0,
                    'cheque_no' =>  $request->{'cheque_no_' . $department->id},
                    'department_total'    =>     $request->{'summary_total_amount_total_' . $department->id},
                    'created_by' => Auth::user()->id
                ]);

                foreach ($department->members as $key => $member) {
                    BulkEntry::create([
                        'user_id' => $member->user_id,
                        'member_id' => $member->id,
                        'department_id' => $department->id,
                        'bulk_entry_master_id' =>  $bulk_entry_master->id,
                        'year_id' => $this->current_year->id,
                        'ledger_group_id' => $this->current_year->id,
                        'month' => $request->month,
                        'rec_no' => $bulk_entry_master->receipt->receipt_no,
                        'principal' =>     $request->{'principal_' . $department->id . '_' . $member->user_id},
                        'interest' => $request->{'interest_' . $department->id . '_' . $member->user_id},
                        'fixed' => $request->{'fixed_' . $department->id . '_' . $member->user_id},
                        'ms' => $request->{'ms_' . $department->id . '_' . $member->user_id},
                        'total_amount' => $request->{'total_amount_' . $department->id . '_' . $member->user_id},
                        'status' => $request->status,
                    ]);
                    //effect on fixed saving and loan emi if bulk entry is in completed status
                    if($bulk_master->getRawOriginal('status') == 2){

                        // ********* for fixed saving entry***********//
                          $fixed_saving_entry = MemberFixedSaving::create([
                              'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
                              'member_id' => $member->id,
                              'month' => $request->month,
                              'fixed_amount' => $request->{'fixed_' . $department->id . '_' . $member->user_id},
                              'year_id' => $this->current_year->id,
                              'status' => 1
                          ]);
                          $member_fixed_saving = $member->fixed_saving_ledger_account->opening_balance + $member->fixed_saving()->sum('fixed_amount');
                          $member->fixed_saving_ledger_account->update(['current_balance' => $member_fixed_saving]);

                          //********* for loan entry***********//
                          $loan_emi_id = $request->{'emi_id_' . $department->id . '_' . $member->user_id};
                          $emi = $request->{'interest_' . $department->id . '_' . $member->user_id};
                          if($loan_emi_id && $emi){
                              $emi = LoanEMI::find($loan_emi_id);
                              if($emi){
                                  $emi->update(['status' => 2]);
                                  $member->loan_ledger_account->update(['current_balance' => ($member->loan_ledger_account->current_balance - $emi->principal)]);
                              }
                          }
                    }


                }
            }
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('bulk_entries.index')
                ->withError(__('Something went wrong'));
        }

        return redirect()->route('bulk_entries.index')
            ->withSuccess(__('Bulk Entry added successfully.'));
    }

    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {

        $data['bulk_master'] = BulkMaster::where('id', $id)->first();
        if ($data['bulk_master']->getRawOriginal('status') == 2) {
            return redirect()->back()->withError(__('Bulk Entry already completed.'));
        }
        $data['bulk_entry_master'] = BulkEntryMaster::where('bulk_master_id', $data['bulk_master']->id)->get();
        $data['page_title'] = __('Edit Bulk Entry');
        $data['departments'] = Department::whereIn('id',  $data['bulk_entry_master']->pluck('department_id'))->get();
        $data['ledger_type'] = ['principal', 'interest', 'fixed', 'ms', 'total_amount'];
        $data['total']['principal'] = 0;
        $data['total']['interest'] = 0;
        $data['total']['fixed'] = 0;
        $data['total']['ms'] = 0;
        $data['total']['total_amount'] = 0;

        $data['months'] = getMonthsOfYear($this->current_year->id);
        $data['previous_month'] = $data['bulk_master']->month ?? '';
        $data['next_month'] = $data['bulk_master']->month ?? '';

        $data['bulk_entry_master'] = BulkEntryMaster::where('bulk_master_id', $data['bulk_master']->id)->distinct('department_id')->get();
        // dd($data['bulk_entry_master']);
        foreach ($data['bulk_entry_master'] as $key => $bulk_entry_master) {
            $department = Department::find($bulk_entry_master->department_id);
            DB::enableQueryLog();
            $bulk_entry_master = BulkEntryMaster::with('members')->where('bulk_master_id', $data['bulk_master']->id)
                ->where('department_id', $bulk_entry_master->department_id)->first();
            // dd($bulk_entry_master);
            // dd(DB::getQueryLog());
            // dd($data['bulk_master']->id,$department->id);
            $members = $bulk_entry_master->members;
            // dd($members);
            $members->map(function ($item, $subkey) use ($data, $department, $key) {
                $prefill = BulkEntry::where('user_id', $item->user_id)->where('department_id', $department->id)->where('month', $data['previous_month'])->first();
                $loan_emi = LoanEMI::where('member_id', $item->id)->where('month', $data['next_month'])->where('status', 1)->first();

                $item->emi_id = $loan_emi->id ?? '';
                foreach ($data['ledger_type'] as $skey => $value) {
                    // for prefill edit value
                    $item->{$value} = $prefill->{$value} ?? 0;
                    // for get department wise total
                    $data['departments'][$key]->{$value . '_total'} += $item->{$value};
                }
            });
            $data['departments'][$key]->members = $members;
            $data['departments'][$key]->exact_amount = $data['bulk_entry_master'][$key]->exact_amount ?? 0;
            $data['departments'][$key]->cheque_no = $data['bulk_entry_master'][$key]->cheque_no ?? '';
            // for get all field total
            $data['total']['principal'] += $data['departments'][$key]->principal_total;
            $data['total']['interest'] += $data['departments'][$key]->interest_total;
            $data['total']['fixed'] += $data['departments'][$key]->fixed_total;
            $data['total']['ms'] += $data['departments'][$key]->ms_total;
            $data['total']['total_amount'] += $data['departments'][$key]->total_amount_total;
            $data['departments'][$key]->is_ms_applicable = $data['bulk_master']->is_ms_applicable ?? 0;
            $data['departments'][$key]->ms_value = $data['bulk_master']->ms_value ?? 0;
        }
        // dd($data['departments']);
        return view('bulk_entries.edit', $data);
    }


    public function update(Request $request, string $id)
    {
        // $request->validate([
        //     'status' => 'required'
        // ]);

        $bulk_master = BulkMaster::where('id', $id)->first();
        $bulk_entry_master = BulkEntryMaster::with('members')->where('month', $bulk_master->month)->where('bulk_master_id', $bulk_master->id)->get();
        // dd( $bulk_entry_master[0]);
        $data['page_title'] = __('Add New Bulk Entry');
        $data['departments'] = Department::whereIn('id', $bulk_entry_master->pluck('department_id'))->get();
        $data['ledger_type'] = ['principal', 'interest', 'fixed', 'ms', 'total_amount'];
        $month_total = 0;
        foreach ($data['departments'] as $dept) {
            $month_total += $request->{'summary_total_amount_total_' . $dept->id};
        }
        // dd($month_total);
        $bulk_master->update([
            'is_ms_applicable' =>  $request->is_ms_applicable,
            'ms_value' => $request->ms_value ?? 0,
            'total' =>   $month_total,
            'status' => $request->status,
        ]);
        BulkEntry::whereIn('bulk_entry_master_id', $bulk_entry_master->pluck('id')->all())->delete();
        foreach ($data['departments'] as $key => $department) {
            $bulk_entry_master[$key]->update([
                'exact_amount' =>  $request->{'exact_amount_' . $department->id} ?? 0,
                'cheque_no' =>  $request->{'cheque_no_' . $department->id},
                'department_total' => $request->{'summary_total_amount_total_' . $department->id} ?? 00,
            ]);
            // dd($bulk_entry_master[$key]->members);
            $bulk_entry_master[$key]->receipt->update([
                'cheque_no' => $request->{'cheque_no_' . $department->id},
                'exact_amount' => $request->{'exact_amount_' . $department->id} ?? 0,
            ]);
            // dd($department->members()->withTrashed()->get());
            foreach ($bulk_entry_master[$key]->members as $subkey => $member) {
                // $member = Member::withTrashed()->where('user_id',$user_id)->first();

                // $department = Department::where('id',$request->department_id[$subkey])->first();

                BulkEntry::create([
                    'user_id' => $member->user_id,
                    'member_id' => $member->id,
                    'department_id' => $department->id,
                    'bulk_entry_master_id' =>  $bulk_entry_master[$key]->id,
                    'year_id' => $this->current_year->id,
                    'rec_no' => $bulk_entry_master[$key]->receipt->receipt_no,
                    'ledger_group_id' => $this->current_year->id,
                    'month' => $bulk_entry_master[$key]->month,
                    'principal' =>     $request->{'principal_' . $department->id . '_' . $member->user_id},
                    'interest' => $request->{'interest_' . $department->id . '_' . $member->user_id},
                    'fixed' => $request->{'fixed_' . $department->id . '_' . $member->user_id},
                    'ms' => $request->{'ms_' . $department->id . '_' . $member->user_id},
                    'total_amount' => $request->{'total_amount_' . $department->id . '_' . $member->user_id},
                    'status' => $request->status
                ]);
                if($bulk_master->getRawOriginal('status') == 2){
                    // dd('d');
                    //********* for fixed saving entry***********//
                      $fixed_saving_entry = MemberFixedSaving::create([
                          'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
                          'member_id' => $member->id,
                          'month' =>  $bulk_master->month,
                          'fixed_amount' => $request->{'fixed_' . $department->id . '_' . $member->user_id},
                          'year_id' => $this->current_year->id,
                          'status' => 1
                      ]);
                      $member_fixed_saving = $member->fixed_saving_ledger_account->opening_balance + $member->fixed_saving()->sum('fixed_amount');
                      $member->fixed_saving_ledger_account->update(['current_balance' => $member_fixed_saving]);

                      //********* for loan entry***********//
                      $loan_emi_id = $request->{'emi_id_' . $department->id . '_' . $member->user_id};
                      $emi = $request->{'interest_' . $department->id . '_' . $member->user_id};
                      if($loan_emi_id && $emi){
                          $emi = LoanEMI::find($loan_emi_id);
                          if($emi){
                              $emi->update(['status' => 2]);
                              $member->loan_ledger_account->update(['current_balance' => ($member->loan_ledger_account->current_balance - $emi->principal)]);
                          }
                      }
                }

            }
        }
        return redirect()->route('bulk_entries.index')
            ->withSuccess(__('Bulk Entry Edited successfully.'));
    }


    public function destroy(string $id)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function export(string $id)
    {
        $month = BulkMaster::where('id', $id)->first()->month;
        $filename = date('M-Y', strtotime('01-' . $month)) . '-' . $this->current_year->title;
        return (new ExportsBulkEntry($month))->download($filename . '.xlsx');
    }
}
