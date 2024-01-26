<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Member;
use App\Models\AccountType;
use App\Models\LedgerGroup;
use App\Models\LedgerAccount;
use App\Models\SalaryDeduction;
use Illuminate\Support\Facades\Hash;
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
    public function getdept_id($id){
        $deps = [
             'HO'=>'1',
             'RO'=>'2',
             'SRM' => '3',
             'CIVIL WING' => '4',
             'OTHERS'=>'6'

        ];
        return $deps[$id];
    }
    public function model(array $row)
    {
        $not_inserted[] = '';
        if($row[0] == 'month'){
            $this->month = $row[1];
        }
        if($row[2] == 'year'){
            $this->year = $row[3];
        }
        if(in_array($row[0],['HO','RO','SRM','CIVIL WING','OTHERS'])){
            if ($row[2] != null && is_numeric($row[2]) && $this->month == 3 && $this->year == 2022) {
                // dd($this);
                // if ($row[2] != null && $row[2] == '266') {
                //     // dd($row);
                // }
                $member = Member::where('uid', $row[2])->get()->first();
                if ($member) {
                    // dump($row[2]);
                    $member->department_id = $this->getdept_id($row[0]);
                    $member->save();
                    // for ($i=1; $i <= 3; $i++) {
                    //     $group = LedgerGroup::where('id',$i)->first();
                    //     $ledger_entry = LedgerAccount::where('user_id',$member->user_id)->where('ledger_group_id',$i)->first();
                    //     if(!$ledger_entry){
                    //         $ledger_entry = new LedgerAccount();
                    //         $ledger_entry->ledger_group_id  = $group->id;
                    //         $ledger_entry->account_name  = $member->user->name.'-'.$group->ledger_group;
                    //         $ledger_entry->account_name  = $member->user->name.'-'.$group->ledger_group;
                    //         $ledger_entry->user_id	= $member->user_id;
                    //         $ledger_entry->opening_balance = 0;
                    //         $ledger_entry->type	= 'DR';
                    //         $ledger_entry->year_id	= 1;
                    //         $ledger_entry->created_by = 1;
                    //         $ledger_entry->status	= 1;
                    //         $ledger_entry->save();
                    //     }
                    // }
                    // dd($member);
                    return new SalaryDeduction([
                        'user_id'  => $member->user_id,
                        'uid'  => $row[2],
                        'department_id' => $member->department_id,
                        'rec_no'  => $row[4],
                        'principal' => $row[5],
                        'interest' => $row[6],
                        'fixed'  => $row[7],
                        'total_amount' => $row[8],
                        'month'  => $this->month.'-'.$this->year,
                        'year_id' => 1,
                    ]);

                    // dd($member);
                } else {
                    $user = new User();
                    // $user->id = $row[1];
                    $user->name = $row[3];
                    $user->email = $row[3].rand(11,99);
                    $user->password = Hash::make('rajkotpostalsoc12#');
                    $user->save();
                    $user->assignRole('User');

                    $member = new Member();
                    $member->user_id = $user->id;
                    $member->department_id = $this->getdept_id($row[0]);
                    $member->registration_no = '11111111';
                    $member->uid =  $row[2];
                    $member->mobile_no =  11111111;
                    $member->whatsapp_no = 11111111;
                    $member->aadhar_card_no =  11111111;
                    $member->status = 0;
                    $member->save();
                    return new SalaryDeduction([
                        'user_id'  => $member->user_id,
                        'uid'  => $row[2],
                        'department_id' => $member->department_id,
                        'rec_no'  => $row[4],
                        'principal' => $row[5],
                        'interest' => $row[6],
                        'fixed'  => $row[7],
                        'total_amount' => $row[8],
                        'month'  => $this->month.'-'.$this->year,
                        'year_id' => 1,
                    ]);
                    dump( 'nn'.$row[2]);
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
