<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
        // dd($this);
    }

    /**
     * Display a listing of the resource.
     */
    public function apply()
    {
        $data['page_title'] = __('Loan Apply');
        return view('front.loan.apply', $data);
    }

    /**
     * Display a listing of the resource.
     */
    public function calculator()
    {
        $data['page_title'] = __('Loan Calculator');
        return view('front.loan.calculator', $data);
    }
}
