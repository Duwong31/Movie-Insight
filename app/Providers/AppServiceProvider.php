<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        $moduleViewPath = base_path('modules/User/Views');
        $this->loadViewsFrom($moduleViewPath, 'User');

        $moduleViewPath = base_path('modules/Review/Views');
        $this->loadViewsFrom($moduleViewPath, 'Review');

        $moduleViewPath = base_path('modules/Movies/Views');
        $this->loadViewsFrom($moduleViewPath, 'Movies');

        $moduleViewPath = base_path('modules/TVShows/Views');
        $this->loadViewsFrom($moduleViewPath, 'TVShows');

        $moduleViewPath = base_path('modules/Genres/Views');
        $this->loadViewsFrom($moduleViewPath, 'Genres');
    }
}
