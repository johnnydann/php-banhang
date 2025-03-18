<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Constants\RoleConstants;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {

        DB::table('users')->where('email', 'admin@example.com')->delete();
        DB::table('users')->where('email', 'employee@example.com')->delete();
        DB::table('users')->where('email', 'customer@example.com')->delete();

        // Tạo tài khoản Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone_number' => '0123456789',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole(RoleConstants::ROLE_ADMIN);
        
        // Tạo tài khoản Employee
        $employee = User::create([
            'name' => 'Employee',
            'email' => 'employee@example.com',
            'phone_number' => '0123456788',
            'password' => Hash::make('password123'),
        ]);
        $employee->assignRole(RoleConstants::ROLE_EMPLOYEE);
        
        // Tạo tài khoản Customer
        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'phone_number' => '0123456787',
            'password' => Hash::make('password123'),
        ]);
        $customer->assignRole(RoleConstants::ROLE_CUSTOMER);
    }
}