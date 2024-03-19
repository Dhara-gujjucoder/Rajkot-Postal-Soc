<?php

namespace App\Traits;

use App\Models\MemberShare;
use App\Models\MemberShareDetail;

trait UpdateMemberShare
{

    public function update_member_share($member, $no_of_share)
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

            $share_detail_entry = new MemberShareDetail();
            $share_detail_entry->member_share_id = $share_entry->id;
            $share_detail_entry->member_id = $share_entry->member_id;
            $share_detail_entry->year_id = currentYear()->id;
            $share_detail_entry->is_purchase = 1;
            $share_detail_entry->save();
            // dd($share_detail_entry);

        }


        $member->share_ledger_account->update(['current_balance' => ($member->share_ledger_account->current_balance + ($new_share * current_share_amount()->share_amount))]);
        $member->total_share = $no_of_share;
        $member->save();
        // $share_total_price = MemberShare::where('member_id', $member->id)->where('status', 1)->first();

        // $member->share_total_price =  $member->share_total_price + ($new_share * current_share_amount()->share_amount);   //$share_total_price;
        $member->save();
    }
}
