<?php

namespace App\Imports;

use DateTime;
use App\Models\Member;
use App\Models\LedgerShareCapital;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;
// use Maatwebsite\Excel\Concerns\ToCollection;

class AllShareImport implements ToModel, WithStartRow, WithLimit
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $member = Member::where('uid', $row[1])->get()->first();

        if ($member) {

            $fn_month = 3;
            $end_date = new DateTime('2022' . '-' . $fn_month . '-01');

            for ($j = 1; $j <= 12; $j++) {
                
                if($row[$j+4] > 0 ){

                    for ($i = 1; $i <= 5; $i++) {
                        $count = LedgerShareCapital::count() + 1;
                        $no = str_pad($count, 6, 0, STR_PAD_LEFT);
    
                        $ledger = LedgerShareCapital::create([
                            'ledger_account_id' => $member->LedgerAccount->id,
                            'member_id' => $member->id,
                            'share_code' => $no,
                            'share_amount' => 100,
                            'year_id' => 1,
                            'status' => 1,
                            'created_date' => $end_date->format('Y-m-d'),
                        ]);
                    }
                }
                $end_date->modify('+1 month');
            }
           
            $query = LedgerShareCapital::where('member_id', $member->id)->where('status',1);
            $total_share = $query->count();
            // $share_total_price = $query->sum('share_amount');
            // dd($row[4]);
            $member->total_share = $total_share;
            $member->share_total_price = $row[4];
            $member->save();

            return $ledger;
        }
    }

    public function limit(): int
    {
        return 2;
    }
}
