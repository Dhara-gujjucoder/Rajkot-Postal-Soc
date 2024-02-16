<?php

namespace App\Traits;

use App\Models\MemberShare;

trait UpdateMemberShare
{


    
    public function member_share($member, $no_of_share)
    {
        $exist_share = MemberShare::where('member_id', $member->id)->where('status', 1)->count();

        $new_share = $no_of_share - $exist_share;
        for ($i = 1; $i <= $new_share; $i++) {

            $count = MemberShare::count() + 1;
            $no = str_pad($count, 6, 0, STR_PAD_LEFT);
            // $no .= $count > 0 ? $count + 1 : 1;

            $share_entry = new MemberShare();
            $share_entry->ledger_account_id = $member->share_ledger_account->id ?? 0;
            $share_entry->member_id = $member->id;
            $share_entry->share_code = $no;
            $share_entry->share_amount = current_share_amount()->share_amount;
            $share_entry->year_id = currentYear()->id;
            $share_entry->status = 1;
            $share_entry->purchase_on = date('Y-m-d');
            $share_entry->save();
        }
        // $share_total_price = MemberShare::where('member_id', $member->id)->where('status', 1)->first();

        // $member->share_total_price =  $member->share_total_price + ($new_share * current_share_amount()->share_amount);   //$share_total_price;
        $member->save();
    }
}
