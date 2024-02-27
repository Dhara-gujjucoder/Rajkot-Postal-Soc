<?php

namespace App\Traits;

use App\Models\MemberShare;
use App\Models\MemberFixedSaving;

trait UpdateMemberFixedSaving
{

    public function update_fixed_Saving($member, $fixed_saving)
    {
        // dd($member->id);
        $saving  = MemberFixedSaving::where('member_id', $member->id)->get()->count();
        $remaining = $fixed_saving / (current_fixed_saving()->monthly_saving);
        $total = count(getMonthsOfYear($this->current_year->id));
        // dump($total,$saving,$remaining);
        // dd($saving,$remaining,$total);
        if (($total - $saving) ==  $remaining) {
            $amt_total = 0;
            for ($i = 0; $i < $remaining - 1; $i++) {
                $amt_total += current_fixed_saving()->monthly_saving;
                MemberFixedSaving::create([
                    'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
                    'member_id' => $member->id,
                    'month' => getMonthsOfYear($this->current_year->id)[$i]['value'],
                    'fixed_amount' => 0,
                    'year_id' => $this->current_year->id,
                    'status' => 1
                ]);
            }
           $final =  MemberFixedSaving::create([
                'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
                'member_id' => $member->id,
                'month' => getMonthsOfYear($this->current_year->id)[$i]['value'],
                'fixed_amount' => $amt_total += current_fixed_saving()->monthly_saving,
                'year_id' => $this->current_year->id,
                'status' => 1
            ]);
            $member->fixed_saving_ledger_account->update(['current_balance' => ($member->fixed_saving_ledger_account->current_balance + $amt_total)]);
        }
    }
}
