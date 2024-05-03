<?php

namespace App\Imports;

use DateTime;
use DateInterval;
use App\Models\Member;
use App\Models\LoanEMI;
use App\Models\LoanMaster;
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
        $member = Member::where('uid', $row[5])->get()->first();
        // dump($member->count());
        // dd($member);
        $loan_amt = 0;
        $loan_master = (object)[];
        if ($member) {
            $j = 11;
            $m = 0;

            if ($row[9] > 0) {
                for ($i = 0; $i < 12; $i++) {
                    $row[$j];   //11
                    $row[$j + 1]; //12
                    $row[$j + 2]; //13

                    // *******MONTH GENERATE********
                    if ($i + 4 == 13) {
                        $m = '01';
                    } elseif ($i + 4 == 14) {
                        $m = '02';
                    } elseif ($i + 4 == 15) {
                        $m = '03';
                    } else {
                        $m = $i + 4;
                    }

                    $current_year = '2023';
                    $next_year = '2024';
                    $month = str_pad($m, 2, '0', STR_PAD_LEFT) . ($i > 8 ? '-' . $next_year : '-' . $current_year);
                    // dump($month);

                    if ($i == 0) {
                        $member->loan_ledger_account->update(['opening_balance' => $row[9]]);
                        $loan_amt = $row[9];

                        $loan_master = LoanMaster::create([
                            'ledger_account_id' => $member->loan_ledger_account->id,
                            'loan_no'           => $row[8] ?? '',
                            'year_id'           => currentYear()->id,
                            'month'             => '03-2023',
                            'member_id'         => $member->id,
                            'start_month'       => '03-2023',
                            'end_month'         => '',
                            'principal_amt'     => $row[9],
                            'emi_amount'        => $row[10],
                            'status'            => '1',
                        ]);
                    }

                    LoanEMI::create([
                        'loan_master_id'    => $loan_master->id,
                        'month'             => $month,
                        'member_id'         => $loan_master->member_id,
                        'ledger_account_id' => $member->loan_ledger_account->id,
                        'principal_amt'     => $loan_master->principal_amt,
                        'interest'          => '9.5',
                        'interest_amt'      => intval($row[$j + 1] ?? '0'),
                        'emi'               => $row[10],
                        'principal'         => intval($row[$j] ?? '0'),
                        'rest_principal'    => intval($row[$j + 2]),
                        'status'            => '2',
                    ]);
                    if ($i == 11) {
                        $cur_bal = intval($row[$j + 2]);
                        $bal = $member->loan_ledger_account->update(['current_balance' => $cur_bal]);
                        // dd($cur_bal);
                    }

                    // *********** PENDING LOAN EMI'S SET **********

                    if ($row[$j + 2] > 0 && $i == 11) {
                        $loan_amt    = intval($row[$j + 2]);
                        $emi_amount  = $row[10];
                        $loan_amount = $loan_amt;
                        $no_of_emi   = ($loan_amt / $emi_amount);
                        $emi_c       = getLoanParam()[0];
                        $emi_d       = getLoanParam()[1];
                        $rate        = 9.5;
                        $dmonth      = date('d-m-Y',strtotime('01-'.$month));
                        // dd($no_of_emi);

                        // $member->loan_ledger_account->update(['current_balance' => $loan_amt]);
                        while($loan_amt > 0){
                        // for ($i = 1; $i <= $no_of_emi; $i++) {
                            $emi_interest = intval($loan_amt * $rate / 100 * $emi_c / $emi_d);
                            $date         = new DateTime($dmonth);
                            $date->add(new DateInterval('P1M'));
                            $dmonth       = $date->format('d-m-Y');

                            if ($loan_amt > 0) {
                                if ($loan_amt < $emi_amount) {
                                    $emi_amount = $loan_amt;
                                    $loan_amt= 0;
                                }else{
                                    $loan_amt = intval($loan_amt - ($emi_amount - $emi_interest));
                                }
                                // console.log($emi_amount, $emi_interest);

                                LoanEMI::create([
                                    'loan_master_id'    => $loan_master->id,
                                    'month'             => $date->format('m-Y'),
                                    'member_id'         => $loan_master->member_id,
                                    'ledger_account_id' => $member->loan_ledger_account->id,
                                    'principal_amt'     => $loan_amount,
                                    'interest'          => $rate,
                                    'interest_amt'      => $emi_interest,
                                    'emi'               => $emi_amount,
                                    'principal'         => $emi_amount - $emi_interest,
                                    'rest_principal'    => $loan_amt,
                                    'status'            => 1,
                                ]);
                            }
                        }
                    }

                    $j = $j + 3;
                }
            }
        }
        return $member;
        // dd('done');//
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function limit(): int
    {
        return 82;
    }
}
