<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Services\PricingService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PricingService::class, function ($app) {
            return new PricingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('frontend.partials.header', function ($view) {
            $categories = Category::where('is_active', 1)
                ->whereNull('parent_id')
                ->orderBy('name')
                ->take(10)
                ->get();

            $view->with('categories', $categories);
        });

    }
}
