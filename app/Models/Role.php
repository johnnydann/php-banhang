<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Lấy danh sách users thuộc role này
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}