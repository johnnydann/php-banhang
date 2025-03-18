<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Constants\RoleConstants;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Tạo roles
        Role::create(['name' => RoleConstants::ROLE_ADMIN, 'guard_name' => 'web']);
        Role::create(['name' => RoleConstants::ROLE_EMPLOYEE, 'guard_name' => 'web']);
        Role::create(['name' => RoleConstants::ROLE_CUSTOMER, 'guard_name' => 'web']);
        Role::create(['name' => RoleConstants::ROLE_COMPANY, 'guard_name' => 'web']);

        $this->command->info('Roles created successfully!');
    }
}