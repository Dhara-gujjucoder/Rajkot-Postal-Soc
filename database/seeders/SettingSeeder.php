<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Setting::create([
            'title' => 'Rajkot Postal Soc.'	,
            'logo' => 'assets/images/logo.png',	
            'favicon' => 'assets/images/favicon.png',	
            'email' => 'rajkot_postal@gmail.com',	
            'phone' => '1111111111',	
            'address' => '7Q3W+QPG, P and T Colony, P.&T.Colony, Rajkot, Gujarat 360004.',	
        ]);
    }
}
