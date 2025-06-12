<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomePage;

Route::get('/', HomePage::class); // Hoofdsite homepage

// eventueel: superadmin login
Route::get('/admin-login', \App\Livewire\Auth\LoginPage::class)->name('admin.login');
