<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;


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
            // API rate limiting (60 requests per minute per user or IP)
            RateLimiter::for('api', function ($request) {
                return Limit::perMinute(60)->by(
                    optional($request->user())->id ?: $request->ip()
                );
            });
        }
}
