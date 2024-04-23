<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\MemberFixedSaving;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Schema\ForeignKeyDefinition;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class FixedSavingExport implements FromCollection,WithHeadings,WithMapping,WithStrictNullComparison
{
    public function collection()
    {
        return Member::get(['id', 'uid', 'user_id']);
    }

    public function headings(): array
    {
        $heading = ['M.No','Name','OB'];
        foreach (getMonthsOfYear(1) as $key => $value) {
            $heading[] = $value['month'];
        }

        $heading[] = 'Total';
        // dd($heading);

        return $heading;
    }

    public function map($member): array
    {
        // dd($member);
        $entry = [
            $member->uid,
            $member->name,
            $member->fixed_saving_ledger_account->opening_balance ?? '0',
        ];

        for ($i=0; $i<12; $i++) {
            $entry[] = isset($member->fixed_saving[$i]) ? $member->fixed_saving[$i]->fixed_amount : '0' ;
        }

        // foreach ($member->fixed_saving as $key => $value) {
        //     $entry[] = $value->fixed_amount ?? '-';
        //     // dd($value);
        // }
        $entry[] = $member->fixed_saving()->sum('fixed_amount');

        return $entry;
    }
}
