<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll()
    {
        return Product::all();
    }

    public function getById(int $id)
    {
        return Product::with('category')->find($id);
    }

    public function getByCategoryId(int $id)
    {
        return Product::where('category_id', $id)->get();
    }

    public function add(Product $product)
    {
        $product->save();
        return $product;
    }

    public function update(Product $product)
    {
        $product->save();
        return $product;
    }

    public function delete(int $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->is_active = false;
            $product->save();
        }
    }

    public function searchByName(string $name)
    {
        if (empty(trim($name))) {
            return collect();
        }

        $searchTerms = preg_split('/[\s,.;]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
        
        $query = Product::where('is_active', true);
        
        foreach ($searchTerms as $term) {
            $query->where('name', 'like', "%{$term}%");
        }
        
        return $query->get();
    }

    public function activate(int $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->is_active = true;
            $product->save();
        }
    }

    public function getInactiveProducts()
    {
        return Product::where('is_active', false)->get();
    }

    public function getPaginatedActiveProducts($pageSize, $pageNumber)
    {
        return Product::where('is_active', true)
                    ->orderByDesc('id')
                    ->skip(($pageNumber - 1) * $pageSize)
                    ->take($pageSize)
                    ->get();
    }

}