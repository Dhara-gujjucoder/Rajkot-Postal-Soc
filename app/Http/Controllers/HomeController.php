<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
        // dd($this);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        // dd($user->hasRole('Super Admin'));
        $user->syncPermissions();
        // dd(Hash::make('rajkotpostalsoc12#'));
        // dump($user->can('edit-user'));
        //   dd($user->can('view-user'));
        $data['page_title'] = __('Dashboard');
        $data['total_members'] = Member::count();
        $data['total_users'] = User::admin()->count();
        $data['page_title'] = __('Dashboard');
        $user = Auth::user();
        // dd($this->current_year);
        return view('dashboard', $data);
    }
}
