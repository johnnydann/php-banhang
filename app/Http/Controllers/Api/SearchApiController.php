<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Tìm kiếm sản phẩm theo tên
     */
    public function search(Request $request)
    {
        $query = $request->query('query');
        
        if (empty($query)) {
            return response()->json(['error' => 'Please provide a search query.'], 400);
        }
        
        $products = $this->productRepository->searchByName($query);
        
        return response()->json($products);
    }

    /**
     * Gợi ý tên sản phẩm dựa trên từ khóa
     */
    public function suggest(Request $request)
    {
        $term = $request->query('term');
        
        if (empty($term)) {
            return response()->json(['error' => 'Please provide a search term.'], 400);
        }
        
        $products = $this->productRepository->searchByName($term);
        
        // Lấy các tên sản phẩm phân biệt
        $suggestions = collect($products)->pluck('name')->unique()->values()->all();
        
        return response()->json($suggestions);
    }
}