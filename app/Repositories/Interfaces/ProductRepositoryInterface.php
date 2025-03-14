<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function getAll();
    public function getById(int $id);
    public function getByCategoryId(int $id);
    public function add(Product $product);
    public function update(Product $product);
    public function delete(int $id);
    public function searchByName(string $name);
    public function activate(int $id);
    public function getInactiveProducts();
}