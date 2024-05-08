<?php

namespace App\Http\Controllers;

use App\Exports\TarijReport;
use Illuminate\Http\Request;
use App\Exports\TarijReportExport;
use Maatwebsite\Excel\Facades\Excel;

class TarijReportController extends Controller
{
    public function index()
    {
        $data['page_title'] = __('Tarij Report');

        return view('ledger_reports.tarij_report.index', $data);
    }


    public function tarij_report_export(Request $request)
    {
        // dd($request->all());

        $month_from = $request->month_from;
        $month_to = $request->month_to;
        // dd($month_from,$month_to);
        $excel = Excel::download(new TarijReportExport($month_from, $month_to), 'Tarij-Report '. $this->current_year->title.'.xlsx');
        // dd($excel);
        return $excel;
    }
}
