<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\FixedSavingExport;
use App\Exports\MemberShareExport;
use Maatwebsite\Excel\Facades\Excel;

class TempReportController extends Controller
{
    // ***** EXPORT INTO EXCEL FILE [0]*****
    public function create()
    {
        $data['page_title'] = __('Temp-Reports');
        return view('tempreport.create',$data);
    }

    public function fixed_saving_export()
    {
        return Excel::download(new FixedSavingExport, 'Member-Fixed-Saving.xlsx');
    }

    // ***** EXPORT INTO EXCEL FILE [1]*****
    public function member_share()
    {
        $data['page_title'] = __('Member Share');
        return view('tempreport.member_share',$data);
    }

    public function member_share_export()
    {
        return Excel::download(new MemberShareExport, 'Member-Share.xlsx');
    }

}
