<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShoppingCartController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login'); // Trang login của admin
})->name('login');


Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::get('/products', function () {
        return view('admin.product');
    });

    Route::get('/categories', function () {
        return view('admin.category');
    });

    Route::get('/statistics', function () {
        return view('admin.statistics');
    });

    Route::get('/users', function () {
        return view('admin.users');
    });

    Route::get('/events', function () {
        return view('admin.events');
    });

});


