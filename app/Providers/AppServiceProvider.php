<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\categories;
use App\Models\config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $infos = Config::first();
            $categories = categories::all();
            $brands = Brand::all();
            $view->with([
                'infos' => $infos,
                'categories' => $categories,
                'brands' => $brands
            ]);
        });
    }
}
