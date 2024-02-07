<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\FixedSavingExport;
use Maatwebsite\Excel\Facades\Excel;

class TempReportController extends Controller
{
    public function create()
    {
        $data['page_title'] = __('Temp-Reports');
        return view('tempreport.create',$data);
    }

     // ***** EXPORT INTO EXCEL FILE*****
    public function fixed_saving_export()
    {
        return Excel::download(new FixedSavingExport, 'Member Fixed Saving.xlsx');
    }

}
