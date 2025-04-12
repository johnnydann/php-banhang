<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    private const DEFAULT_PAGE_SIZE = 6;
    
    private const IMAGE_DIRECTORY = 'productImages';

    private $productRepository;
    
    private $categoryRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Lấy tất cả sản phẩm đang hoạt động và phân trang
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllProducts(Request $request): JsonResponse
    {
        try {
            $pageNumber = (int) $request->input('pageNumber', 1);
            $pageSize = (int) $request->input('pageSize', self::DEFAULT_PAGE_SIZE);
    
            // Lấy danh sách phân trang từ repo
            $products = $this->productRepository->getPaginatedActiveProducts($pageSize, $pageNumber);
    
            // Tổng số sản phẩm active
            $total = Product::where('is_active', true)->count();
    
            // Bổ sung dữ liệu phụ cho sản phẩm
            $this->enrichProductsWithData($products);
    
            return response()->json([
                'products' => $products,
                'total' => $total,
                'page' => $pageNumber,
                'page_size' => $pageSize,
                'total_pages' => ceil($total / $pageSize),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Internal server error', 500);
        }
    }
    
    
    

    /**
     * Lấy sản phẩm theo ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getProductById($id): JsonResponse
    {
        try {
            $product = $this->productRepository->getById($id);
            
            if (!$product) {
                return $this->errorResponse('Product not found', 404);
            }

            // Thêm thông tin category cho sản phẩm
            $this->addCategoryToProduct($product);

            return response()->json($product);
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Product not found', 404);
        }
    }

        public function getByCategory(Request $request)
    {
        try {
            $categoryId = $request->query('categoryId');
            
            if (!$categoryId) {
                return response()->json(['message' => 'Category ID is required'], 400);
            }
            
            $products = Product::where('category_id', $categoryId)->get();
            
            // Thêm đường dẫn đầy đủ cho ảnh
            $products->each(function ($product) {
                $product->fullImageUrl = $product->image 
                    ? url('storage/' . $product->image) 
                    : '';
            });
            
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Đếm số lượng sản phẩm theo danh mục
     */
    public function countByCategory(Request $request)
    {
        try {
            $categoryId = $request->query('categoryId');
            
            if (!$categoryId) {
                return response()->json(['message' => 'Category ID is required'], 400);
            }
            
            $count = Product::where('category_id', $categoryId)->count();
            
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Thêm sản phẩm mới
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addProduct(Request $request): JsonResponse
    {
        try {
            // Validate dữ liệu đầu vào
            $validatedData = $this->validateProductData($request);
            
            // Tạo sản phẩm mới
            $product = new Product($validatedData);
            
            // Xử lý sizes
            $product->sizes = $request->has('sizes') && !empty($request->sizes) ? $request->sizes : null;

            // Xử lý upload hình ảnh
            if ($request->hasFile('imageFile')) {
                $product->image_url = $this->saveImage($request->file('imageFile'));
            }

            // Lưu sản phẩm vào database
            $this->productRepository->add($product);

            return response()->json($product, 201);
        } catch (\Exception $e) {
            Log::error('Error adding product: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Internal server error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật sản phẩm
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateProduct(Request $request, $id): JsonResponse
    {
        try {
            // Kiểm tra sản phẩm tồn tại
            $existingProduct = $this->productRepository->getById($id);
            
            if (!$existingProduct) {
                return $this->errorResponse('Product not found', 404);
            }

            // Validate dữ liệu đầu vào
            $validatedData = $this->validateProductData($request, true);
            
            // Cập nhật thông tin cơ bản
            $this->updateBasicProductInfo($existingProduct, $validatedData);
            
            // Xử lý cập nhật hình ảnh
            $this->handleImageUpdate($request, $existingProduct);

            // Cập nhật sản phẩm trong database
            $this->productRepository->update($existingProduct);
            
            // Lấy sản phẩm đã cập nhật và thêm thông tin category
            $updatedProduct = $this->productRepository->getById($id);
            $this->addCategoryToProduct($updatedProduct);

            return response()->json([
                'success' => true,
                'message' => 'Update successful',
                'data' => $updatedProduct
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Update failed: ' . $e->getMessage(), 500);
        }
    }

    private function updateBasicProductInfo(Product $product, array $data): void
    {
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->quantity = $data['quantity'] ?? 0;
        $product->description = $data['description'] ?? null;
        $product->category_id = $data['category_id'];
        $product->is_active = $data['is_active'] ?? true;
        $product->sizes = $data['sizes'] ?? null;
    }


    /**
     * Xóa sản phẩm (xóa mềm)
     */
    public function deleteProduct($id): JsonResponse
    {
        try {
            $product = $this->productRepository->getById($id);
            
            if (!$product) {
                return $this->errorResponse('Product not found', 404);
            }

            $this->productRepository->delete($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Delete successful'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Delete failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Kích hoạt lại sản phẩm đã xóa mềm
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function activateProduct(Request $request): JsonResponse
    {
        try {
            $this->productRepository->activate($request->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Product activated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error activating product: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Activation failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Lấy danh sách sản phẩm không hoạt động
     *
     * @return JsonResponse
     */
    public function getInactiveProducts(): JsonResponse
    {
        try {
            $inactiveProducts = $this->productRepository->getInactiveProducts();
            $this->enrichProductsWithData($inactiveProducts);
            
            return response()->json($inactiveProducts);
        } catch (\Exception $e) {
            Log::error('Error fetching inactive products: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Internal server error', 500);
        }
    }

    /**
     * Xác thực dữ liệu sản phẩm
     */
    private function validateProductData(Request $request, bool $isUpdate = false): array
    {
        $rules = [
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0.01|max:100000000000.00',
            'quantity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'is_active' => 'boolean',
            'sizes' => 'nullable|array',
        ];
        
        // Quy tắc cho imageFile, chỉ áp dụng khi có file upload
        if ($request->hasFile('imageFile')) {
            $rules['imageFile'] = 'nullable|image|max:2048';
        }
        
        // Thêm quy tắc cho cập nhật hình ảnh
        if ($isUpdate) {
            $rules['image_url'] = 'nullable|string';
            $rules['remove_image'] = 'nullable|boolean';
        }
        
        $validated = $request->validate($rules);
        
        Log::info('Validated data', [
            'has_file' => $request->hasFile('imageFile'),
            'has_image_url' => $request->has('image_url'),
            'image_url' => $request->input('image_url'),
            'content_type' => $request->header('Content-Type')
        ]);
        
        return $validated;
    }

    // /**
    //  * Thêm thông tin bổ sung vào danh sách sản phẩm
    //  *
    //  * @param \Illuminate\Support\Collection $products
    //  */
    private function enrichProductsWithData($products): void
    {
        foreach ($products as $product) {
            // Gán giá trị mặc định
            $product->sizes = $product->sizes ?? [];
            $product->quantity = $product->quantity ?? 0;
            
            // Thêm thông tin category
            $this->addCategoryToProduct($product);
        }
    }

    
    private function addCategoryToProduct($product): void
    {
        if ($product->category_id != 0) {
            $category = $this->categoryRepository->getById($product->category_id);
            
            if ($category) {
                $product->category = $category;
                unset($product->category->products); // Tránh chu kỳ tham chiếu
            }
        }
    }
        

    //phân trang
    private function paginateCollection($collection, $pageNumber, $pageSize)
    {
        return $collection->skip(($pageNumber - 1) * $pageSize)
                        ->take($pageSize)
                        ->values();
    }

    // xu ly cap nhat hinh anh
    private function handleImageUpdate(Request $request, Product $product): void
    {
        $oldImageUrl = $product->image_url;
        
        Log::info('Image update request data', [
            'has_file' => $request->hasFile('imageFile'),
            'file_valid' => $request->hasFile('imageFile') ? $request->file('imageFile')->isValid() : 'N/A',
            'has_image_url' => $request->has('image_url'),
            'image_url' => $request->input('image_url'),
            'remove_image' => $request->input('remove_image'),
            'old_image_url' => $oldImageUrl
        ]);
        
        // Trường hợp 1: Có file mới upload
        if ($request->hasFile('imageFile') && $request->file('imageFile')->isValid()) {
            $this->handleImageFileUpload($request, $product, $oldImageUrl);
        }
        // Trường hợp 2: Cập nhật bằng URL hình ảnh mới
        // Đảm bảo image_url tồn tại, không null và khác với URL cũ
        else if ($request->has('image_url') && $request->input('image_url') !== null && $request->input('image_url') !== $oldImageUrl) {
            $this->handleImageUrlUpdate($request, $product, $oldImageUrl);
        }
        // Trường hợp 3: Yêu cầu xóa hình ảnh
        else if ($request->has('remove_image') && $request->boolean('remove_image') === true) {
            $this->handleImageRemoval($product, $oldImageUrl);
        }
        // Trường hợp 4: Giữ nguyên ảnh cũ
        else {
            Log::info('Keeping existing image', ['path' => $oldImageUrl]);
        }
    }

    // sử lý file ảnh
    private function handleImageFileUpload(Request $request, Product $product, $oldImageUrl): void
    {
        // Xóa file ảnh cũ nếu có
        $this->deleteOldImageIfExists($oldImageUrl);
        
        // Lưu file ảnh mới
        $this->ensureDirectoryExists();
        $product->image_url = $this->saveImage($request->file('imageFile'));
        
        Log::info('New image saved from file', [
            'old_path' => $oldImageUrl,
            'new_path' => $product->image_url
        ]);
    }

    /**
     * Xử lý cập nhật URL ảnh mới
     *
     * @param Request $request
     * @param Product $product
     * @param string|null $oldImageUrl
     */
    // private function handleImageUrlUpdate(Request $request, Product $product, $oldImageUrl): void
    // {
    //     // Xóa file ảnh cũ nếu có
    //     if (str_contains($oldImageUrl ?? '', '/' . self::IMAGE_DIRECTORY . '/')) {
    //         $this->deleteOldImageIfExists($oldImageUrl);
    //     }
        
    //     // Cập nhật URL mới
    //     $product->image_url = $request->input('image_url');
        
    //     Log::info('Image URL updated', [
    //         'old_path' => $oldImageUrl,
    //         'new_path' => $product->image_url
    //     ]);
    // }

    /**
     * Xử lý xóa hình ảnh
     *
     * @param Product $product
     * @param string|null $oldImageUrl
     */
    // private function handleImageRemoval(Product $product, $oldImageUrl): void
    // {
    //     // Xóa file ảnh cũ nếu có
    //     $this->deleteOldImageIfExists($oldImageUrl);
        
    //     // Đặt image_url về null
    //     $product->image_url = null;
        
    //     Log::info('Image removed', ['path' => $oldImageUrl]);
    // }

    /**
     * Xóa file ảnh cũ nếu tồn tại
     *
     * @param string|null $imageUrl
     * @return bool
     */
    private function deleteOldImageIfExists($imageUrl): bool
    {
        if ($imageUrl && str_contains($imageUrl, '/' . self::IMAGE_DIRECTORY . '/') && file_exists(public_path($imageUrl))) {
            File::delete(public_path($imageUrl));
            Log::info('Old image deleted', ['path' => $imageUrl]);
            return true;
        }
        return false;
    }
    
    /**
     * Lưu hình ảnh vào public/productImages
     *
     * @param \Illuminate\Http\UploadedFile $imageFile
     * @return string|null
     */
    private function saveImage($imageFile): ?string
    {
        try {
            // Kiểm tra file
            if (!$imageFile || !$imageFile->isValid()) {
                Log::error('Invalid image file');
                return null;
            }

            // Log thông tin file
            Log::info('Processing image', [
                'name' => $imageFile->getClientOriginalName(),
                'size' => $imageFile->getSize(),
                'mime' => $imageFile->getMimeType()
            ]);

            // Đảm bảo thư mục tồn tại
            $this->ensureDirectoryExists();
            
            // Tạo tên file ngẫu nhiên để tránh trùng lặp
            $fileName = uniqid() . '_' . $imageFile->getClientOriginalName();
            
            // Tạo đường dẫn đầy đủ tới thư mục lưu trữ
            $destinationPath = public_path(self::IMAGE_DIRECTORY);
            
            // Di chuyển file vào thư mục đích
            $imageFile->move($destinationPath, $fileName);
            
            // Đường dẫn tới file ảnh để lưu vào database
            $imagePath = '/' . self::IMAGE_DIRECTORY . '/' . $fileName;
            
            // Kiểm tra file đã được lưu chưa
            if (file_exists(public_path($imagePath))) {
                Log::info('Image stored successfully', ['path' => $imagePath]);
                return $imagePath;
            } else {
                Log::warning('File was not saved properly', ['expected_path' => public_path($imagePath)]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error saving image: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return null;
        }
    }
    
    /**
     * Tạo thư mục productImages nếu chưa tồn tại
     */
    private function ensureDirectoryExists(): void
    {
        $directory = public_path(self::IMAGE_DIRECTORY);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
    }
    
    private function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }
}