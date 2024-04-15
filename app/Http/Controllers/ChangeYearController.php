<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\LedgerGroup;
use App\Models\BalanceSheet;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use Illuminate\Database\Eloquent\Builder;

class ChangeYearController extends Controller
{
    public function change_year($year_id)
    {
        $members = Member::where('status', 1)->get();
        // dd($members);

        //************************** for all members ledger account creation **************************
        foreach ($members as $member) {

            for ($i = 1; $i <= 3; $i++) {
                // dd($year_id);
                $group = LedgerGroup::where('id', $i)->first();
                $old_ledger_entry = LedgerAccount::where('member_id', $member->id)->where('ledger_group_id', $i)->first();

                $ledger_entry = LedgerAccount::withoutGlobalScope('year')->where('year_id', $year_id)->where('member_id', $member->id)->where('ledger_group_id', $i)->first();

                    if (!$ledger_entry) {
                        // dd($year_id);
                        $ledger_entry = new LedgerAccount();
                        $ledger_entry->ledger_group_id = $group->id;
                        $ledger_entry->account_name = $member->name . '-' . $group->ledger_group;
                        $ledger_entry->member_id =  $member->id;
                        $ledger_entry->opening_balance = $old_ledger_entry->current_balance;
                        $ledger_entry->current_balance = $ledger_entry->opening_balance;
                        $ledger_entry->type  = 'DR';
                        // dd($year_id);
                        $ledger_entry->year_id = $year_id;
                        $ledger_entry->created_by = 1;
                        $ledger_entry->status = 1;
                        $ledger_entry->save();
                    }
            }

            /**Member Share updated */
            // dd($member);
            if($member->shares()->count() > 0){

                $member->shares()->update(['year_id' => $year_id]);
                // dd($member);
            }
            /**End */
        }

        //************************** for fixed ledger account creation **************************
        $fixed_accounts = LedgerAccount::where('is_member_account', 0)->get();
        foreach ($fixed_accounts as $key => $fixed_account) {
            $new_fixed_account = $fixed_account->replicate();
            $new_fixed_account->year_id = $year_id;

            $new_fixed_account->save();
        }

        //************************** for Balance Sheet creation **************************

        $data['ledger_ac'] = LedgerAccount::where('ledger_group_id', 7)->where('status', 1)->get();
        foreach ($data['ledger_ac'] as $key => $value) {
            $balanceSheet = new BalanceSheet();
            $balanceSheet->ledger_ac_id = $value->id;
            $balanceSheet->ledger_ac_name = $value->account_name;
            $balanceSheet->balance = 0;
            $balanceSheet->year_id = $year_id;
            $balanceSheet->save();
        }


        // return redirect()->route('home');

        // dd('Successfully Done');
    }
}



// -> add financial year and set as current
// -> all ledger new entry (member and other_fixed account)
// -> opening balance
// -> balance sheet
// -> Share Amount
// -> monthly saving
