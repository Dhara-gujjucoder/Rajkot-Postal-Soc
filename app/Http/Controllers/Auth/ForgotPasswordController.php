<?php

namespace App\Http\Controllers\Auth;

use DB;
use Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showForgetPasswordForm()
    {
        return view('auth.forgetPassword');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'reg_no' => 'required|exists:members,registration_no',
        ]);

        $token = Str::random(64);
        $member = Member::where('registration_no',$request->reg_no)->first();
        DB::table('password_resets')->insert([
            'email' => $member->user->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $sitename = Setting::pluck('title')->first();
        $member_name = $member->user->name;

        Mail::send('email.forgetPassword', ['token' => $token,'member_name' => $member_name], function ($message) use ($member,$sitename) {
            $message->to($member->user->email);
            // dd($name.'');
            $message->subject('Reset Password - ' .$sitename);
        });

        return back()->with('message', 'Please check email for reset password.');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.forgetPasswordLink', ['token' => $token]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'reg_no' => 'required|exists:members,registration_no',
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required'
        ]);
        $member = Member::where('registration_no',$request->reg_no)->first();
        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $member->user->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $member->user->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $member->user->email])->delete();
        return redirect()->route('user.login')->with('message', 'Your password has been changed!');
    }
}
