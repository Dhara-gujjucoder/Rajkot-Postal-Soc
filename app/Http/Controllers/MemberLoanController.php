<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MemberLoanImport;

use App\Imports\MemberLoanGuarentor;
use Maatwebsite\Excel\Facades\Excel;

class MemberLoanController extends Controller
{
    //*************** E X C E L  S H E E T S [0] ***************

    public function loan(){
        return view('member_loan.import', [
            'page_title' => __('Member Loan')
        ]);
    }

    public function import_loan(Request $request){

        $import = new MemberLoanImport;   // main this
        // $import = new MemberLoanGuarentor;    // temporary   if MemberLoanGuarentor added time uncomment

        Excel::import($import, $request->file('excel_file'));
        // dd($import->not_insert);

        return redirect()->route('home')
            ->withSuccess(__('All Loan Imported successfully.'));
    }

}
