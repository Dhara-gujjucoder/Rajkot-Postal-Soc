<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class MemberShareExport implements FromCollection,WithHeadings,WithMapping,WithStrictNullComparison
{
    public function collection()
    {
        // return Member::get(['id','member_id','user_id',]);
    }

    public function headings(): array
    {
        $heading = ['M.No','Name','OB','Puch.','Sold','Net'];

        return $heading;
    }

    public function map($member): array
    {
        $entry = [

        ];


        return $entry;
    }
}

