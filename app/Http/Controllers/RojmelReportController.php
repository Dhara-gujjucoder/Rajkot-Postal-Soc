<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RojmelReportExport;

class RojmelReportController extends Controller
{
    public function index()
    {
        $data['page_title'] = __('Rojmel Report');

        return view('ledger_reports.rojmel_report.index', $data);
    }


    public function rojmel_report_export(Request $request)
    {
        // dd($request->all());

        $month_from = $request->month_from;
        $month_to = $request->month_to;
        // dd($month_from,$month_to);
        $excel = Excel::download(new RojmelReportExport($month_from, $month_to), 'Rojmel-Report '. $this->current_year->title.'.xlsx');
        // dd($excel);
        return $excel;
    }
}
