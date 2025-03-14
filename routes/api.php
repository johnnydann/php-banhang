<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\EventController;

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

Route::prefix('products')->group(function () {
    Route::get('/getall', [ProductApiController::class, 'getAllProducts']);
    Route::get('/getproductbyid/{id}', [ProductApiController::class, 'getProductById']);
    Route::post('/add', [ProductApiController::class, 'addProduct']);
    Route::put('/update/{id}', [ProductApiController::class, 'updateProduct']);
    Route::post('/update/{id}', [ProductApiController::class, 'updateProduct']);
    Route::delete('/delete/{id}', [ProductApiController::class, 'deleteProduct']);
    Route::post('/activate', [ProductApiController::class, 'activateProduct']);
    Route::get('/inactive', [ProductApiController::class, 'getInactiveProducts']);
});

// Category API Routes
Route::prefix('categories')->group(function () {
    Route::get('/getall', [CategoryApiController::class, 'getAllCategories']);
    Route::get('/getbyId', [CategoryApiController::class, 'getCategoryById']);
    Route::post('/add', [CategoryApiController::class, 'addCategory']);
    Route::put('/update', [CategoryApiController::class, 'updateCategory']);
    Route::delete('/delete', [CategoryApiController::class, 'deleteCategory']);
});

// Route cho Event
Route::prefix('events')->group(function () {
    Route::get('/getall', [EventController::class, 'getAllEvents']);
    Route::get('/getbyId/{id}', [EventController::class, 'getEventById']);
    Route::post('/add', [EventController::class, 'addEvent']);
    Route::put('/update/{id}', [EventController::class, 'updateEvent']);
    Route::post('/update/{id}', [EventController::class, 'updateEvent']);
    Route::delete('/delete/{id}', [EventController::class, 'deleteEvent']);
});