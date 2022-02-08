<?php

use App\Http\Controllers\API\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ["auth:sanctum"]], function () {
    Route::post('products', [ProductController::class, 'store'])->name('product.create');
    Route::patch('products/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('products/{id}', [ProductController::class, 'delete'])->name('product.delete');

    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('edit-user', [UserController::class, 'editUser']);
    Route::get('logout', [UserController::class, 'logout']);

    Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('sales/user', [SaleController::class, 'show'])->name('sales.show');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
});

Route::get('products', [ProductController::class, 'index'])->name('product.index');
Route::get('products/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('cart/{id}', [CartController::class, 'show'])->name('cart.show');
Route::post('cart', [CartController::class, 'store'])->name('cart.store');
Route::delete('cart/{user_id}/{product_id}', [CartController::class, 'delete'])->name('cart.delete');
