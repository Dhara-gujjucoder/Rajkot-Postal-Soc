<?php

namespace App\Imports;

use App\Models\AccountType;
use App\Models\LedgerAccount;
use App\Models\Member;
use App\Models\SalaryDeduction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class SalaryDedImport implements ToModel,WithMultipleSheets,WithHeadingRow, SkipsEmptyRows, WithCalculatedFormulas
{
    public $un = [];
    public $month;
    public $year;
    
    public function model(array $row)
    {
        $not_inserted[] = '';
        if($row[0] == 'month'){
            $this->month = $row[1];
        }
        if($row[2] == 'year'){
            $this->year = $row[3];
        }
        if(in_array($row[0],['Ho','RO','SRM','CIVIL WING','OTHERS'])){
            if ($row[2] != null && is_numeric($row[2])) {
                // dd($this);
                // if ($row[2] != null && $row[2]) {
                $member = Member::where('uid', $row[2])->get()->first();
                if ($member) {
                    // dd($member);
                    return new SalaryDeduction([
                        'user_id'  => $member->user_id,
                        'uid'  => $row[2],
                        'ledger_ac_id' => LedgerAccount::where('type_name',$row[0])->pluck('id')->first() ? AccountType::where('type_name',$row[0])->pluck('id')->first() : 1 ,
                        'rec_no'  => $row[4],
                        'principal' => $row[5],
                        'interest' => $row[6],
                        'fixed'  => $row[7],
                        'total_amount' => $row[8],
                        'month'  => $this->month,
                        'year_id' => 1,
                    ]);
                    // dd($r );
                } else {
                    $not_inserted[] = $row;
                }
            }
        }else{
            $not_inserted[] = $row;
        }
        $this->un = $not_inserted;
    }
    public function sheets(): array
    {
        return [
            0 => $this,
            1 => $this,
            2 => $this,
            3 => $this,
            4 => $this,
            5 => $this,
            6 => $this,
            7 => $this,
            8 => $this,
            9 => $this,
            10 => $this,
            11 => $this,
            // 12 => $this,
        ];

    }
}
