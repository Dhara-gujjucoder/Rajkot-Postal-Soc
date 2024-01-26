<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use App\Imports\AllShareImport;
use Yajra\DataTables\DataTables;
use App\Models\LedgerShareCapital;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;

class LedgerShareCapitalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-share_account|edit-share_account|delete-share_account|view-share_account', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-share_account', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-share_account', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-share_account', ['only' => ['destroy']]);
        $this->middleware('permission:view-share_account', ['only' => ['show', 'index']]);
        parent::__construct();
    }

    public function update(Request $request,$id)
    {
        $share = LedgerShareCapital::find($id);
        $share->status = 0;
        $share->save();

        $member = Member::withTrashed()->where('id', $share->member_id)->first();
        $query = LedgerShareCapital::where('member_id', $share->member_id)->where('status',1);
        $total_share = $query->count();
        // $share_total_price = $query->sum('share_amount');

        $member->total_share = $total_share;

        $balance = $member->share_total_price - $share->share_amount;
        if($balance >= 0){
            $member->share_total_price = $balance;
        }

        $member->save();

        return response()->json(['status' => true, 'total_share' => $total_share]);
        // ->withSuccess(__('Member is deleted successfully.'));
    }
    public function index(Request $request)
    {
        $data['page_title'] = __('All Shares');
        $data['shares'] = LedgerShareCapital::get();
        $data['active_share'] = LedgerShareCapital::where('status',1);
        $data['active_share_count'] = $data['active_share']->count();
        $data['share_amount'] = $data['active_share']->sum('share_amount');
        $data['members'] = Member::withTrashed()->orderBy('uid', 'ASC')->get();

        if ($request->ajax()) {
            $data = LedgerShareCapital::where('year_id',$this->current_year->id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->filterColumn('member_id', function($query, $search) {
                    $query->where('member_id',$search);
                })
                ->filterColumn('status', function($query, $search) {
                    $query->where('status',$search);
                })
                ->editColumn('member_id', function ($row) {
                    return $row->member->fullname;
                })
                ->editColumn('created_date', function ($row) {
                    return date('d-M-Y', strtotime($row->created_date));
                })
                ->editColumn('status', function ($row) {
                    // return $row->status == 1 ? __('Active') : __('Closed');
                    $html = '';
                    if ($row->status == 1){
                        // return 'abc';
                        $html .= '<button type="button" class="btn btn-outline-warning btn-sm" onclick="changeStatus('.$row->id.')">'. __('Close').'</button>';
                    }
                    else{
                        $html .= '<b>'. __('Closed').'</b>';
                    }
                    return $html;
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('ledger_sharecapital.index',$data);
    }


    public function all_share(){
        return view('ledger_sharecapital.all_share_import', [
            'page_title' => __('Import All Share')
        ]);
    }

    public function import_all_share(Request $request): RedirectResponse
    {
        $import = new AllShareImport;

        Excel::import($import, $request->file('all_share'));

        return redirect()->route('ledger_sharecapital.index')
            ->withSuccess(__('All Share Imported successfully.'));
    }

}
