<?php

use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/dashboard', function () {
        return view('dashboard');
    })
    ->name('dashboard');

Route::prefix('/')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::resource('users', UserController::class);
        Route::prefix('vendors')->group(function() {
            Route::get('import', [VendorController::class, 'import'])->name('vendors.import');
        });
        Route::resource('vendors', VendorController::class);
        Route::resource('offers', OfferController::class)->names('offers');
        Route::get('offers/{offer}/sendToLexoffice', [OfferController::class, 'sendToLexoffice'])->name('offer.send-to-lexoffice');
        Route::prefix('products')->group(function() {
            Route::get('import', [ProductController::class, 'import'])->name('products.import');
            Route::post('import', [ProductController::class, 'processImport'])->name('products.process');
            Route::get('assignToOffer/{offer}/{vendorProduct}', [ProductController::class, 'assignToOffer'])->name('product.assign-to-offer');
        });
        Route::resource('products', ProductController::class);
        Route::resource('vendor-products', VendorProductController::class);
    });
