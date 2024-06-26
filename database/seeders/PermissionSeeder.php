<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // 'create-role',
            // 'edit-role',
            // 'delete-role',
            // 'view-role',
            // 'view-user',
            // 'create-user',
            // 'edit-user',
            // 'delete-user',
            // 'view-account_type',
            // 'create-account_type',
            // 'edit-account_type',
            // 'delete-account_type',
            // 'view-ledger_account',
            // 'create-ledger_account',
            // 'edit-ledger_account',
            // 'delete-ledger_account',
            // 'view-member',
            // 'create-member',
            // 'edit-member',
            // 'delete-member',
            // 'edit-general-setting',
            // 'loaninterest-setting',
            // 'share-amount-setting',
            // 'monthly-saving-setting',

            // 'view-salary_deduction',
            // 'create-salary_deduction',
            // 'edit-salary_deduction',
            // 'delete-salary_deduction',

            // 'create-financial_year',
            // 'edit-financial_year',
            // 'delete-financial_year',
            // 'view-financial_year',

            // 'create-ledger_entries',
            // 'edit-ledger_entries',
            // 'delete-ledger_entries',
            // 'view-ledger_entries'

            // 'create-ledger_group',
            // 'edit-ledger_group',
            // 'delete-ledger_group',
            // 'view-ledger_group'

            // 'create-department',
            // 'edit-department',
            // 'delete-department',
            // 'view-department',

            // 'create-ledger_group',
            // 'edit-ledger_group',
            // 'delete-ledger_group',
            // 'view-ledger_group'

            // 'create-loan_matrix',
            // 'edit-loan_matrix',
            // 'delete-loan_matrix',
            // 'view-loan_matrix',

            // 'create-bulk_entries',
            // 'edit-bulk_entries',
            // 'export-bulk_entries'

            // 'create-loan',
            // 'edit-loan',
            // 'delete-loan',
            // 'view-loan',

            // 'create-member_share',
            // 'edit-member_share',
            // 'delete-member_share',
            // 'view-member_share',

            //  'create-double_entries',
            // 'edit-double_entries',
            // 'delete-double_entries',
            // 'view-double_entries',

            // 'ledger-share-report',
            //  'ledger-fixed-saving-report'

            // 'view-balance_sheet',
            // 'edit-balance_sheet',

            // 'view-general-setting',
            // 'edit-general-setting',
            // 'edit-loaninterest-setting',
            // 'edit-share-amount-setting',
            // 'edit-monthly-saving-setting'

            'create-loaninterest-setting',

        ];

        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::create(['guard_name' => config('auth.defaults.guard'), 'name' => $permission]);
        }
    }
}
