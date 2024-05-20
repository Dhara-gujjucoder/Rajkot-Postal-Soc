<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        return view('front.profile', $data);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'registration_no'   => 'required',
            'password'          => 'required',
        ]);

        // dd($request->all());
        $member = User::query()
            ->whereExists(function (Builder $q) use ($request) {
                $q->select(DB::raw(1))
                    ->from('members')
                    ->where('members.status', 1)
                    // ->whereIn('members.registration_no',['01110111','00007777'])
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
            return redirect()->back()->withInput()->withErrors(['registration_no' => 'Registration no. are wrong.']);
            // return redirect()->route('user.login')->withInput()->withErrors('registration_no', __('Registration No Are wrong.'));
        }
        // dd(Auth::guard('users')->attempt(['email' => $member->email, 'password' => $request->password]));

        if (Auth::guard('users')->attempt(['email' => $member->email, 'password' => $request->password])) {
            session()->put('registration_no', $member->member->registration_no);

            return redirect()->route('user.home');
        } else {
            return redirect()->route('user.login')->withInput()->withErrors(['password' => __('Password Are Wrong.')]);
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


    public function change_password()
    {
        return view('front.password.change');
    }

    public function update_password(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $user = $user->fresh();
        $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'password'  => 'bail|required|string|min:4|confirmed',
        ]);
        $input['password'] = $request->password;
        $user->update($input);

        return redirect()->back()->withSuccess(__('Password update successfully.'));
    }
}
