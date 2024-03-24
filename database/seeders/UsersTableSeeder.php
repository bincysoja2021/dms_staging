<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::create([
            'full_name' => 'Admin',
            'email' => 'admin@exacoreitsolutions.com',
            'password' => Hash::make('123456'),
            'user_name'=>'Admin',
            'user_type'=>'Super admin',
            'otp'=>random_int(100000, 999999),
            'active_status'=>1,
            'user_registerd_date'=>date("Y-m-d"),
        ]);
         User::create([
            'full_name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('123456'),
            'user_name'=>'Manager',
            'user_type'=>'Manager',
            'otp'=>random_int(100000, 999999),
            'active_status'=>1,
            'user_registerd_date'=>date("Y-m-d"),
        ]);
    }
}
