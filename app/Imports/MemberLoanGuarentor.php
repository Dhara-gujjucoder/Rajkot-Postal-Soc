<?php

namespace App\Imports;

use App\Models\LoanMaster;
use DateTime;
use DateInterval;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class MemberLoanGuarentor implements ToModel, WithStartRow, WithLimit, WithMultipleSheets, WithCalculatedFormulas
{
    public function startRow(): int
    {
        return 2;
    }
    public $not_insert = [];

    public function model(array $row)
    {
        $loan = LoanMaster::where('loan_no', $row[2])->get()->first();
        // dd($$row[4]);
        if($loan){

            $loan->payment_type = $row[3];
            $loan->g1_member_id = Member::where('uid',$row[4])->first()->id ?? 0;
            $loan->g2_member_id = Member::where('uid',$row[5])->first()->id ?? 0;
            $loan->gcheque_no = $row[6];
            $loan->gbank_name = $row[7];
            $loan->save();
        }else{
            dd($row);
        }
        return $loan;


    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function limit(): int
    {
        return 71;
    }
}
