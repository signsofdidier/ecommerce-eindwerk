<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
    // We laden enkel 'routes/web.php'.
    // Het superadmin-gedeelte is voor nu even uitgeschakeld, dus we vermelden 'superadmin.php' niet hier.
        web: __DIR__ . '/../routes/web.php',

        // Artisan-commands en health-endpoint zoals voorheen
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
