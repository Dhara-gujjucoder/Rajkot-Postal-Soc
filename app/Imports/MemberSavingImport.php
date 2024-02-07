<?php

namespace App\Imports;

use DateTime;
use App\Models\Member;
use App\Models\MemberFixedSaving;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
// use Maatwebsite\Excel\Concerns\ToCollection;

class MemberSavingImport implements ToModel, WithStartRow, WithLimit,WithMultipleSheets, WithCalculatedFormulas
{
    public function startRow(): int
    {
        return 2;
    }
    public $not_insert = [];
    public function model(array $row)
    {
        $member = Member::where('uid', $row[1])->get()->first();
        if ($member) {
            // $fn_month = 3;
        // $end_date = new DateTime('2022' . '-' . $fn_month . '-01');

            for ($j = 1; $j <= 12; $j++) {
                // dump($row[$j+4]);
                if(isset($row[$j+4]) && $row[$j+4] > 0){

                    // for ($i = 1; $i <= 5; $i++) {
                        $count = MemberFixedSaving::count() + 1;
                        $m = 0;
                        if($j+2 == 13)
                        {
                            $m = '1';
                        }elseif($j+2 == 14){
                            $m = '2';
                        }else{
                            $m = $j+2;
                        }
                        $month = str_pad($m, 2, '0', STR_PAD_LEFT).($j>10 ?'-2023':'-2022');

                        $ledger = MemberFixedSaving::create([
                            'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
                            'member_id' => $member->id,
                            'month' => $month,
                            'fixed_amount' => $row[$j+4],
                            'year_id' => 1,
                            'status' => 1
                            // 'created_date' => $end_date->format('Y-m-d'),
                        ]);
                        // $ob = $member->fixed_saving_ledger_account->opening_balance;
                        $member->fixed_saving_ledger_account->update(['opening_balance' =>   $row[17]]);
                    // }
                }else{
                    $this->not_insert[] = $row[1];
                }
                // $end_date->modify('+1 month');
            }

        }
        else{
            $this->not_insert[] = 'notexist_'.$row[1];
        }
        return $member;
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function limit(): int
    {
        return 211;
    }
}
