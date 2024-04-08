<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\BulkMaster;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Exports\ShareLedgerExport;
use Maatwebsite\Excel\Facades\Excel;

class ShareLedgerController extends Controller
{
    public function index(Request $request)
    {

        $data['page_title'] = __('Share Ledger');
        // $data['members'] = Member::withTrashed()->get();

        if ($request->ajax()) {
            $data = Member::active();
            return DataTables::of($data)
                ->editColumn('id', function ($row) {
                    return $row->name;
                })
                ->filterColumn('id', function ($query, $search) {
                    $query->whereHas('user', function ($q) use ($search) {
                        $q->where('name', 'Like', '%' . $search . '%');
                    });
                })
                ->addColumn('uid', function ($row) {
                    return $row->uid;
                })
                ->addColumn('opening_balance', function ($row) {
                    return $row->share_ledger_account->opening_balance;
                })
                ->addColumn('purchased_share', function ($row) {
                    return $row->purchased_share->sum('share_sum_share_amount');
                })
                ->addColumn('sold_share', function ($row) {
                    return $row->sold_share->sum('share_sum_share_amount');
                })
                ->addColumn('net_balance', function ($row) {
                    return $row->share_ledger_account->opening_balance + $row->purchased_share->sum('share_sum_share_amount') - $row->sold_share->sum('share_sum_share_amount');

                })
                ->rawColumns(['status'])
                ->make(true);;
        }
        return view('ledger_reports.share_ledger.index', $data);
    }

    public function all_share_ledger_export(Request $request)
    {
        return Excel::download(new ShareLedgerExport(), 'Ledger-Share '.$this->current_year->title.'.xlsx');
    }


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
