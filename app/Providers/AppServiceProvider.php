<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\City;
use Illuminate\Support\Facades\Schema;

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
        try {
            if (Schema::hasTable('cities')) {
                View::share('cities', City::orderBy('name')->get() ?? collect([]));
            } else {
                View::share('cities', collect([]));
            }
        } catch (\Exception $e) {
            View::share('cities', collect([]));
        }
    }
}
