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
            'loaninterest-setting',
            'share-amount-setting',
            'monthly-saving-setting'
        ];

        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::create(['guard_name' => config('auth.defaults.guard'), 'name' => $permission]);
        }
    }
}
