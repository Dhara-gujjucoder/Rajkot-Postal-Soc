<?php

namespace App\Http\Controllers;

use App\Models\LedgerEntry;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;


class LedgerEntryController extends Controller
{
    /*check permission*/
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-ledger_entries|edit-ledger_entries|delete-ledger_entries|view-ledger_entries', ['only' => ['index','show']]);
        $this->middleware('permission:create-ledger_entries', ['only' => ['create','store']]);
        $this->middleware('permission:edit-ledger_entries', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-ledger_entries', ['only' => ['destroy']]);
        parent::__construct();
    }

    public function index(){
        $user = Auth::user();
        $data['ledger_entries'] = LedgerEntry::where('year_id',$this->current_year->id)->orderBy('id','ASC')->get();
        $data['page_title'] = __('Ledger Entries');
        return view('ledger_entries.index', $data);
    }

    public function create(){
        $data['page_title'] = __('Add New Ledger Entry');
        $data['ledgers'] = LedgerAccount::get();
        return view('ledger_entries.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ledger_ac_id' => 'required',
            'particular' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);
        $input = $request->all();
        $input['entry_type'] = 'DR';
        $input['opening_balance'] = '0';
        $input['year_id'] = $this->current_year->id;
        LedgerEntry::create($input);

        return redirect()->route('ledger_entries.index')
            ->withSuccess(__('Ledger Entry is created successfully.'));
    }

    public function edit(string $id)
    {
        $ledger_entry = LedgerEntry::findOrFail($id);
        return view('ledger_entries.edit', [
            'ledger_entry'=> $ledger_entry,
            'ledger_accounts' => LedgerAccount::get(),
            'page_title'=> __('Edit Ledger Entry')
        ]);
    }

    public function update(Request $request,LedgerEntry $ledger_entry): RedirectResponse
    {
        $request->validate(['ledger_ac_id' => 'required','amount' => 'required','date' => 'required','particular'=>'required|string|max:255,' . $ledger_entry->id]);
        $input = $request->only('ledger_ac_id','particular','amount','date');
        $input['entry_type'] = 'DR';
        $ledger_entry->update($input);
        return redirect()->route('ledger_entries.index')
                ->withSuccess(__('Ledger Entries is updated successfully.'));
    }

    public function destroy(LedgerEntry $ledger_entry): RedirectResponse
    {
        $ledger_entry->delete();
        return redirect()->route('ledger_entries.index')
                ->withSuccess(__('Ledger Entry deleted successfully.'));
    }

    


}

