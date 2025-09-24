<?php

namespace Modules\Actors\Providers;

use Illuminate\Support\ServiceProvider;

class ActorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register views
        $this->loadViewsFrom(__DIR__ . '/../Views', 'Actors');
    }
}
