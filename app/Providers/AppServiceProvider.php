<?php

namespace App\Providers;

use App\Http\Middleware\SetFrontendCompany;
use App\Models\Company;
use App\Observers\CompanyObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Route;
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
        Company::observe(CompanyObserver::class);
    }
}
