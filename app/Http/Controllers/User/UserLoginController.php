<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

class UserLoginController extends Controller
{
    // protected $redirectTo = '/user/home';
    /*check permission*/
    public function __construct()
    {
        // $this->middleware('auth:users');
        // $this->middleware('permission:create-account_type', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit-account_type', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-account_type', ['only' => ['destroy']]);
        parent::__construct();
    }

    public function index()
    {
        return view('front.home');
    }

    public function comingsoon()
    {
        // return view('front.home');
        return view('welcome');
    }

    public function showLoginForm()
    {
        return view('front.login');
    }

    public function profile()
    {
        $data['user'] = Auth::user();
        $data['member'] = $data['user']->member;
        // dd($data['user']->email);
        return view('front.profile',$data);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'registration_no'   => 'required',
        ]);
        $member = User::query()
            ->whereExists(function (Builder $q) use ($request) {
                $q->select(DB::raw(1))
                    ->from('members')
                    ->where('members.status',1)
                    ->whereColumn('members.user_id', 'users.id')
                    ->where('members.registration_no', $request->registration_no);
            })
            ->whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'user');
                }
            )
            ->first();

        if (empty($member)) {
            return redirect()->back()->withErrors(['registration_no' => 'Registration no. are wrong.']);
            // return redirect()->route('user.login')->withInput()->withErrors('registration_no', __('Registration No Are wrong.'));
        }

        if (Auth::guard('users')->loginUsingId($member->id)) {
            session()->put('registration_no', $member->member->registration_no);

            return redirect()->route('user.home');
        } else {
            return redirect()->route('user.login')->withInput()->with('error', __('Email-Address And Password Are Wrong.'));
        }


        // if (Auth::guard('users')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

        //     return redirect()->intended('/admin');
        // }
        // return back()->withInput($request->only('email', 'remember'));
    }
    public function logout(Request $request)
    {
        Auth::guard('users')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
}
