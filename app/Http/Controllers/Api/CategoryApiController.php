<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Lấy tất cả danh mục
     */
    public function getAllCategories()
    {
        $categories = $this->categoryRepository->getAll();
        return response()->json($categories);
    }

    /**
     * Lấy danh mục theo ID
     */
    public function getCategoryById(int $id)
    {
        //$id = $request->query('id');
        $category = $this->categoryRepository->getById($id);
        
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    /**
     * Thêm danh mục mới
     */
    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|unique:categories'
        ]);

        $category = new Category($request->all());
        $this->categoryRepository->add($category);

        return response()->json($category, 201);
    }

    /**
     * Cập nhật danh mục
     */
    public function updateCategory(Request $request, int $id)
    {
        try {            
            $request->validate([
                'name' => 'required|string|max:50',
                'slug' => 'required|string|unique:categories,slug,' . $id
            ]);

            $category = $this->categoryRepository->getById($id);
            
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $category->fill($request->all());
            $this->categoryRepository->update($category);

            return response()->json([
                'success' => true,
                'message' => 'Update successful',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Xóa danh mục
     */
    public function deleteCategory(Request $request, int $id)
    {
        $category = $this->categoryRepository->getById($id);
        
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $this->categoryRepository->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Delete successful'
        ]);
    }
}