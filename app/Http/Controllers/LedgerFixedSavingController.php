<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use App\Models\MemberFixedSaving;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LedgerFixedSavingExport;
use App\Exports\AllLedgerFixedSavingExport;

class LedgerFixedSavingController extends Controller
{
    public function index(){
        $data['page_title'] = __('Fixed Saving');
        $data['fixed_savings'] = LedgerAccount::where('ledger_group_id',1)->get();
        $data['members'] = Member::withTrashed()->orderBy('uid', 'ASC')->get();

        return view('ledger_reports.fixed_saving.index',$data);
    }

    public function fixed_saving_export($id)
    {
        $ledger_acc = LedgerAccount::where('id', $id)->get()->first();
        $reg_id = $ledger_acc->member->registration_no;
        return Excel::download(new LedgerFixedSavingExport($id), 'Member-Fixed-Saving-'. $reg_id .' '. $this->current_year->title.'.xlsx');
    }

    public function all_fixed_saving_export(Request $request)
    {
        // dd($request->all());
        $from = $request->member_id_from;
        $to = $request->member_id_to;
        $month_from = $request->month_from;
        $month_to = $request->month_to;

        return Excel::download(new AllLedgerFixedSavingExport($from, $to, $month_from, $month_to), 'All-Member-Fixed-Saving '. $this->current_year->title.'.xlsx');
    }

}






