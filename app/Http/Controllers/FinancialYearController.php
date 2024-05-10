<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ChangeYearController;
use App\Models\BulkMaster;
use App\Models\MemberShare;

class FinancialYearController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-financial_year|edit-financial_year|delete-financial_year|view-financial_year', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-financial_year', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-financial_year', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-financial_year', ['only' => ['destroy']]);
        $this->middleware('permission:view-financial_year', ['only' => ['show', 'index']]);
        parent::__construct();
    }

    public function index()
    {
        $data['financial_years'] = FinancialYear::get();
        // dd($data['financial_years']);
        $data['page_title'] = __('Financial Year');
        return view('setting.financial_year.index', $data);
    }

    public function create()
    {
        // dd(currentYear()->id);
        $data['page_title'] = __('Add New Financial Year');
        return view('setting.financial_year.create', $data);
    }

    public function store(Request $request)
    {
        // dd(currentYear()->id);
        $bulk_entry = BulkMaster::where('year_id',currentYear()->id);
        $saving = $bulk_entry->sum('fixed_saving_total');
        $interest = $bulk_entry->sum('interest_total');

        $active_share = MemberShare::where('status',1)->where('year_id',currentYear()->id);
        $active_share_count = $active_share->count();
        $share_amount = $active_share->sum('share_amount');
        $total =  $saving + $interest + $share_amount;
        // dd( 'saving-->' . ($saving) . 'interest' . ($interest) . 'active_share_count-->' . ($active_share_count) . 'share_amount-->' . ($share_amount) . 'balance' . ($total));


        $request->validate([
            'title' => 'required',
            'start_year' => 'required',
            'start_month' => 'required',
            'end_year' => 'required',
            'end_month' => 'required',
        ]);
        $input = $request->all();
        $input['total_saving'] = $saving ?? '0';
        $input['total_interest'] = $interest ?? '0';
        $input['total_share'] = $active_share_count ?? '0';
        $input['total_share_amount'] = $share_amount ?? '0';
        $input['balance'] = $total ?? '0';


            $input['is_current'] = 0;
                // if ($request->is_current == 1) {
                //     FinancialYear::query()->where('is_current', 1)->update(['is_current' => 0]);
                //     $input['is_current'] = 0;
                // }
            if ($request->is_active == 1) {
                FinancialYear::query()->where('is_active', 1)->update(['is_active' => 0]);
                $input['is_active'] = 1;
            }

        $year = FinancialYear::create($input);


        /*for create finaciayal year records*/
        $printReport = new ChangeYearController;
        $printReport->change_year($year->id);
        /* end for create finaciayal year records*/


        return redirect()->route('financial_year.index')
            ->withSuccess(__('Financial Year is created successfully.'));
    }

    public function edit($id)
    {
        $data['financial'] = FinancialYear::where('id', $id)->first();
        $data['page_title'] = __('Edit Financial Year');
        return view('setting.financial_year.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'start_year' => 'required',
            'start_month' => 'required',
            'end_year' => 'required',
            'end_month' => 'required',
        ]);

        $financial = FinancialYear::where('id',$id)->first();
        $input = $request->all();
        unset($input['is_current']);
        $financial->update($input);
        if ($request->is_current == 1) {
            FinancialYear::query()->update(['is_current' => false]);
            $financial->update(['is_current' => true]);
        }
        if ($request->is_active == 1) {
            FinancialYear::query()->update(['is_active' => false]);
            $financial->update(['is_active' => true]);
        }
        return redirect()->route('financial_year.index')
            ->withSuccess(__('Financial Year updated successfully.'));
    }
}
