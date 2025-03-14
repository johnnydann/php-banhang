<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function getAll();
    public function getById(int $id);
    public function add(Category $category);
    public function update(Category $category);
    public function delete(int $id);
}