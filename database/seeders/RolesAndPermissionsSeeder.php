<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Constants\RoleConstants;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Tạo permissions
        $permissions = [
            // Sản phẩm
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            // Danh mục
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Sự kiện
            'view events',
            'create events',
            'edit events',
            'delete events',
            
            // Người dùng
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Đơn hàng
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Tạo roles và gán permissions
        
        // Customer Role
        $customerRole = Role::create(['name' => RoleConstants::ROLE_CUSTOMER]);
        $customerRole->givePermissionTo([
            'view products',
            'view categories',
            'view events',
            'create orders',
            'view orders'
        ]);
        
        // Company Role
        $companyRole = Role::create(['name' => RoleConstants::ROLE_COMPANY]);
        $companyRole->givePermissionTo([
            'view products',
            'view categories',
            'view events',
            'create orders',
            'view orders'
        ]);
        
        // Employee Role
        $employeeRole = Role::create(['name' => RoleConstants::ROLE_EMPLOYEE]);
        $employeeRole->givePermissionTo([
            'view products',
            'create products',
            'edit products',
            'view categories',
            'view events',
            'create events',
            'edit events',
            'view orders',
            'edit orders'
        ]);
        
        // Admin Role (có tất cả quyền)
        $adminRole = Role::create(['name' => RoleConstants::ROLE_ADMIN]);
        $adminRole->givePermissionTo(Permission::all());
    }
}