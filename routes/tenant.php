<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'CENTRALE APP';
});

// DIT is de tenantgroep:
Route::middleware([
    'web',
    \Stancl\Tenancy\Middleware\InitializeTenancyByPath::class,
])
    ->prefix('{tenant}')
    ->group(function () {
        Route::get('/', function () {
            return 'Welkom bij tenant: ' . tenant('id');
        });
    });
