<?php

namespace App\Imports;

use DateTime;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class MemberLoanImport implements ToModel, WithStartRow, WithLimit, WithMultipleSheets, WithCalculatedFormulas
{
    public function startRow(): int
    {
        return 3;
    }
    public $not_insert = [];

    public function model(array $row)
    {
        $member = Member::where('uid', $row[5])->get();
        // dump($member->count());
        // dd($member);

        if($row[5] > 0) {
            dd($member);

        }

    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function limit(): int
    {
        return 81;
    }
}
