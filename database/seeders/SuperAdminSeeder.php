<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@rajkotpostal.com',
            'password' => Hash::make('rajkotpostalsoc12#'),
            'is_member' => 0
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@rajkotpostal.com',
            'password' => Hash::make('rajkotpostalsoc12#'),
            'is_member' => 0
        ]);
        $admin->assignRole('Admin');

        // // Creating member User
        // $user = User::create([
        //     'name' => 'User',
        //     'email' => 'user@rajkotpostal.com',
        //     'password' => Hash::make('rajkotpostalsoc12#'),

        // ]);
        // $member = Member::create([
        //     'user_id' =>  $user->id,
        //     'uid' => 0,
        //     'company' => 'ABc Ind pvt ltd',
        //     'designation' => 'Employee',
        //     'gender' => 'male',
        //     'birthdate' => date('Y-m-d'),
        //     'mobile_no' => 9985324715,
        //     'whatsapp_no' => 9985324715,
        //     'aadhar_card_no' => 998532473415,
        //     'aadhar_card' => '/images/aadhar_card/1234.png',
        //     'pan_no' => 'DR45DF66',
        //     'pan_card' => '/images/aadhar_card/1234.png',
        //     'current_address' => 'C-202 shradhha height, sorthyavadi circal near karan para',
        //     'parmenant_address' => 'C-202 shradhha height, sorthyavadi circal near karan para',
        //     'salary' => 12000,
        //     'da' => 2000,
        //     'nominee_name' => 'surya',
        //     'nominee_relation' => 'son',
        //     'registration_no' => '53528423',
        //     'department_id_proof' => '',
        //     'saving_account_no' => '',
        //     'bank_name' => '',
        //     'ifsc_code' => '',
        //     'branch_address' => 'Rajkot',
        //     'signature' => '',
        //     'witness_signature' => '',
        // ]);
        // $user->assignRole('User');
    }
}
