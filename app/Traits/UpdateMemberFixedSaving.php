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
        $fixed_saving = $fixed_saving+current_fixed_saving()->monthly_saving; // include current month also
        $remaining = $fixed_saving / (current_fixed_saving()->monthly_saving);
        MemberFixedSaving::create([
                    'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
                    'member_id' => $member->id,
                    'month' => date('m-Y',strtotime($month)),
                    'fixed_amount' => $fixed_saving,
                    'year_id' => $this->current_year->id,
                    'loan_settlement' => 1,
                    'status' => 1
                ]);

        $member->fixed_saving_ledger_account->update(['current_balance' => $member->fixed_saving_ledger_account->opening_balance + $member->fixed_saving()->sum('fixed_amount')]);


    }
}
