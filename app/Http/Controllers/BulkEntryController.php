<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\BulkEntry;
use App\Models\BulkMaster;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\BulkEntryMaster;
use App\Models\SalaryDeduction;
use Illuminate\Support\Facades\Auth;
use App\Exports\BulkEntry as ExportsBulkEntry;

class BulkEntryController extends Controller
{
    /**
     * check permission
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-ledger_entries|edit-ledger_entries|delete-ledger_entries|view-ledger_entries', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-ledger_entries', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-ledger_entries', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-ledger_entries', ['only' => ['destroy']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $months = getYearDropDown($this->current_year->id);
        $data['bulk_entries'] = BulkMaster::get();
        $data['page_title'] = __('Bulk Entries');
        return view('bulk_entries.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
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

        $data['months'] = getYearDropDown($this->current_year->id);
        $data['previous_month'] = BulkEntryMaster::get()->last()->month ?? '';
        $data['next_month'] = date('m-Y', strtotime(date('01-' . $data['previous_month']) . " +1 month"));
        foreach ($data['months'] as $key => $month) {
            $entry = BulkMaster::where('month', $month['value'])->first();
            $entry ? $data['months'][$key]['is_disable'] = 1 : $data['months'][$key]['is_disable'] = 0;
        }
        foreach ($data['departments'] as $key => $department) {
            $members = $department->members()->orderBy('uid', 'asc')->get();
            $department->principal_total = 0;
            //   dd($data['previous_month']);
            $members->map(function ($item, $subkey) use ($data, $department) {
                $prefill = SalaryDeduction::where('user_id', $item->user_id)->where('department_id', $department->id)->where('month', '3-2022')->first();
                foreach ($data['ledger_type'] as $key => $value) {
                    // for prefill previous month value 
                    $item->{$value} = $prefill->{$value} ?? 0;

                    // for get department wise total 
                    $department->{$value . '_total'} += $item->{$value};
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'status' => 'required'
        ]);
        // dd($request->all());
        try {
            $month_total = 0;
            $data['departments'] = Department::whereNot('id', 5)->get();
            foreach ($data['departments'] as $dept) {
                $month_total += $request->{'summary_total_amount_total_' . $dept->id};
            }
            $bulk_master = BulkMaster::create([
                'year_id' => $this->current_year->id,
                'month' =>  $request->month,
                'is_ms_applicable' =>  $request->is_ms_applicable ?? 0,
                'ms_value' => $request->ms_value ?? 0,
                'created_by' => Auth::user()->id,
                'total' =>  $month_total,
                'status' => $request->status,
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
                    ]);
                }
            }
    
        } catch (\Throwable $th) {
            return redirect()->route('bulk_entries.index')
            ->withError(__('Something went wrong'));
        }
      
        return redirect()->route('bulk_entries.index')
            ->withSuccess(__('Bulk Entry added successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $data['bulk_master'] = BulkMaster::where('id', $id)->first();
        if($data['bulk_master']->getRawOriginal('status') == 2){
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

        $data['months'] = getYearDropDown($this->current_year->id);
        $data['previous_month'] = $data['bulk_master']->month ?? '';
        $data['next_month'] = $data['bulk_master']->month ?? '';

        foreach ($data['departments'] as $key => $department) {
            $members = $department->members()->orderBy('uid', 'asc')->get();
            $members->map(function ($item, $subkey) use ($data, $department) {
                $prefill = BulkEntry::where('user_id', $item->user_id)->where('department_id', $department->id)->where('month', $data['previous_month'])->first();
                foreach ($data['ledger_type'] as $skey => $value) {
                    // for prefill previous month value 
                    $item->{$value} = $prefill->{$value} ?? 0;

                    // for get department wise total 
                    $department->{$value . '_total'} += $item->{$value};
                }
            });
            $department->members = $members;
            $department->exact_amount = $data['bulk_entry_master'][$key]->exact_amount ?? 0;
            $department->cheque_no = $data['bulk_entry_master'][$key]->cheque_no ?? '';
            // for get all field total 
            $data['total']['principal'] += $department->principal_total;
            $data['total']['interest'] += $department->interest_total;
            $data['total']['fixed'] += $department->fixed_total;
            $data['total']['ms'] += $department->ms_total;
            $data['total']['total_amount'] += $department->total_amount_total;
            $department->is_ms_applicable = $data['bulk_master']->is_ms_applicable ?? 0;
            $department->ms_value = $data['bulk_master']->ms_value ?? 0;
        }
        return view('bulk_entries.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $bulk_master = BulkMaster::where('id', $id)->first();
        $bulk_entry_master = BulkEntryMaster::where('month', $bulk_master->month)->where('bulk_master_id', $bulk_master->id)->get();
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
            'ms_value' => $request->ms_value,
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
            $bulk_entry_master[$key]->receipt->update([
                'cheque_no' => $request->{'cheque_no_' . $department->id},
                'exact_amount' => $request->{'exact_amount_' . $department->id} ?? 0,
            ]);
            foreach ($department->members as $subkey => $member) {
                BulkEntry::create([
                    'user_id' => $member->user_id,
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
                ]);
            }
        }
        return redirect()->route('bulk_entries.index')
            ->withSuccess(__('Bulk Entry Edited successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
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
        $filename = date('M-Y',strtotime('01-'.$month)).'-'.$this->current_year->title;
        return (new ExportsBulkEntry($month))->download($filename.'.xlsx');
    }
}
