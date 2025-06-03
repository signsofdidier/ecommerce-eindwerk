<?php
// routes/superadmin.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Superadmin-routes (zonder tenant-middleware)
|--------------------------------------------------------------------------
|
| Dit bestand bevat ALLE routes die draaien op het hoofddomein (bv. http://localhost)
| en waarmee je als superadmin tenants kunt beheren. Ze draaien NIET onder
| de “set.tenant” middleware.
|
*/

// 1) Superadmin-login/logout (hoofddomein, zonder subdomein)
Route::get('superadmin/login', [AuthenticatedSessionController::class, 'create'])
    ->name('superadmin.login');
Route::post('superadmin/login', [AuthenticatedSessionController::class, 'store']);
Route::post('superadmin/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('superadmin.logout');

// 2) Routes voor ingelogde superadmin-gebruikers
Route::middleware([
    'auth',               // Standaard Laravel-authenticatie
    'can:viewSuperAdmin', // Gate in AuthServiceProvider (user->isSuperAdmin())
])->prefix('superadmin')->group(function () {
    // Dashboard: lijst van alle tenants
    Route::get('/', [TenantController::class, 'index'])
        ->name('superadmin.dashboard');

    // CRUD‐operaties voor Tenant‐model
    Route::resource('tenants', TenantController::class, [
        'as' => 'superadmin',
        'parameters' => ['tenants' => 'tenant'],
    ])->except(['show']);
});
