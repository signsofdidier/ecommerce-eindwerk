<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

// Laravel-global middleware
use App\Http\Middleware\TrustHosts;
use App\Http\Middleware\TrustProxies;
use Fruitcake\Cors\HandleCors;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;

// Web-middleware groep
use App\Http\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;

// API-middleware groep
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\SubstituteBindings as ApiSubstituteBindings;

// Route-middleware (eigen)
use App\Http\Middleware\SetCurrentCompany;
use App\Http\Middleware\EnsureTenantAdmin;
use App\Http\Middleware\EnsureSuperAdmin;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware die altijd op elk request lopen.
     */
    protected $middleware = [
        // Controleert welke hosts/domains vertrouwd zijn
        TrustHosts::class,

        // Stelt de proxi atiers in (bv. als je achter Cloudflare of Load Balancer zit)
        TrustProxies::class,

        // CORS‐headers verwerken
        HandleCors::class,

        // Prevent requests during maintenance (geplande downtime)
        PreventRequestsDuringMaintenance::class,

        // Valideer de maximale post‐size (file uploads, forms)
        ValidatePostSize::class,

        // Trimt alle ingekomen strings
        TrimStrings::class,

        // Zet lege strings om naar null
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * De middleware‐groepen.
     *
     * - 'web' bevat alle middleware die je normaal voor web‐routes gebruikt:
     *   sessions, CSRF, cookie‐encryptie, enzovoort.
     *
     * - 'api' bevat middleware voor API‐routes, zoals rate limiting en binding.
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            ApiSubstituteBindings::class,
        ],
    ];

    /**
     * De individuele route‐middleware.
     *
     * Hiermee kun je in routes/web.php bijvoorbeeld:
     *     ->middleware('set.company')
     * of  ->middleware('auth')
     * specificeren.
     */
    protected $routeMiddleware = [
        'auth'                  => \App\Http\Middleware\Authenticate::class,
        'auth.basic'            => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers'         => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'                   => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'                 => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm'      => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed'                => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'              => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'              => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Onze custom middleware voor multi-tenancy:
        'set.company'           => SetCurrentCompany::class,
        'ensure.tenant.admin'   => EnsureTenantAdmin::class,
        'ensure.superadmin'     => EnsureSuperAdmin::class,
    ];
}
