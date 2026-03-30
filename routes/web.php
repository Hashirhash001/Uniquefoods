<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerGroupController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GroupPricingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Frontend\Auth\AuthController;
use App\Http\Controllers\Frontend\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\Auth\GoogleController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');
Route::get('/shop/filter', [ShopController::class, 'filter'])->name('shop.filter');
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
    Route::get('/', [WishlistController::class, 'index'])->name('index');
});


// ============================================
// EMAIL VERIFICATION ROUTES
// ============================================
Route::middleware('auth')->group(function () {

    // Show "please verify your email" notice
    Route::get('/email/verify', function () {
        return view('frontend.auth.verify-email');
    })->name('verification.notice');

    // Handle verification link click
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home')->with('success', 'Email verified successfully! Welcome to Unique Foods.');
    })->middleware('signed')->name('verification.verify');

    // Resend verification email
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent! Please check your inbox.');
    })->middleware('throttle:6,1')->name('verification.send');

});


// ============================================
// CHECKOUT & ORDERS (Auth + Verified Required)
// ============================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/create-payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('checkout.create-payment-intent');
    Route::post('/checkout/shipping-estimate', [CheckoutController::class, 'shippingEstimate'])
    ->name('checkout.shipping.estimate');
    Route::post('/checkout/process', [CheckoutController::class, 'processOrder'])->name('checkout.process');
    Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [CheckoutController::class, 'orderDetails'])->name('orders.details');
    Route::post('/orders/{orderNumber}/cancel', [CheckoutController::class, 'cancelOrder'])
    ->name('orders.cancel');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // ── Address routes (named now) ──
    Route::get('/account/addresses', [CheckoutController::class, 'addressBook'])->name('account.addresses');
    Route::get('/account/addresses/json', [CheckoutController::class, 'getSavedAddresses'])->name('account.addresses.json');
    Route::post('/account/addresses', [CheckoutController::class, 'storeAddress'])->name('account.addresses.store');
    Route::put('/account/addresses/{id}', [CheckoutController::class, 'updateAddress'])->name('account.addresses.update');
    Route::delete('/account/addresses/{id}', [CheckoutController::class, 'deleteAddress'])->name('account.addresses.delete');
    Route::post('/account/addresses/{id}/default', [CheckoutController::class, 'setDefaultAddress'])->name('account.addresses.default');

    Route::get('/account/profile', [ProfileController::class, 'index'])->name('account.profile');
    Route::put('/account/profile', [ProfileController::class, 'update'])->name('account.profile.update');
    Route::put('/account/profile/password', [ProfileController::class, 'updatePassword'])->name('account.profile.password');
    Route::post('/account/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('account.profile.avatar');
    Route::delete('/account/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('account.profile.avatar.remove');

    // Profile OTP routes
    Route::post('/account/profile/send-change-email-otp', [ProfileController::class, 'sendEmailChangeOtp'])->name('account.profile.email.otp');
    Route::post('/account/profile/verify-change-email-otp', [ProfileController::class, 'verifyEmailChangeOtp'])->name('account.profile.email.verify');
    Route::delete('/account/profile/delete', [ProfileController::class, 'deleteAccount'])->name('account.profile.delete');
});


// ============================================
// GUEST ROUTES (Login / Register)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:3,1');

    Route::post('/register/send-otp', [AuthController::class, 'sendOtp'])->name('register.send-otp')->middleware('throttle:3,1');
    Route::post('/register/verify-otp', [AuthController::class, 'verifyOtp'])->name('register.verify-otp');

    // Forgot Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.send-otp')->middleware('throttle:3,1');
    Route::post('forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify-otp');
    Route::post('forgot-password/reset', [ForgotPasswordController::class, 'reset'])->name('password.reset');

    // Google OAuth
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])
        ->name('auth.google')
        ->middleware('throttle:10,1');

    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])
        ->middleware('throttle:10,1');
});


// Logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [LoginController::class, 'showLogin'])
        ->middleware('guest')
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('guest')
        ->name('login.submit');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])
            ->name('dashboard.stats');

        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Products
        Route::resource('products', ProductController::class);

        // Categories
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');

        // Brands
        Route::resource('brands', BrandController::class)->except(['show']);
        Route::post('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])
            ->name('brands.toggle-status');

        // Banners
        Route::resource('banners', BannerController::class)->except(['show']);
        Route::post('banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])
            ->name('banners.toggle-status');

        // Customer Groups
        Route::resource('customer-groups', CustomerGroupController::class)->except(['show']);
        Route::post('customer-groups/{customerGroup}/toggle-status', [CustomerGroupController::class, 'toggleStatus'])
            ->name('customer-groups.toggle-status');

        // Group Pricing & Discounts
        Route::prefix('customer-groups/{customerGroup}')->name('customer-groups.')->group(function () {
            Route::get('discounts', [GroupPricingController::class, 'groupDiscounts'])->name('discounts');
            Route::post('discounts', [GroupPricingController::class, 'storeGroupDiscount'])->name('discounts.store');

            Route::get('product-prices', [GroupPricingController::class, 'productPrices'])->name('product-prices');
            Route::post('product-prices', [GroupPricingController::class, 'storeProductPrice'])->name('product-prices.store');

            Route::get('product-offers', [GroupPricingController::class, 'productOffers'])->name('product-offers');
            Route::post('product-offers', [GroupPricingController::class, 'storeProductOffer'])->name('product-offers.store');

            Route::get('overview', [CustomerGroupController::class, 'overview'])->name('overview');
        });

        Route::delete('group-discounts/{discount}', [GroupPricingController::class, 'destroyGroupDiscount'])
            ->name('group-discounts.destroy');
        Route::post('group-discounts/{discount}/toggle', [GroupPricingController::class, 'toggleGroupDiscount'])
            ->name('group-discounts.toggle');

        Route::delete('product-group-prices/{price}', [GroupPricingController::class, 'destroyProductPrice'])
            ->name('product-group-prices.destroy');

        Route::delete('product-offers/{offer}', [GroupPricingController::class, 'destroyProductOffer'])
            ->name('product-offers.destroy');

        // Orders Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
            Route::put('/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('update-payment-status');
            Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('bulk-delete');
            Route::get('/export/csv', [OrderController::class, 'export'])->name('export');
            Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
        });

        Route::get('shipping',        [ShippingController::class, 'index'])->name('shipping.index');
        Route::post('shipping/update',[ShippingController::class, 'update'])->name('shipping.update');

        // Customers
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/',              [CustomerController::class, 'index'])->name('index');
            Route::post('/',             [CustomerController::class, 'store'])->name('store');
            Route::get('/{user}/edit',   [CustomerController::class, 'edit'])->name('edit');
            Route::put('/{user}',        [CustomerController::class, 'update'])->name('update');
            Route::delete('/{user}',     [CustomerController::class, 'destroy'])->name('destroy');
            Route::put('/{user}/groups', [CustomerController::class, 'updateGroups'])->name('update-groups');
            Route::get('/{user}',        [CustomerController::class, 'show'])->name('show');
        });

    });
});
