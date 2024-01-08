<?php

namespace App\Http\Controllers;

use App\Models\LoanInterest;
use App\Models\MonthlySaving;
use App\Models\ShareAmount;
use Illuminate\Http\Request;

class LoanSettingController extends Controller
{
    /*check permission*/
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:loaninterest-setting|loaninterest-setting');
        // $this->middleware('permission:create-account_type', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit-account_type', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-account_type', ['only' => ['destroy']]);
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['loan_interest'] = LoanInterest::get();
        $data['share_amount'] = ShareAmount::get();
        $data['monthly_saving'] = MonthlySaving::get();
        $data['page_title'] = __('Loan Interest');
        // dd($data['share_amount']);
        return view('setting.loaninterest.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['loan_interest'] = LoanInterest::get();
        $data['page_title'] = __('Add New Loan Interest');
        // dd($data['share_amount']);
        return view('setting.loaninterest.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['loan_interest' => 'required']);

        $last = LoanInterest::latest()->first();
        if ($last) {
            LoanInterest::query()->update(['is_active' => false]);
            $last->update(['end_date' => date('Y-m-d H:i:s')]);
        }
        LoanInterest::create(['loan_interest' => $request->loan_interest, 'is_active' => 1, 'start_date' => date('Y-m-d H:i:s')]);

        return redirect()->route('loaninterest.index')
            ->withSuccess(__('Setting is updated successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['loan_interest'] = LoanInterest::where('id', $id)->first();
        $data['page_title'] = __('Edit Loan Interest');
        // dd($data['share_amount']);
        return view('setting.loaninterest.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $loan_interest = LoanInterest::where('id', $id)->first();
        if ($request->is_active == 1) {
            LoanInterest::query()->where('is_active', 1)->update(['is_active' => false, 'end_date' => date('Y-m-d H:i:s')]);
            $loan_interest->update(['is_active' => true]);
        }
        $loan_interest->update(['loan_interest' => $request->loan_interest]);
        return redirect()->route('loaninterest.index')
            ->withSuccess(__('Setting is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
