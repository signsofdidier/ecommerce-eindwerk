<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

Route::middleware([
    'web',
    InitializeTenancyByPath::class, // NIET tenancy of domain
])
    ->group(function () {
        Route::get('/', function () {
            return 'Welkom bij tenant: ' . tenant('id');
        });
    });
