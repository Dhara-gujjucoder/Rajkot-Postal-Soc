<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AccountTypeController extends Controller
{
    /*check permission*/
    public function __construct()
    {
      
        $this->middleware('auth');
        $this->middleware('permission:create-account_type|edit-account_type|delete-account_type|view-account_type', ['only' => ['index','show']]);
        $this->middleware('permission:create-account_type', ['only' => ['create','store']]);
        $this->middleware('permission:edit-account_type', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-account_type', ['only' => ['destroy']]);
        parent::__construct();
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['account_types'] = AccountType::orderBy('id','DESC')->paginate(10);
        $data['page_title']= __('View Account Types');
        return view('account_type.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account_type.create', [
            'page_title'=> __('Add New Account Type')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:255'
        ]);
        AccountType::create(['type_name' => $request->type_name]);
        return redirect()->route('account_type.index')
                ->withSuccess(__('New Account Type is added successfully.'));
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
        $account_type = AccountType::findOrFail($id);
        return view('account_type.edit', [
            'account_type'=> $account_type,
            'page_title'=> __('Edit Account Type')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,AccountType $account_type): RedirectResponse
    {
        $request->validate(['type_name'=>'required|string|max:255']);
        $input = $request->only('type_name');
        $account_type->update($input);
        return redirect()->route('account_type.index')
                ->withSuccess(__('Account Type is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountType $account_type): RedirectResponse
    {
        $account_type->delete();
        return redirect()->route('account_type.index')
                ->withSuccess(__('Account Type is deleted successfully.'));
    }
}
