<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberShare;
use App\Models\ShareAmount;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MemberShareDetail;
use App\Imports\MemberShareImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;

class MemberShareController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-member_share|edit-member_share|delete-member_share|view-member_share', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-member_share', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-member_share', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-member_share', ['only' => ['destroy']]);
        $this->middleware('permission:view-member_share', ['only' => ['show', 'index']]);
        parent::__construct();
    }

    public function create()
    {
        // $shares = MemberShare::get()->all();
        // foreach ($shares as $key => $value) {

        //     $share_detail_entry = new MemberShareDetail();
        //     $share_detail_entry->member_share_id = $value->id;
        //     $share_detail_entry->member_id = $value->member_id;
        //     $share_detail_entry->year_id = currentYear()->id;
        //     $share_detail_entry->is_purchase = 1;
        //     $share_detail_entry->save();

        //     if($value->sold_on){

        //         $share_detail_entry = new MemberShareDetail();
        //         $share_detail_entry->member_share_id = $value->id;
        //         $share_detail_entry->member_id = $value->member_id;
        //         $share_detail_entry->year_id = currentYear()->id;
        //         $share_detail_entry->is_sold = 1;
        //         $share_detail_entry->save();

        //     }
        // }

        return view('member_share.create', [
            'page_title' => __('Add New Share'),
            'members' => Member::get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'share' => 'required',
            'type' => 'required',
            'form_type' => 'required'
        ]);

        return redirect()->route('member_share.index')
            ->withSuccess(__('Share added successfully.'));
    }

    public function update(Request $request,$id)
    {
        $share = MemberShare::find($id);
        $share->status = 0;
        $share->save();

        $share_detail_entry = new MemberShareDetail();
        $share_detail_entry->member_share_id = $share->id;
        $share_detail_entry->member_id = $share->member_id;
        $share_detail_entry->year_id = currentYear()->id;
        $share_detail_entry->is_sold = 1;
        $share_detail_entry->save();

        $member = Member::withTrashed()->where('id', $share->member_id)->first();
        $query = MemberShare::where('member_id', $share->member_id)->where('status',1);
        $total_share = $query->count();
        // $share_total_price = $query->sum('share_amount');

        $member->total_share = $total_share;

        // $balance = $member->share_total_price - $share->share_amount;
        // if($balance >= 0){
        //     $member->share_total_price = $balance;
        // }

        $member->share_ledger_account->update(
            ['current_balance' =>  ($total_share * current_share_amount()->share_amount)]);
        $member->save();


        return response()->json(['status' => true, 'total_share' => $total_share]);
        // ->withSuccess(__('Member is deleted successfully.'));
    }

    public function index(Request $request)
    {
        // dd(currentYear()->id);
        $data['page_title'] = __('Member Shares');
        $data['shares'] = MemberShare::get();
        $data['active_share'] = MemberShare::where('status',1);
        $data['active_share_count'] = $data['active_share']->count();
        $data['share_amount'] = $data['active_share']->sum('share_amount');
        $data['members'] = Member::withTrashed()->orderBy('uid', 'ASC')->get();

        if ($request->ajax()) {
            $data = MemberShare::where('year_id',$this->current_year->id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->filterColumn('member_id', function($query, $search) {
                    $query->where('member_id',$search);
                })
                ->filterColumn('status', function($query, $search) {
                    $query->where('status',$search);
                })

                ->addColumn('uid', function ($row) {
                    return $row->member->uid;
                })
                ->editColumn('member_id', function ($row) {
                    return $row->member->fullname;
                })
                ->editColumn('purchase_on', function ($row) {
                    return date('d-M-Y', strtotime($row->purchase_on));
                })
                ->editColumn('status', function ($row) {
                    // return $row->status == 1 ? __('Active') : __('Closed');
                    $html = '';
                    if ($row->status == 1){
                        // return 'abc';
                        $html .= '<button type="button" class="btn btn-outline-warning btn-sm" onclick="changeStatus('.$row->id.')">'. __('Sell').'</button>';
                    }
                    else{
                        $html .= '<b>'. __('Sold').'</b>';
                    }
                    return $html;
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('member_share.index', $data);
    }

    //*************** E X C E L  S H E E T S [1]***************

    public function member_share(){
        return view('member_share.import', [
            'page_title' => __('Import Member Share')
        ]);
    }

    public function import_member_share(Request $request): RedirectResponse
    {
        $import = new MemberShareImport;

        Excel::import($import, $request->file('member_share'));
        dd($import->not_insert);


        return redirect()->route('member_share.index')
            ->withSuccess(__('Member Share Imported successfully.'));
    }
}
