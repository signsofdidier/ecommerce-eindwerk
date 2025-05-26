<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welkom bij tenant: ' . tenant('id');
});


