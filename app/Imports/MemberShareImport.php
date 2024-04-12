<?php

namespace App\Imports;

use DateTime;
use App\Models\Member;
use App\Models\MemberShare;
use App\Models\MemberShareDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
// use Maatwebsite\Excel\Concerns\ToCollection;

class MemberShareImport implements ToModel, WithStartRow, WithLimit, WithMultipleSheets, WithCalculatedFormulas
{
    public function startRow(): int
    {
        return 3;
    }
    public $not_insert = [];
    public $total = 0;
    public function model(array $row)
    {
        $member = Member::where('uid', $row[1])->get()->first();
        // dd($member);

        if ($member) {
            $fn_month = 3;
            $D = ($row[5] / 100) + (isset($row[6]) ? $row[6] / 100 : 0) ;
            $curTime = date('2023-03-01');

            $this->total = $this->total + ($row[7] / 100) ;

            for ($d=1; $d<=$D; $d++) {
                $count = MemberShare::count() + 1;
                $no = str_pad($count, 6, 0, STR_PAD_LEFT);

                $share_entry = MemberShare::create([
                    'ledger_account_id' => $member->share_ledger_account->id ?? 0,
                    'member_id' => $member->id,
                    'share_code' => $no,
                    'share_amount' => 100,
                    'year_id' => 1,
                    'status' => 1,
                    'purchase_on' => $curTime,
                    // 'sold_on' => $curTime,
                ]);

                    $share_detail_entry = new MemberShareDetail();
                    $share_detail_entry->member_share_id = $share_entry->id;
                    $share_detail_entry->member_id = $share_entry->member_id;
                    $share_detail_entry->year_id = currentYear()->id;
                    $share_detail_entry->is_purchase = 1;
                    $share_detail_entry->save();
            }

            $sold = (isset($row[7]) ? $row[7] / 100 : 0);

            if( $sold>0){
                $SoldTime = date("Y-m-d");

                for ($f=1; $f<=$sold; $f++) {
                    $membershare = MemberShare::where('member_id',$member->id)->where('status',1)->latest()->first();
                    $membershare->status = 0;
                    $membershare->sold_on = $SoldTime;
                    $membershare->save();

                    $share_detail_entry = new MemberShareDetail();
                    $share_detail_entry->member_share_id = $membershare->id;
                    $share_detail_entry->member_id = $membershare->member_id;
                    $share_detail_entry->year_id = currentYear()->id;
                    $share_detail_entry->is_sold = 1;
                    $share_detail_entry->save();
                }
            }

            $query = MemberShare::where('member_id', $member->id)->where('status',1);
            $total_share = $query->count();
                    // $share_total_price = $query->sum('share_amount');
                    // dd($row[4]);

            $member->total_share = $total_share;
            $member->share_total_price = $row[8];
            $opening_bal = $row[5];
                    // dd($member->share_total_price);
            $member->save();

            $member->share_ledger_account->update(['opening_balance' =>$opening_bal ?? '','current_balance' =>$row[8]]);

        }
        else{
            $this->not_insert[] = 'notexist_'.$row[1];
        }
        return $member;
    }

    public function sheets(): array
    {
        return [
            1 => $this,
        ];
    }

    public function limit(): int
    {
        return 219;
    }
}
