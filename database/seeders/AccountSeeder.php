<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\FinancialYear;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FinancialYear::create(['year'=> date('Y'),
        	'start_date'=> date('Y-04-01'),
            'end_date'=> date('Y-03-30'),
            'is_selected' => 1
        ]);
        $acc[0] = AccountType::create(['type_name' => 'Assets']); // main balance
        $acc[1] = AccountType::create(['type_name' => 'Liabilities']); // how much he owes the federal government in income tax.
        $acc[2] = AccountType::create(['type_name' => 'Equity']); // invested into the company
        $acc[3] = AccountType::create(['type_name' => 'Revenues']); //revenue is the amount of money earned in exchange for goods and services
        $acc[4] = AccountType::create(['type_name' => 'Expenses']); //expenses are money paid to support a company's day-to-day operations

        Account::create([
            'account_name' => 'Fixed Saving A/C',
            'account_type_id' => $acc[0]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'Share Capital',
            'account_type_id' => $acc[2]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'Member Loan A/C',
            'account_type_id' => $acc[1]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'Bank Account',
            'account_type_id' => $acc[0]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'Loan Interest Income',
            'account_type_id' => $acc[0]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'Membership Fee',
            'account_type_id' => $acc[0]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'Cash',
            'account_type_id' => $acc[0]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'Direct Expense',
            'account_type_id' => $acc[4]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'Indirect Expense',
            'account_type_id' => $acc[4]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'TRAVELING ALLOWANCE',
            'account_type_id' => $acc[1]->id,
            'created_by' => 1
        ]);
        Account::create([
            'account_name' => 'Bill Payable',
            'account_type_id' => $acc[1]->id,
            'created_by' => 1
        ]);
    }
}
