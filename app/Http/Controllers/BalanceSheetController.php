<?php

namespace App\Http\Controllers;

use App\Models\BalanceSheet;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use Illuminate\Support\Facades\Auth;

class BalanceSheetController extends Controller
{
    public function create()
    {
        $data['page_title'] = __('Create Balance SHEET');
        $data['ledger_ac'] = LedgerAccount::where('ledger_group_id', 7)->where('status', 1)->get();

        return view('ledger_reports.balance_sheet.create', $data);
    }

    public function edit()
    {
        $data['page_title'] = __('Edit Balance SHEET');
        $data['balance_sheet'] = BalanceSheet::where('year_id', $this->current_year->id)->get();
        return view('ledger_reports.balance_sheet.edit', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data['ledger_ac'] = LedgerAccount::where('ledger_group_id', 7)->where('status', 1)->get();

        // $rules = [];
        // foreach ($data['ledger_ac'] as $key => $value) {
        //     $rules['acc_' . $value->id] = 'required|numeric';
        // }
        // $request->validate(
        //     $rules,[
        //         'required' => 'This field is required.',
        //         'numeric' => 'This field must be a number.'
        //     ]
        // );

        foreach ($data['ledger_ac'] as $key => $value) {
            $balanceSheet = new BalanceSheet();
            $balanceSheet->ledger_ac_id = $value->id;
            $balanceSheet->ledger_ac_name = $value->account_name;
            $balanceSheet->balance = $request['acc_' . $value->id] ?? 0;
            $balanceSheet->year_id = $this->current_year->id;

            $balanceSheet->save();
        }

        return redirect()->route('balance_sheet.create')
            ->withSuccess(__('Balance sheet added successfully.'));
    }

    public function index()
    {
        // dump(Auth::user()->can('edit-balance_sheet'));
          /*******/
        // $data['ledger_ac'] = LedgerAccount::where('ledger_group_id', 7)->where('status', 1)->get();
        // // dd(   $data['ledger_ac'] );
        // foreach ($data['ledger_ac'] as $key => $value) {
        //             $balanceSheet = new BalanceSheet();
        //             $balanceSheet->ledger_ac_id = $value->id;
        //             $balanceSheet->ledger_ac_name = $value->account_name;
        //             $balanceSheet->balance = 0;
        //             $balanceSheet->year_id = $this->current_year->id;
        //             $balanceSheet->save();
        //         }

                /*******/
        $data['page_title'] = __('Balance SHEET');
        $data['balance_sheet'] = BalanceSheet::where('year_id', $this->current_year->id)->get();
        return view('ledger_reports.balance_sheet.index', $data);
    }

    public function update(Request $request,$id)
    {
        $data['ledger_ac'] = BalanceSheet::where('year_id', $this->current_year->id)->get();

        // $rules = [];
        // foreach ($data['ledger_ac'] as $key => $value) {
        //     $rules['acc_' . $value->id] = 'required|numeric';
        // }
        // $request->validate(
        //     $rules,[
        //         'required' => 'This field is required.',
        //         'numeric' => 'This field must be a number.'
        //     ]
        // );


        foreach ($data['ledger_ac'] as $key => $value) {
            $balanceSheet = $value;
            // $balanceSheet->ledger_ac_name = $value->account_name;
            $balanceSheet->balance = $request['acc_'.$value->id] ?? 0;
            $balanceSheet->year_id = $this->current_year->id;
            $balanceSheet->save();
        }

        return redirect()->route('balance_sheet.index')
            ->withSuccess(__('Balance Sheet updated successfully.'));
    }
}
