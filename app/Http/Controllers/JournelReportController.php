<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\JournelReport;
use App\Exports\JournelReportExport;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class JournelReportController extends Controller
{
    public function index(Request $request)
    {
        // dd(bcrypt('1234'));
        $data['page_title'] = __('Journel Report');

        return view('ledger_reports.journel_report.index', $data);
    }

    public function journel_report_export(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'month_from' => 'required_without:month_to',
        //     'month_to' =>  'required_without:month_from',
        // ]);



        $month_from = $request->month_from;
        $month_to = $request->month_to;
        $result = [];

        // if ($month_from == null || $request->month_to == null) {
        //     return redirect()->back()->withErrors(['error', 'dfdf']);
        // }

        // dd($month_to);
        if ($month_from != null && $month_to != null) {
            if ($month_from > $request->month_to) {
                return redirect()->back()->withErrors(['error', 'dfdf']);
            } else {
                $months = getMonthsOfYear(currentYear()->id);
                foreach ($months as $key => $value) {
                    // dd($month_from && $month_to);

                    if ($key <= $month_to && $key >= $month_from) {
                        $result[] = $value;
                    }
                }

                $months = collect($result)->pluck('value');
                $result  = $months->toArray();
            }

        } elseif ($month_from != null) {
            $months = [getMonthsOfYear(currentYear()->id)[$month_from]['value']];
            $result = $months;

        } elseif ($month_to != null) {
            $months = [getMonthsOfYear(currentYear()->id)[$month_to]['value']];
            $result = $months;
        }else{
            // dd('dd');
            $months = getMonthsOfYear(currentYear()->id);
            $months = collect($months)->pluck('value');
            // dd( $months);
            $result  = $months->toArray();
        }
        //  dd($months );

        // if ($month_from && $month_to) {

        //     // } else {
        //     // $result[] = $value;
        //     // }
        // }

        // dd($result);


        return Excel::download(new JournelReport($result), 'journel_report_export '. $this->current_year->title.'.xlsx');
    }
}
