<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/superadmin', function () {
        return 'Welkom superadmin!';
    });

    // andere globale (niet-tenant) routes hier
});
