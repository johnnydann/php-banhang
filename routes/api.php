<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AuthApiController;
use App\Constants\RoleConstants;

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