<?php

namespace App\Traits;

use App\Models\MemberShare;
use App\Models\MemberFixedSaving;

trait UpdateMemberFixedSaving
{

    public function update_fixed_Saving($member, $fixed_saving,$month)
    {
        // dd($member->id);
        // dd($fixed_saving);
        // $saving_count  = MemberFixedSaving::where('member_id', $member->id)->where('fixed_amount', '>', 0)->get()->count();
        $remaining = $fixed_saving / (current_fixed_saving()->monthly_saving);
        // $total = MemberFixedSaving::where('member_id', $member->id)->get()->count();
        MemberFixedSaving::create([
                    'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
                    'member_id' => $member->id,
                    'month' =>   date('m-Y',strtotime($month)),
                    'fixed_amount' => $fixed_saving,
                    'year_id' => $this->current_year->id,
                    'loan_settlement' => 1,
                    'status' => 1
                ]);

        // $remaining_entries = MemberFixedSaving::where('member_id', $member->id)
        // ->where('fixed_amount', '<=', 0)->get()->all();
        // dd(  $remaining_entries);
        // for ($i = 0; $i < $remaining; $i++) {

        //     MemberFixedSaving::create([
        //         'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
        //         'member_id' => $member->id,
        //         'month' =>   $remaining_entries[$i]->month,
        //         'fixed_amount' => current_fixed_saving()->monthly_saving,
        //         'year_id' => $this->current_year->id,
        //         'status' => 1
        //     ]);
        //     $remaining_entries[$i]->status = 0;
        //     $remaining_entries[$i]->save();
        // }
        $member->fixed_saving_ledger_account->update(['current_balance' => $member->fixed_saving_ledger_account->opening_balance + $member->fixed_saving()->sum('fixed_amount')]);

        // $member->fixed_saving_ledger_account->update(['current_balance' => $member->fixed_saving()->withoutGlobalScope('year')->withoutGlobalScope('bulk_entry')->sum('fixed_amount')]);

        // dump($total,$saving,$remaining);
        // dd($saving,$remaining,$total);
        // 12 - 12 ==
        // if (($total - $saving_count) ==  $remaining) {
        //     $amt_total = 0;
        //     for ($i = 0; $i < ($remaining - 1); $i++) {
        //         $amt_total += current_fixed_saving()->monthly_saving;
        //         MemberFixedSaving::create([
        //             'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
        //             'member_id' => $member->id,
        //             'month' => getMonthsOfYear($this->current_year->id)[$i]['value'],
        //             'fixed_amount' => 0,
        //             'year_id' => $this->current_year->id,
        //             'status' => 1
        //         ]);
        //     }
        //    $final =  MemberFixedSaving::create([
        //         'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
        //         'member_id' => $member->id,
        //         'month' => getMonthsOfYear($this->current_year->id)[$i]['value'],
        //         'fixed_amount' => $amt_total += current_fixed_saving()->monthly_saving,
        //         'year_id' => $this->current_year->id,
        //         'status' => 1
        //     ]);
        //     $member->fixed_saving_ledger_account->update(['current_balance' => ($member->fixed_saving_ledger_account->current_balance + $amt_total)]);
        // }
    }
}
