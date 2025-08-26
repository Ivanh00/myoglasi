<?php

namespace App\Providers;

use App\Services\CategoryService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->singleton(\App\Services\CategoryService::class, function ($app) {
        //     return new \App\Services\CategoryService();
        // });
        $this->app->bind(\App\Services\CategoryService::class, function ($app) {
            return new \App\Services\CategoryService();
        });
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('year', date('Y'));

        view()->composer('*', function ($view) {
            if (!app()->runningInConsole()) {
                $view->with('categories', \App\Models\Category::whereNull('parent_id')->get());
                $view->with('conditions', \App\Models\ListingCondition::all());
            }
        });
    }
}
