<?php

namespace App\Http\Controllers;

use App\Models\BulkEntry;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\SalaryDeduction;
use Illuminate\Support\Facades\Auth;

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
        $data['ledger_entries'] = BulkEntry::where('year_id', $this->current_year->id)->orderBy('id', 'ASC')->get();
        $data['page_title'] = __('Ledger Entries');
        return view('bulk_entries.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['page_title'] = __('Add Bulk Entry');
        $data['departments'] = Department::whereNot('id', 5)->get();
        $data['total']['principal'] = 0;
        $data['total']['interest'] = 0;
        $data['total']['fixed'] = 0;
        $data['total']['ms'] = 0;
        $data['total']['sub_total'] = 0;
        foreach ($data['departments'] as $key => $department) {
            $members = $department->members()->orderBy('uid','asc')->get();
            $department->principal_total = 0;
            $principal_total =  0;
              $members->map(function ($item, $subkey) use ($principal_total,$department) {
                $prefill = SalaryDeduction::where('user_id', $item->user_id)->where('department_id',$department->id)->where('month', '3-2022')->first();
                 $item->principal = $prefill->principal ?? 0;
                 $item->interest = $prefill->interest ?? 0;
                 $item->fixed = $prefill->fixed ?? 0;
                 $item->total_amount = $prefill->total_amount ?? 0;
                 $item->ms = $prefill->ms ?? 0;

                //  $item->principal_total = $principal_total +  $item->principal;
                 $department->interest_total +=  $item->interest;
                 $department->fixed_total +=  $item->fixed;
                 $department->ms_total +=  $item->ms_total;
                 $department->principal_total += $item->principal;
                 $department->sub_total += $item->total_amount;
                //  dump( $item->principal.'___'.$item->department_id.'___'.$department->principal_total);
                });
            $department->members = $members;
            $data['total']['principal'] += $department->principal_total;
            $data['total']['interest'] += $department->interest_total;
            $data['total']['fixed'] += $department->fixed_total;
            $data['total']['ms'] += $department->ms_total;
            $data['total']['sub_total'] += $department->sub_total;
        }
        $data['prefill'] = SalaryDeduction::get()->all();
        return view('bulk_entries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
