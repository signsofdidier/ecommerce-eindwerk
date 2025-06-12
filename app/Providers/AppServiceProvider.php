<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Filament::serving(function () {
            // Zorgt dat Filament 'weet' dat je in een tenant context zit
            if (tenancy()->initialized) {
                Filament::registerTheme(mix('/css/app.css')); // optioneel
            }
        });
    }
}
