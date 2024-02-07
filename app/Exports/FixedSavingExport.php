<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\MemberFixedSaving;
use Illuminate\Database\Schema\ForeignKeyDefinition;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class FixedSavingExport implements FromCollection,WithHeadings,WithMapping
{
    public function collection()
    {
        return Member::get(['id','uid','user_id']);
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

        $entry =  [
            $member->uid,
            $member->name,
            $member->fixed_saving_ledger_account->opening_balance ?? 0,
        ];


        foreach ($member->fixed_saving as $key => $value) {
            $entry[] = $value->fixed_amount;
            // dd($value);
        }
        $entry[] = $member->fixed_saving()->sum('fixed_amount');

        return $entry;
    }
}




// <?php

// namespace App\Exports;

// use App\Models\Member;
// use App\Models\MemberFixedSaving;
// use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\FromCollection;

// class FixedSavingExport implements FromCollection,WithHeadings,WithMapping
// {
//     public function collection()
//     {
//         return Member::with(['fixed_saving_ledger_account','fixed_saving'])->get();
//     }

//     public function headings(): array
//     {
//         // dd(getMonthsOfYear(1)->only(['month']));
//         $heading = ['M.No.','Name'];
//         $heading[] = 'OB';
//         foreach (getMonthsOfYear(1) as $key => $value) {
//             $heading[] = $value['month'];
//         }


//         // $heading[] = 'Total';

//         return $heading;
//     }

//     public function map($member): array
//     {
//         $entry = [
//             $member->uid,
//             $member->name,
//         ];

//         if($member->fixed_saving){

//             array_push( $entry ,$member->fixed_saving_ledger_account->opening_balance ?? 0);

//             foreach ($member->fixed_saving as $key => $value) {
//                 array_push( $entry ,$value->fixed_amount);
//             }

//             // array_push( $entry ,$member->fixed_saving->sum('fixed_amount'));
//         }
//         return $entry;
//     }
// }
