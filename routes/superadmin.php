<?php
// routes/superadmin.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SuperAdmin\TenantController;

/*
|--------------------------------------------------------------------------
| Superadmin-routes op hoofddomein (localhost)
|--------------------------------------------------------------------------
|
| Dit bestand bevat alle routes voor de superadmin (die draaien op
| http://localhost/... en geen subdomein vereisen). Hier kun je inloggen,
| tenants aanmaken/bewerken/verwijderen.
|
*/

// 1) Superadmin-login/logout (zonder tenant‐middleware)
Route::get('superadmin/login',  [AuthenticatedSessionController::class, 'create'])
    ->name('superadmin.login');
Route::post('superadmin/login', [AuthenticatedSessionController::class, 'store']);
Route::post('superadmin/logout',[AuthenticatedSessionController::class, 'destroy'])
    ->name('superadmin.logout');

// 2) Beschermde superadmin-routes (auth + Gate)
Route::middleware([
    'auth',
    'can:viewSuperAdmin', // Gate die je definieert in AuthServiceProvider: user->is_super_admin
])->prefix('superadmin')->group(function () {
    // Dashboard: lijst van alle tenants
    Route::get('/', [TenantController::class, 'index'])
        ->name('superadmin.dashboard');

    // CRUD voor Tenant-model
    Route::resource('tenants', TenantController::class, [
        'as'         => 'superadmin',
        'parameters' => ['tenants' => 'tenant'],
    ])->except(['show']);
});
