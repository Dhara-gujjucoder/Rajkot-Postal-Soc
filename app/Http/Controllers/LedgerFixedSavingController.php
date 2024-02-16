<?php

namespace App\Http\Controllers;

use App\Models\LedgerAccount;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LedgerFixedSavingExport;



class LedgerFixedSavingController extends Controller
{
    public function index(){
        $data['page_title'] = __('Fixed Saving');
        $data['fixed_savings'] = LedgerAccount::where('ledger_group_id',1)->where('year_id',$this->current_year->id)->get();
        // dd($data['fixed_savings']);
        return view('ledger_reports.fixed_saving.index',$data);
    }

    public function fixed_saving_export($id)
    {
        $ledger_acc = LedgerAccount::where('id', $id)->get()->first();
        $reg_id = $ledger_acc->member->registration_no;
    //    dd($ledger_acc->member->registration_no);
        return Excel::download(new LedgerFixedSavingExport($id), 'Member-Fixed-Saving-'.$reg_id.'.xlsx');
    }
}
