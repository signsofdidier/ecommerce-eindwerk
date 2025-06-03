<?php

use Illuminate\Support\Facades\Route;

// Tenant routes alleen registreren als de helper bestaat
if (function_exists('tenancy')) {
    Route::tenancy(function () {
        require base_path('routes/tenant.php');
    })->middleware('web');
}

