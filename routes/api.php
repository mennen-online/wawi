<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserOffersController;
use App\Http\Controllers\Api\VendorProductController;
use App\Http\Controllers\Api\VendorProductOffersController;
use App\Http\Controllers\Api\VendorVendorProductsController;
use App\Http\Controllers\Api\ProductVendorProductsController;

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

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('users', UserController::class);

        // User Offers
        Route::get('/users/{user}/offers', [
            UserOffersController::class,
            'index',
        ])->name('users.offers.index');
        Route::post('/users/{user}/offers', [
            UserOffersController::class,
            'store',
        ])->name('users.offers.store');

        Route::apiResource('vendors', VendorController::class);

        // Vendor Vendor Products
        Route::get('/vendors/{vendor}/vendor-products', [
            VendorVendorProductsController::class,
            'index',
        ])->name('vendors.vendor-products.index');
        Route::post('/vendors/{vendor}/vendor-products', [
            VendorVendorProductsController::class,
            'store',
        ])->name('vendors.vendor-products.store');

        Route::apiResource('products', ProductController::class);

        // Product Vendor Products
        Route::get('/products/{product}/vendor-products', [
            ProductVendorProductsController::class,
            'index',
        ])->name('products.vendor-products.index');
        Route::post('/products/{product}/vendor-products', [
            ProductVendorProductsController::class,
            'store',
        ])->name('products.vendor-products.store');

        Route::apiResource('vendor-products', VendorProductController::class);

        // VendorProduct Offers
        Route::get('/vendor-products/{vendorProduct}/offers', [
            VendorProductOffersController::class,
            'index',
        ])->name('vendor-products.offers.index');
        Route::post('/vendor-products/{vendorProduct}/offers/{offer}', [
            VendorProductOffersController::class,
            'store',
        ])->name('vendor-products.offers.store');
        Route::delete('/vendor-products/{vendorProduct}/offers/{offer}', [
            VendorProductOffersController::class,
            'destroy',
        ])->name('vendor-products.offers.destroy');
    });
