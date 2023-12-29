<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LedgerAccountController extends Controller
{
    /*check permission*/
    public function __construct()
    {
      
        $this->middleware('auth');
        $this->middleware('permission:create-ledger_account|edit-ledger_account|delete-ledger_account|view-ledger_account', ['only' => ['index','show']]);
        $this->middleware('permission:create-ledger_account', ['only' => ['create','store']]);
        $this->middleware('permission:edit-ledger_account', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-ledger_account', ['only' => ['destroy']]);
        parent::__construct();
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $data['ledger_accounts'] = LedgerAccount::orderBy('id','DESC')->paginate(10);
        $data['page_title']= __('View Ledger Accounts');
        return view('ledger_account.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ledger_account.create', [
            'page_title'=> __('Add New Ledger Account'),
            'account_types' => AccountType::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'account_name' => 'required|string|max:255',
        ]);
        LedgerAccount::create(['account_name' => $request->account_name,'account_type_id' => $request->account_type_id,'created_by' => Auth::user()->id]);
        return redirect()->route('ledger_account.index')
                ->withSuccess(__('New Ledger Account is added successfully.'));
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
        $ledger_account = LedgerAccount::findOrFail($id);
        return view('ledger_account.edit', [
            'ledger_account'=> $ledger_account,
            'account_types' => AccountType::get(),
            'page_title'=> __('Edit Ledger Account')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,LedgerAccount $ledger_account): RedirectResponse
    {
        $request->validate(['account_name'=>'required|string|max:255']);
        $input = $request->only('account_name','account_type_id');
        $ledger_account->update($input);
        return redirect()->route('ledger_account.index')
                ->withSuccess(__('Ledger Account is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LedgerAccount $ledger_account): RedirectResponse
    {
        $ledger_account->delete();
        return redirect()->route('ledger_account.index')
                ->withSuccess(__('Ledger Account is deleted successfully.'));
    }
}

