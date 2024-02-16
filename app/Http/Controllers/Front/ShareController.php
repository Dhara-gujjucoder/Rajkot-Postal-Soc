<?php

namespace App\Http\Controllers\Front;

use App\Models\Member;
use App\Models\MemberShare;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show()
    {
        $data['page_title'] = __('All Share');
        $user = Auth::user(); 

        $data['active_share'] = MemberShare::where('member_id', $user->member->id)->where('status',1);
        $data['active_share_count'] = $data['active_share']->count();
        $data['share_amount'] = $data['active_share']->sum('share_amount');

        // dd($data['active_share']->count());

        $data['shares'] = MemberShare::where('member_id', $user->member->id)->get(); 

        return view('front.share.show', $data);
    }
   
}
