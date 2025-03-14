<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll()
    {
        return Category::all();
    }

    public function getById(int $id)
    {
        return Category::find($id);
    }

    public function add(Category $category)
    {
        $category->save();
        return $category;
    }

    public function update(Category $category)
    {
        $category->save();
        return $category;
    }

    public function delete(int $id)
    {
        Category::destroy($id);
    }
}