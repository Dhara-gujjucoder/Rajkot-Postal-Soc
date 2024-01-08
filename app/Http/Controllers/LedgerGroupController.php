<?php

namespace App\Http\Controllers;

use App\Models\LedgerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LedgerGroupController extends Controller
{
    /*check permission*/
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware('permission:create-ledger_group|edit-ledger_group|delete-ledger_group|view-ledger_group', ['only' => ['index','show']]);
        $this->middleware('permission:create-ledger_group', ['only' => ['create','store']]);
        $this->middleware('permission:edit-ledger_group', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-ledger_group', ['only' => ['destroy']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['ledger_groups'] = LedgerGroup::orderBy('id','ASC')->paginate(10);
        $data['page_title']= __('View Ledger Group Types');
        return view('ledger_group.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ledger_group.create', [
            'page_title'=> __('Add New Ledger Group')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|unique:ledger_group,type_name|max:255'
        ]);
        LedgerGroup::create(['type_name' => $request->type_name]);
        return redirect()->route('ledger_group.index')
                ->withSuccess(__('New Ledger Group is added successfully.'));
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
        $ledger_group = LedgerGroup::findOrFail($id);
        return view('ledger_group.edit', [
            'ledger_group'=> $ledger_group,
            'page_title'=> __('Edit Ledger Group')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,LedgerGroup $ledger_group): RedirectResponse
    {
        $request->validate(['type_name'=>'required|string|max:255|unique:ledger_group,type_name,' . $ledger_group->id]);
        $input = $request->only('type_name');
        $ledger_group->update($input);
        return redirect()->route('ledger_group.index')
                ->withSuccess(__('Ledger Group is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LedgerGroup $ledger_group): RedirectResponse
    {
        $ledger_group->delete();
        return redirect()->route('ledger_group.index')
                ->withSuccess(__('Ledger Group is deleted successfully.'));
    }
}
