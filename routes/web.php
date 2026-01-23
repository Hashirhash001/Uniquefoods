<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop page (all products with filters)
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

// Product details
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');
Route::get('/shop/filter', [ShopController::class, 'filter'])->name('shop.filter');

// Category page (products filtered by category)
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category.show');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [LoginController::class, 'showLogin'])
        ->middleware('guest')
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('guest')
        ->name('login.submit');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // products
        Route::resource('products', ProductController::class);

        // Categories
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');

        // brands
        Route::resource('brands', BrandController::class)->except(['show']);
        Route::post('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');

        // Banners
        Route::resource('banners', BannerController::class)->except(['show']);
        Route::post('banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])
            ->name('banners.toggle-status');
    });

});
