<?php

namespace App\Imports;

use DateTime;
use App\Models\Member;
use App\Models\MemberShare;
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
        $member = Member::where('uid', $row[0])->get()->first();

        if ($member) {
            $fn_month = 3;
            $D = ($row[3] / 100) + (isset($row[4]) ? $row[4] / 100 : 0) ;
            $curTime = date('2022-03-01');

            $this->total = $this->total + ($row[5] / 100) ;

            for ($d=1; $d<=$D; $d++) {
                $count = MemberShare::count() + 1;
                $no = str_pad($count, 6, 0, STR_PAD_LEFT);

                $ledger = MemberShare::create([
                    'ledger_account_id' => $member->share_ledger_account->id ?? 0,
                    'member_id' => $member->id,
                    'share_code' => $no,
                    'share_amount' => 100,
                    'year_id' => 1,
                    'status' => 1,
                    'purchase_on' => $curTime,
                    // 'sold_on' => $curTime,
                ]);
            }

            $sold = (isset($row[5]) ? $row[5] / 100 : 0);
            if( $sold>0){
                $SoldTime = date("Y-m-d");
                for ($f=1; $f<=$sold; $f++) {
                    $membershare = MemberShare::where('member_id',$member->id)->where('status',1)->latest()->first();
                    $membershare->status = 0;
                    $membershare->sold_on = $SoldTime;
                    $membershare->save();
                }
            }

            $query = MemberShare::where('member_id', $member->id)->where('status',1);
            $total_share = $query->count();
                    // $share_total_price = $query->sum('share_amount');
                    // dd($row[4]);
                    
            $member->total_share = $total_share;
            $member->share_total_price = $row[6];
                    // dd($member->share_total_price);
            $member->save();
        }
        else{
            $this->not_insert[] = 'notexist_'.$row[0];
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
        return 196;
    }
}
