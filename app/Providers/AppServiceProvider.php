<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\PricingService;
use App\Services\ShippingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PricingService::class, function ($app) {
            return new PricingService();
        });

        $this->app->singleton(ShippingService::class, function ($app) {
            return new ShippingService();
        });
    }

    public function boot(): void
    {
        // ── Header: featured parent categories only ──────────────────────────
        View::composer('frontend.partials.header', function ($view) {
            $view->with('categories', Cache::remember('header_categories', 3600, function () {
                return Category::with('activeChildren')
                    ->where('is_active', 1)
                    ->where('is_featured', true)      // ← featured only for nav
                    ->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }));
        });

        // ── Shop page: ALL active parent categories ──────────────────────────
        View::composer('frontend.shop', function ($view) {
            $view->with('categories', Cache::remember('shop_categories', 3600, function () {
                return Category::with('activeChildren')
                    ->where('is_active', 1)
                    ->whereNull('parent_id')          // ← no is_featured filter
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }));

            $view->with('brands', Cache::remember('active_brands', 3600, function () {
                return \App\Models\Brand::where('is_active', 1)->orderBy('name')->get();
            }));
        });
    }

}
