<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Constants\RoleConstants;

class UpdateUserRoles extends Command
{
    protected $signature = 'users:update-roles';
    protected $description = 'Update role column based on Spatie roles';

    public function handle()
    {
        $users = User::all();
        $updated = 0;

        foreach ($users as $user) {
            $roleName = $user->getRoleNames()->first();
            $role = 'user'; // Giá trị mặc định
            
            if ($roleName === RoleConstants::ROLE_ADMIN) {
                $role = 'admin';
            } elseif ($roleName === RoleConstants::ROLE_EMPLOYEE) {
                $role = 'employee';
            } elseif ($roleName === RoleConstants::ROLE_CUSTOMER) {
                $role = 'customer';
            }
            
            if ($user->role !== $role) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['role' => $role]);
                $updated++;
            }
        }

        $this->info("Updated roles for {$updated} users.");
    }
}