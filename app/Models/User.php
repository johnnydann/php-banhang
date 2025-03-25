<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use App\Constants\RoleConstants;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'role',
        'banned_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'banned_until' => 'datetime',
    ];

    // protected static function booted()
    // {
    //     static::saved(function ($user) {
    //         // Lấy tên vai trò đầu tiên từ Spatie
    //         $roleName = $user->getRoleNames()->first();
            
    //         // Nếu không có role nào được gán thì bỏ qua
    //         if (!$roleName) {
    //             return;
    //         }
            
    //         // Ánh xạ từ tên vai trò Spatie sang giá trị cột role
    //         $role = 'customer'; // Giá trị mặc định
            
    //         if ($roleName === RoleConstants::ROLE_ADMIN) {
    //             $role = 'admin';
    //         } elseif ($roleName === RoleConstants::ROLE_EMPLOYEE) {
    //             $role = 'employee';
    //         } elseif ($roleName === RoleConstants::ROLE_CUSTOMER) {
    //             $role = 'customer';
    //         } elseif ($roleName === RoleConstants::ROLE_COMPANY) {
    //             $role = 'company';
    //         }
            
    //         // Chỉ cập nhật khi giá trị khác với giá trị hiện tại
    //         if ($user->role !== $role) {
    //             // Cập nhật trực tiếp qua DB::table để tránh gọi lại saved event
    //             DB::table('users')
    //                 ->where('id', $user->id)
    //                 ->update(['role' => $role]);
    //         }
    //     });
    // }

    public function isBanned()
    {
        return $this->banned_until !== null && now()->lessThan($this->banned_until);
    }
}