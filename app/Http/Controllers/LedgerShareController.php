<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberShare;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LedgerShareController extends Controller
{
    public function index(){
        $data['page_title'] = __('Share Ledger');
        // $data['share'] = MemberShare::where()->get();
        $data['members'] = Member::withTrashed()->get();
        return view('ledger_reports.share_ledger.index',$data);
    }





    // public function fixed_saving_export($id)
    // {
    //     $ledger_acc = LedgerAccount::where('id', $id)->get()->first();
    //     $reg_id = $ledger_acc->member->registration_no;
    //     return Excel::download(new LedgerFixedSavingExport($id), 'Member-Fixed-Saving-'.$reg_id.'.xlsx');
    // }

    // public function all_fixed_saving_export(Request $request)
    // {
    //     // dd($request->all());
    //     $from = $request->member_id_from;
    //     $to = $request->member_id_to;
    //     $month_from = $request->month_from;
    //     $month_to = $request->month_to;

    //     return Excel::download(new AllLedgerFixedSavingExport($from, $to, $month_from, $month_to), 'All-Member-Fixed-Saving.xlsx');
    // }

}






