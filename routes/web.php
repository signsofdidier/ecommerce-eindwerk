<?php

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
use Illuminate\Support\Facades\Route;

// Tenant-specifieke routes
Route::prefix('{company:slug}')->middleware('tenant')->group(function() {
    Route::get('/', HomePage::class)->name('company.home');
    Route::get('/categories', CategoriesPage::class)->name('company.categories');
    Route::get('/products', ProductsPage::class)->name('company.products');
    Route::get('/products/{slug}', ProductDetailPage::class)->name('company.product.detail');
    Route::get('/cart', CartPage::class)->name('company.cart');

    // Login/register routes
    Route::middleware('guest')->group(function() {
        Route::get('/login', LoginPage::class)->name('company.login');
        Route::get('/register', RegisterPage::class)->name('company.register');
        Route::get('/forgot', ForgotPasswordPage::class)->name('company.password.request');
        Route::get('/reset/{token}', ResetPasswordPage::class)->name('company.password.reset');
    });

    // Auth routes
    Route::middleware('auth')->group(function() {
        Route::get('/logout', function() {
            auth()->logout();
            $companySlug = request()->route('company');
            return redirect("/$companySlug");
        })->name('company.logout');
        Route::get('/checkout', CheckoutPage::class)->name('company.checkout');
        Route::get('/my-orders', MyOrdersPage::class)->name('company.my-orders');
        Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('company.my-orders.show');
        Route::get('/success', SuccessPage::class)->name('company.success');
        Route::get('/cancel', CancelPage::class)->name('company.cancel');
    });
});

// Behoud de bestaande routes (optioneel)
// openbare routes voor alle users (standaard zonder tenant)
/*Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/products', ProductsPage::class);
Route::get('/cart', CartPage::class);
Route::get('/products/{slug}', ProductDetailPage::class);*/

