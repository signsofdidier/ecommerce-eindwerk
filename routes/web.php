<?php

use App\Http\Middleware\setCurrentCompany;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// openbare routes voor alle users
Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/products', ProductsPage::class);
Route::get('/cart', CartPage::class);
Route::get('/products/{slug}', ProductDetailPage::class);

// openbare routes voor niet ingelogde users
Route::middleware('guest')->group(function() {
    Route::get('/login', LoginPage::class)->name('login');
    Route::get('/register', RegisterPage::class);
    Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
});

// enkel voor ingelogde users
Route::middleware('auth')->group(function() {
    // navbar logout knop voor ingelogde user
    Route::get('/logout', function() {
       auth()->logout();
       return redirect('/');
    });
    Route::get('/checkout', CheckoutPage::class);
    Route::get('/my-orders', MyOrdersPage::class);
    Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');
    Route::get('/success', SuccessPage::class)->name('success');
    Route::get('/cancel', CancelPage::class)->name('cancel');
});

// TENANT route
Route::middleware('web')->group(function () {
    Route::prefix('{companySlug}')
        ->middleware(SetCurrentCompany::class)
        ->group(function () {
            Route::get('/', HomePage::class);
            Route::get('/products', ProductsPage::class);
        });
});
