<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superAdminRole = Role::create(['guard_name' => config('auth.defaults.guard'),'name' => 'Super Admin']);
        $adminRole = Role::create(['guard_name' => config('auth.defaults.guard'),'name' => 'Admin']);
        $userRole = Role::create(['guard_name' => config('auth.defaults.guard'),'name' => 'User']);

        $superAdminRole->givePermissionTo(['create-role',
        'edit-role',
        'delete-role',
        'view-role',
        'view-user',
        'create-user',
        'edit-user',
        'delete-user']);
    }
}
