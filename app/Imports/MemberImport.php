<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\User;
use App\Models\Member;
use App\Models\LedgerGroup;
use App\Models\LedgerAccount;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
// use Maatwebsite\Excel\Concerns\ToCollection;

class MemberImport implements ToModel, WithStartRow,WithMultipleSheets,WithLimit
{
    public $not_insert = [];
    public function startRow(): int
    {
        return 2;
    }
    public function model(array $row)
    {
        // Define how to create a model from the Excel row data
        // $member = Member::latest()->first();
        if (isset($row[1]) && $row[1]) {
            // dd($row);

            // $user = new User();

            // $email = $row[12] ? $row[12] : null;
            // // $user->id = $row[1];
            // $user->name = $row[4];
            // $user->email = $email;
            // $user->password = Hash::make('rajkotpostalsoc12#');
            // $user->save();
            // $user->assignRole('User');


            // $birthdate = date_create_from_format('dmY', $row[5]);
            // $format_birthdate = $birthdate ? $birthdate->format('Y-m-d') : null;

            // $member2 = Member::create([
            //     'user_id' => $user->id,
            //     'uid' => $row[1],
            //     'department_id' => $row[7],
            //     'registration_no' => $row[3],
            //     'birthdate' => $format_birthdate,
            //     'mobile_no' => $row[9],
            //     'saving_account_no' => $row[10],
            //     'aadhar_card_no' => $row[11],
            //     'current_address' => $row[13],
            //     'nominee_name' => $row[14],
            //     'nominee_relation' => $row[15],
            //     // Add more columns as needed
            // ]);

            // for ($i = 1; $i <= 3; $i++) {
            //     $group = LedgerGroup::where('id', $i)->first();
            //     // $ledger_entry = LedgerAccount::where('member_id', $member->id)->where('ledger_group_id', $i)->first();
            //     // if (!$ledger_entry) {
            //         // dd($member2);
            //         $ledger_entry = new LedgerAccount();
            //         $ledger_entry->ledger_group_id = $group->id;
            //         $ledger_entry->account_name = $user->name . '-' . $group->ledger_group;
            //         $ledger_entry->member_id =  $member2->id;
            //         $ledger_entry->opening_balance = 0;
            //         $ledger_entry->current_balance = 0;
            //         $ledger_entry->type  = 'DR';
            //         $ledger_entry->year_id = 1;
            //         $ledger_entry->created_by = 1;
            //         $ledger_entry->status = 1;
            //         $ledger_entry->save();
            //     // }
            // }
                $member = Member::where('uid', $row[1])->orderBy('uid', 'ASC')->first();
                if (!$member) {
                    // $this->not_insert[] = $row[1];
                    dd($member.'<-- member');
                }
                $department = Department::where('department_name',$row[7])->first();
                if (!$department) {
                    $this->not_insert[] = $row[1];
                    // dd($row[1].'<-- department');
                }else{

                    $member->department_id = $department->id;
                    // dd($member->department_id );
                    $member->save();
                }

            return $member;
            // return $member2;
        }

    }

    public function sheets(): array
    {
        return [
            1 => $this,
        ];
    }


    public function limit(): int
    {
        return 220;
    }

}
