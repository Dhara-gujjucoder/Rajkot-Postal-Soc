<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\LoanMail;
use App\Models\Member;
use App\Models\LoanInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\LoanCalculationMatrix;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    public function calculator()
    {
        $data['page_title'] = __('Loan Calculator');
        $data['amount'] = LoanCalculationMatrix::pluck('amount')->all();
        // dd(implode(',',$data['amount']));
        $data['user'] =  Auth::user();
        $data['member'] = $data['user']->member;
        $data['loan'] = LoanCalculationMatrix::get()->all();
        $data['loan_interest'] = LoanInterest::where('is_active', 1)->pluck('loan_interest')->first();

        return view('front.loan.calculator', $data);
    }

    public function LoanMailSend(Request $request)
    {
        $data = $request->all();
        $data['user'] =  Auth::user();
        $data['member_id'] = $data['user']->member->uid;

        // dd($data['member_id']);

        $super_admin = User::role('Super Admin','web')->get()->first();
        $email_id = $super_admin->notification_email ?? 'noreply@gmail.com';
        Mail::to($email_id)->send(new LoanMail($data));

        return redirect()->back()->withSuccess(__('Thank you for your request of loan, we will contact you soon.'));
    }
    public function apply()
    {
        $data['page_title'] = __('Loan Apply');
        $data['user'] =  Auth::user();
        $data['member'] = $data['user']->member;
        return view('front.loan.apply', $data);
    }





}
