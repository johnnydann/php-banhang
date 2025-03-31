<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'description',
        'image_url',
        'category_id',
        'sizes',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'is_active' => 'boolean',
        'sizes' => 'array',
    ];

    // Relationship với Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship với ProductImage
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}