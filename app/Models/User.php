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

    /**
     * Khởi tạo events cho model
     */
    protected static function booted()
    {
        static::saved(function ($user) {
            // Lấy tên vai trò đầu tiên từ Spatie
            $roleName = $user->getRoleNames()->first();
            $role = 'user'; // Giá trị mặc định
            
            if ($roleName === RoleConstants::ROLE_ADMIN) {
                $role = 'admin';
            } elseif ($roleName === RoleConstants::ROLE_EMPLOYEE) {
                $role = 'employee';
            } elseif ($roleName === RoleConstants::ROLE_CUSTOMER) {
                $role = 'customer';
            }
            
            // Cập nhật trực tiếp cột role mà không gọi lại saved event
            if ($user->role !== $role) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['role' => $role]);
            }
        });
    }

    /**
     * Check if the user is banned
     *
     * @return bool
     */
    public function isBanned()
    {
        return $this->banned_until !== null && now()->lessThan($this->banned_until);
    }
}