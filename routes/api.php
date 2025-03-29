<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\SearchApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Constants\RoleConstants;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route cho image
Route::get('/productImages/{filename}', function ($filename) {
    $path = public_path('productImages/' . $filename);
    
    if (!file_exists($path)) {
        return response()->json(['error' => 'File not found'], 404);
    }
    
    // Lấy dữ liệu file và mime type
    $file = File::get($path);
    $type = File::mimeType($path);
    $size = File::size($path);
    
    // Tạo response với các header quan trọng
    $response = Response::make($file, 200);
    $response->header('Content-Type', $type);
    $response->header('Content-Length', $size);
    $response->header('Cache-Control', 'public, max-age=31536000');
    $response->header('Accept-Ranges', 'bytes');
    $response->header('Connection', 'keep-alive');
    
    return $response;
})->where('filename', '.*');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ví dụ route chỉ dành cho Admin
// Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
// });

Route::prefix('products')->group(function () {
    Route::get('/getall', [ProductApiController::class, 'getAllProducts']);
    Route::get('/getproductbyid/{id}', [ProductApiController::class, 'getProductById']);
    Route::get('/getByCategory', [ProductApiController::class, 'getByCategory']);
    Route::get('/countByCategory', [ProductApiController::class, 'countByCategory']);
    Route::middleware(['auth:sanctum', 'role:' . RoleConstants::ROLE_ADMIN . ',' . RoleConstants::ROLE_EMPLOYEE])->group(function () {
        Route::post('/add', [ProductApiController::class, 'addProduct']);
        Route::put('/update/{id}', [ProductApiController::class, 'updateProduct']);
        Route::post('/update/{id}', [ProductApiController::class, 'updateProduct']);
        Route::delete('/delete/{id}', [ProductApiController::class, 'deleteProduct']);
        Route::post('/activate', [ProductApiController::class, 'activateProduct']);
        Route::get('/inactive', [ProductApiController::class, 'getInactiveProducts']);
    });
});

// Category API Routes
Route::prefix('categories')->group(function () {
    Route::get('/getall', [CategoryApiController::class, 'getAllCategories']);
    Route::get('/getbyId', [CategoryApiController::class, 'getCategoryById']);
     // Protected routes (Admin & Employee only)
    Route::middleware(['auth:sanctum', 'role:' . RoleConstants::ROLE_ADMIN . ',' . RoleConstants::ROLE_EMPLOYEE])->group(function () {
        Route::post('/add', [CategoryApiController::class, 'addCategory']);
        Route::put('/update', [CategoryApiController::class, 'updateCategory']);
        Route::delete('/delete', [CategoryApiController::class, 'deleteCategory']);
    });
});

// Route cho Event
Route::prefix('events')->group(function () {
    Route::get('/getall', [EventController::class, 'getAllEvents']);
    Route::get('/getbyId/{id}', [EventController::class, 'getEventById']);
   // Protected routes (Admin & Employee only)
    Route::middleware(['auth:sanctum', 'role:' . RoleConstants::ROLE_ADMIN . ',' . RoleConstants::ROLE_EMPLOYEE])->group(function () {
        Route::post('/add', [EventController::class, 'addEvent']);
        Route::match(['put', 'post'], '/update/{id}', [EventController::class, 'updateEvent']);
        Route::delete('/delete/{id}', [EventController::class, 'deleteEvent']);
    });
});

// Search Routes
Route::get('/search', [SearchApiController::class, 'search']);
Route::get('/suggest', [SearchApiController::class, 'suggest']);

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/login', [AuthApiController::class, 'login']);
    
    // Các route cần xác thực
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthApiController::class, 'logout']);
        Route::get('/user', [AuthApiController::class, 'user']);
    });
});


//admin api route
Route::prefix('admin')->group(function () {
    Route::get('/getall', [AdminApiController::class, 'getUsers'])->name('getall.users');
    Route::post('/ban/{userId}', [AdminApiController::class, 'banUser'])->name('ban.user');
    Route::post('/unban/{userId}', [AdminApiController::class, 'unbanUser'])->name('unban.user');
});