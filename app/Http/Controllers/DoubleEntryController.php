<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use App\Models\MetaDoubleEntry;
use App\Models\MasterDoubleEntry;
use App\Models\MemberFixedSaving;
use App\Traits\UpdateMemberShare;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class DoubleEntryController extends Controller
{
    use UpdateMemberShare;

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
        $data['double_entries'] = MasterDoubleEntry::where('year_id',$this->current_year->id)->orderBy('id','ASC')->get();
        $data['page_title'] = __('Double Entries');
        return view('double_entries.index', $data);
    }

    public function confirm(Request $request){

        $data['confirm'] = $request;
        // dd($data['confirm']);
        return response()->json(['success' => true, 'confirm' => view('double_entries.confirm',$data)->render()]);
    }

    public function create(){
        $data['count'] = (MasterDoubleEntry::latest()->first()->id ?? 0) + 1;
        $data['no'] = str_pad($data['count'], 4, 0, STR_PAD_LEFT);
        $data['page_title'] = __('Add Double Entry');
        // $data['ledger_accounts'] = LedgerAccount::whereIn('ledger_group_id',[2,4,5,10])->get();
        $data['ledger_accounts'] = LedgerAccount::get();

        return view('double_entries.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'ledger_ac_id' => 'required|array|min:2',
            'ledger_ac_id.*' => 'required',
            'particular' => 'required|array',
            'particular.*' => 'required',
            'amount' => 'required|array',
            'amount.*' => 'required'
        ]);

        $data['count'] = (MasterDoubleEntry::latest()->first()->id ?? 0) + 1;
        $data['no'] = str_pad($data['count'], 4, 0, STR_PAD_LEFT);

        $master_entry = new MasterDoubleEntry();
        $master_entry->entry_id = $data['no'];
        $master_entry->credit_amount = '0';
        $master_entry->debit_amount = '0';
        $master_entry->date = $request->date;
        $master_entry->description = $request->description;
        $master_entry->year_id = $this->current_year->id;
        $master_entry->save();

        if ($request->ledger_ac_id && $request->particular && $request->amount && $request->type) {
            $count = 0;
            $cr_total = 0;
            $dr_total = 0;
            $count = count($request->ledger_ac_id);

            for ($i = 0; $i<$count; $i++) {
                $ledger_account = LedgerAccount::find($request->ledger_ac_id[$i]);
                $member = Member::find($ledger_account->member_id);
                $month = date('m-Y',strtotime($request->date));
                // dd($member);

                $meta_entry = new MetaDoubleEntry();
                $meta_entry->mde_id  = $master_entry->id;
                $meta_entry->ledger_ac_id  = $request->ledger_ac_id[$i];
                $meta_entry->share = $request->share[$i] ?? '0';
                $meta_entry->particular  = $request->particular[$i] ?? '';
                $meta_entry->amount  = $request->amount[$i] ?? 0;
                $meta_entry->member_id = $member->id??0;
                $meta_entry->month = $month;
                $meta_entry->type = $request->type[$i]?? 0;
                $meta_entry->save();

                if($request->type[$i] == 'credit'){
                    $cr_total = $cr_total + (isset($request->amount[$i]) ? (int)$request->amount[$i] : 0);
                } elseif($request->type[$i] == 'debit') {
                    $dr_total = $dr_total + (isset($request->amount[$i]) ? (int)$request->amount[$i] : 0);
                }

                if($request->share[$i]){
                    $no_of_share = $member->total_share + $request->share[$i];
                    $this->update_member_share($member, $no_of_share);
                }

                if($ledger_account->ledger_group_id == 1){


                    // $fixed_saving = MemberFixedSaving::where('month',$month)->where('member_id',$ledger_account->member_id)->first();

                    // if($fixed_saving){
                    //     $fixed_saving->fixed_amount = $fixed_saving->fixed_amount + $meta_entry->amount;
                    //     $fixed_saving->save();

                    //     $member->fixed_saving_ledger_account->update(['current_balance' => $member->fixed_saving_ledger_account->current_balance + $meta_entry->amount]);
                    // }else{

                        $ledger = MemberFixedSaving::create([
                            'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
                            // 'member_id' => $member->id,
                            'member_id' => $ledger_account->member_id,
                            'month' => $month,
                            'fixed_amount' => $meta_entry->amount,
                            'year_id' => 1,
                            'status' => 1,
                            'is_double_entry' => 1
                            // 'created_date' => $end_date->format('Y-m-d'),
                        ]);
                        $member_fixed_saving = $member->fixed_saving_ledger_account->opening_balance + $member->fixed_saving()->sum('fixed_amount');

                        $member->fixed_saving_ledger_account->update(['current_balance' => $member_fixed_saving]);

                        // $member->fixed_saving_ledger_account->update(['current_balance' => $member->fixed_saving_ledger_account->current_balance + $meta_entry->amount]);

                    // }
                    // dd($ledger);
                }
            }

            // dd($cr_total.'credit----->debit'.$dr_total);
            $master_entry->credit_amount = $cr_total;
            $master_entry->debit_amount = $dr_total;
            $master_entry->save();
        }

        // dd($request->all());
        return redirect()->route('double_entries.index')
            ->withSuccess(__('Double Entry is created successfully.'));
    }

    public function show(string $id)
    {
        $master_entry = MasterDoubleEntry::findOrFail($id);

        return view('double_entries.show', [
            'master_entry' => $master_entry,
            // 'ledger_accounts' => LedgerAccount::whereIn('ledger_group_id',[2,4,5])->get(),
            'ledger_accounts' => LedgerAccount::get(),
            'page_title'=> __('Double Entry Details')
        ]);
    }

    // public function edit(string $id)
    // {
    //     $master_entry = MasterDoubleEntry::findOrFail($id);
    //     return view('double_entries.edit', [
    //         'master_entry' => $master_entry,
    //         'ledger_accounts' => LedgerAccount::whereIn('ledger_group_id',[2,4,5])->get(),
    //         'page_title'=> __('Edit Double Entry')
    //     ]);
    // }

    // public function update(Request $request,MasterDoubleEntry $double_entry): RedirectResponse
    // {
    //     // $request->validate([
    //     //     'description' => 'required',
    //     //     'ledger_ac_id' => 'required|array|min:2',
    //     //     'ledger_ac_id.*' => 'required',
    //     //     'particular' => 'required|array',
    //     //     'particular.*' => 'required',
    //     //     'amount' => 'required|array',
    //     //     'amount.*' => 'required'
    //     // ]);

    //     // $input = $request->only('date','description');
    //     // $master_entry->update($input);

    //     // dd($double_entry);
    //     $double_entry->date = $request->date;
    //     $double_entry->description = $request->description;
    //     $double_entry->save();

    //     $meta_entry = MetaDoubleEntry::where('mde_id',$double_entry->id)->delete();

    //     if ($request->ledger_ac_id && $request->particular && $request->amount && $request->type) {
    //         $count = 0;
    //         $cr_total = 0;
    //         $dr_total = 0;
    //         $count = count($request->ledger_ac_id);

    //         for ($i = 0; $i<$count; $i++) {
    //             $meta_entry = new MetaDoubleEntry();
    //             $meta_entry->mde_id  = $double_entry->id;
    //             $meta_entry->ledger_ac_id  = $request->ledger_ac_id[$i];
    //             $meta_entry->particular  = $request->particular[$i] ?? '';
    //             $meta_entry->amount  = $request->amount[$i] ?? 0;
    //             $meta_entry->type  = $request->type[$i]?? 0;
    //             $meta_entry->save();

    //             if($request->type[$i] == 'credit'){
    //                 $cr_total = $cr_total + (isset($request->amount[$i]) ? (int)$request->amount[$i] : 0);
    //             } elseif($request->type[$i] == 'debit') {
    //                 $dr_total = $dr_total + (isset($request->amount[$i]) ? (int)$request->amount[$i] : 0);
    //             }
    //         }
    //         // dd($cr_total.'credit----->debit'.$dr_total);
    //         $double_entry->credit_amount = $cr_total;
    //         $double_entry->debit_amount = $dr_total;
    //         $double_entry->save();
    //     }

    //     return redirect()->route('double_entries.index')
    //     ->withSuccess(__('Ledger Entries is updated successfully.'));
    // }

    public function destroy(MasterDoubleEntry $double_entry): RedirectResponse
    {
        MetaDoubleEntry::where('mde_id',$double_entry->id)->delete();
        $double_entry->delete();

        return redirect()->route('double_entries.index')
                ->withSuccess(__('Double Entry deleted successfully.'));
    }
}

