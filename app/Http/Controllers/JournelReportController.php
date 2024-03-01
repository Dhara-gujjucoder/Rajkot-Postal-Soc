<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\JournelReportExport;
use Maatwebsite\Excel\Facades\Excel;

class JournelReportController extends Controller
{
    public function index(Request $request)
    {

        $data['page_title'] = __('Journel Report');

        return view('ledger_reports.journel_report.index', $data);
    }

    public function journel_report_export(  )
    {
        return Excel::download(new JournelReportExport("08-2022"), 'journel_report_export .xlsx');
    }

}
