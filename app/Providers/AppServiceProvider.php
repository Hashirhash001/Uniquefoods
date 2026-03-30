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
        // Shared categories loader (reusable)
        $getCategories = function () {
            return Cache::remember('header_categories', 3600, function () {
                return Category::with('activeChildren')
                    ->where('is_active', 1)
                    ->whereNull('parent_id')
                    ->orderBy('name')
                    ->take(10)
                    ->get();
            });
        };

        // Share with header
        View::composer('frontend.partials.header', function ($view) use ($getCategories) {
            $view->with('categories', $getCategories());
        });

        // Share with shop page too
        View::composer('frontend.shop', function ($view) use ($getCategories) {
            $brands = Cache::remember('active_brands', 3600, function () {
                return \App\Models\Brand::where('is_active', 1)->orderBy('name')->get();
            });
            $view->with('categories', $getCategories());
            $view->with('brands', $brands);
        });
    }

}
