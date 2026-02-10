<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\Auth\AuthController;
use App\Http\Controllers\Frontend\Auth\GoogleController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop page (all products with filters)
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/product/{slug}', [ShopController::class, 'show'])
    ->name('product.show');

Route::get('/shop/filter', [ShopController::class, 'filter'])->name('shop.filter');

// Category page (products filtered by category)
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category.show');

// ============================================
// CART ROUTES (Guest + Authenticated)
// ============================================
Route::prefix('cart')->name('cart.')->group(function () {
    Route::post('add', [CartController::class, 'add'])->name('add');
    Route::post('remove', [CartController::class, 'remove'])->name('remove');
    Route::post('update', [CartController::class, 'update'])->name('update');
    Route::post('clear', [CartController::class, 'clear'])->name('clear');
    Route::get('count', [CartController::class, 'count'])->name('count');
    Route::get('get', [CartController::class, 'get'])->name('get');

    // Cart page
    Route::get('/', [CartController::class, 'index'])->name('index');
});

// ============================================
// WISHLIST ROUTES (Guest + Authenticated)
// ============================================
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::post('toggle', [WishlistController::class, 'toggle'])->name('toggle');
    Route::post('add', [WishlistController::class, 'add'])->name('add');
    Route::post('remove', [WishlistController::class, 'remove'])->name('remove');
    Route::get('count', [WishlistController::class, 'count'])->name('count');
    Route::get('get', [WishlistController::class, 'get'])->name('get');

    // Wishlist page
    Route::get('/', [WishlistController::class, 'index'])->name('index');
});

// Guest routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    // Register
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    // Google OAuth
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

// Logout (authenticated users)
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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
