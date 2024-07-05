<?php

namespace App\Http\Controllers\Front;

use App\Models\Member;
use App\Models\MemberShare;
use Illuminate\Http\Request;
use App\Models\MemberFixedSaving;
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

        // $data['shares'] = MemberShare::where('member_id', $user->member->id)->get();   //share listing 'Active'

        return view('front.share.show', $data);
    }

    public function saving_show()
    {
        $data['page_title'] = __('All Saving');
        $user = Auth::user();

        // dump($user->member->id);

        $data['saving_amount'] = MemberFixedSaving::withoutGlobalScope('year')->where('member_id',$user->member->id)->sum('fixed_amount');
        $data['savings'] = MemberFixedSaving::withoutGlobalScope('year')->where('member_id',$user->member->id)->orderBy('id','DESC')->latest()->take(12)->get();

        return view('front.saving.show', $data);
    }

}
