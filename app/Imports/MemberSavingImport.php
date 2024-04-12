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

class MemberSavingImport implements ToModel, WithStartRow, WithLimit, WithMultipleSheets, WithCalculatedFormulas
{
    public function startRow(): int
    {
        return 2;
    }
    public $not_insert = [];
    public function model(array $row)
    {
        $member = Member::where('uid', $row[1])->get()->first();
        if ($row[1] > 0) {

            // if(!$member){
            //     dd($row);
            // }
            // if(!$member->fixed_saving_ledger_account){
            //     dd($member);
            // }

            if ($member) {
                // $fn_month = 3;
                // $end_date = new DateTime('2022' . '-' . $fn_month . '-01');

                for ($j = 1; $j <= 12; $j++) {
                    // dump($row[$j+4]);
                    if (isset($row[$j + 5]) && $row[$j + 5] != "") { // row[6]

                        // for ($i = 1; $i <= 5; $i++) {
                        $count = MemberFixedSaving::count() + 1;
                        $m = 0;
                        if ($j + 2 == 13) {
                            $m = '1';
                        } elseif ($j + 2 == 14) {
                            $m = '2';
                        } else {
                            $m = $j + 2;
                        }

                        $current_year = currentYear()->start_year;
                        $next_year = currentYear()->end_year;
                        $month = str_pad($m, 2, '0', STR_PAD_LEFT) . ($j > 10 ? '-' . $next_year : '-' . $current_year);

                        // $month = str_pad($m, 2, '0', STR_PAD_LEFT) . ($j > 10 ? '-2024' : '-2023');

                        $ledger = MemberFixedSaving::create([
                            'ledger_account_id' => $member->fixed_saving_ledger_account->id ?? 0,
                            'member_id' => $member->id,
                            'month' => $month,
                            'fixed_amount' => $row[$j + 5],
                            'year_id' => 1,
                            'status' => 1
                            // 'created_date' => $end_date->format('Y-m-d'),
                        ]);

                        // $ob = $member->fixed_saving_ledger_account->opening_balance;
                        // }

                    } else {
                        $this->not_insert[] = $row[1];
                    }
                    // $member->fixed_saving_ledger_account->update(['opening_balance' =>   $row[5], 'current_balance' => $row[19] - $row[5]]);
                    $member->fixed_saving_ledger_account->update(['opening_balance' =>   $row[5], 'current_balance' =>$row[19]]);
                }
            } else {
                $this->not_insert[] = $row;
            }
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
        return 217;
    }
}
