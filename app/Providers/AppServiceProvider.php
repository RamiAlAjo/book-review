<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit; // Import the RateLimiting Limit class to define rate limit rules
use Illuminate\Support\ServiceProvider; // Import the base ServiceProvider class that we extend
use Illuminate\Http\Request; // Import the correct Request class to handle incoming HTTP requests
use RateLimiter; // Import the RateLimiter facade for setting rate limits

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method is called when the service provider is registered.
     * You can bind services or perform any initialization tasks here.
     * Since we don't need any additional services for now, this is left empty.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called after all the services have been registered.
     * Here, we configure application-specific functionality that needs to be
     * bootstrapped, such as rate limiting.
     */
    public function boot(): void
    {
        // Define a rate limiter for the 'reviews' resource
        RateLimiter::for('reviews', function (Request $request) {
            // Limit the number of review submissions to 3 per hour
            // We differentiate users based on their ID or IP address if they're not authenticated
            return Limit::perHour(3)->by($request->user()?->id ?: $request->ip());
        });
    }
}
