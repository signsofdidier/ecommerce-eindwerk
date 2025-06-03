<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\SetTenant;
use App\Http\Middleware\EnsureTenantUserIsValid;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Alias 'set.tenant' voor SetTenant‐middleware
        $this->app['router']->aliasMiddleware('set.tenant', SetTenant::class);

        // Alias 'ensure.tenant.user' voor EnsureTenantUserIsValid‐middleware
        $this->app['router']->aliasMiddleware('ensure.tenant.user', EnsureTenantUserIsValid::class);
    }

    public function boot(): void
    {
        //
    }
}
