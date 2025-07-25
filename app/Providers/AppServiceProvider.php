<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;



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
 
public function boot()
{
    view()->composer('*', function ($view) {
        if (Auth::check()) {
            $expiresAt = now()->addMinutes(5);
            Cache::put('user-is-online-' . Auth::id(), true, $expiresAt);
        }
    });
}
}
