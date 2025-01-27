<?php

namespace App\Providers;

use App\Services\EventScheduler;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the EventScheduler service to the service container
        $this->app->singleton(EventScheduler::class, function ($app) {
            return new EventScheduler();
        });    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
    }
}
