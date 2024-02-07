<?php

namespace App\Http\Controllers;

use App\Models\DoubleEntry;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;


class DoubleEntryController extends Controller
{
    /*check permission*/
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-double_entries|edit-double_entries|delete-double_entries|view-double_entries', ['only' => ['index','show']]);
        $this->middleware('permission:create-double_entries', ['only' => ['create','store']]);
        $this->middleware('permission:edit-double_entries', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-double_entries', ['only' => ['destroy']]);
        parent::__construct();
    }

    public function index(){
        $user = Auth::user();
        $data['double_entries'] = DoubleEntry::where('year_id',$this->current_year->id)->orderBy('id','ASC')->get();
        $data['page_title'] = __('Double Entries');
        return view('double_entries.index', $data);
    }

    public function create(){
        $data['page_title'] = __('Add Double Entry');
        $data['ledger_accounts'] = LedgerAccount::where('is_member_account',0)->get();
        return view('double_entries.create', $data);
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
        DoubleEntry::create($input);

        return redirect()->route('double_entries.index')
            ->withSuccess(__('Double Entry is created successfully.'));
    }

    public function edit(string $id)
    {
        $ledger_entry = DoubleEntry::findOrFail($id);
        return view('double_entries.edit', [
            'ledger_entry'=> $ledger_entry,
            'ledger_accounts' => LedgerAccount::get(),
            'page_title'=> __('Edit Double Entry')
        ]);
    }

    public function update(Request $request,DoubleEntry $ledger_entry): RedirectResponse
    {
        $request->validate(['ledger_ac_id' => 'required','amount' => 'required','date' => 'required','particular'=>'required|string|max:255,' . $ledger_entry->id]);
        $input = $request->only('ledger_ac_id','particular','amount','date');
        $input['entry_type'] = 'DR';
        $ledger_entry->update($input);
        return redirect()->route('double_entries.index')
                ->withSuccess(__('Ledger Entries is updated successfully.'));
    }

    public function destroy(DoubleEntry $ledger_entry): RedirectResponse
    {
        $ledger_entry->delete();
        return redirect()->route('double_entries.index')
                ->withSuccess(__('Double Entry deleted successfully.'));
    }

    


}

