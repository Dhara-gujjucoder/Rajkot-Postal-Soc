<?php

namespace App\Http\Controllers;

use App\Models\LoanCalculationMatrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\DataTables;

class LoanCalculationMatrixController extends Controller
{
    /*check permission*/
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware('permission:create-loan_matrix|edit-loan_matrix|delete-loan_matrix|view-loan_matrix', ['only' => ['index','show']]);
        $this->middleware('permission:create-loan_matrix', ['only' => ['create','store']]);
        $this->middleware('permission:edit-loan_matrix', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-loan_matrix', ['only' => ['destroy']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['loan_matrixs'] = LoanCalculationMatrix::get();
        $data['page_title'] = __('Loan Calculation Matrix');
        return view('loan_matrix.index', $data);
    }


    public function create()
    {
        return view('loan_matrix.create', [
            'page_title'=> __('Add New Loan')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'minimum_emi' => 'required',
            'required_share' =>'required'
        ]);
        LoanCalculationMatrix::create([
            'amount' => $request->amount,
            'minimum_emi' => $request->minimum_emi,
            'required_share' => $request->required_share,
            'status' => $request->status
        ]);
        return redirect()->route('loan_matrix.index')
                ->withSuccess(__('New Loan is added successfully.'));
    }


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $loan = LoanCalculationMatrix::findOrFail($id);
        return view('loan_matrix.edit', [
            'loan'=> $loan,
            'page_title'=> __('Edit Loan')
        ]);
    }


    public function update(Request $request,LoanCalculationMatrix $loan_matrix): RedirectResponse
    {
        $request->validate(['amount' => 'required','minimum_emi'=>'required', 'required_share' =>'required']);
        $input = $request->only('amount','minimum_emi','required_share','status');
        $loan_matrix->update($input);

        return redirect()->route('loan_matrix.index')
                ->withSuccess(__('Loan is updated successfully.'));
    }


    public function destroy(LoanCalculationMatrix $loan_matrix): RedirectResponse
    {
        $loan_matrix->delete();
        return redirect()->route('loan_matrix.index')
                ->withSuccess(__('Loan is deleted successfully.'));
    }
}
