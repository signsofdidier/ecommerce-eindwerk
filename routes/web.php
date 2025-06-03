<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;
use App\Http\Middleware\EnsureTenantUserIsValid;
use App\Http\Middleware\SetTenant;
use App\Http\Livewire\Auth\ForgotPasswordPage;
use App\Http\Livewire\Auth\LoginPage;
use App\Http\Livewire\Auth\RegisterPage;
use App\Http\Livewire\Auth\ResetPasswordPage;
use App\Http\Livewire\CancelPage;
use App\Http\Livewire\CartPage;
use App\Http\Livewire\CategoriesPage;
use App\Http\Livewire\CheckoutPage;
use App\Http\Livewire\HomePage;
use App\Http\Livewire\MyOrderDetailPage;
use App\Http\Livewire\MyOrdersPage;
use App\Http\Livewire\ProductDetailPage;
use App\Http\Livewire\ProductsPage;
use App\Http\Livewire\SuccessPage;

/*
|--------------------------------------------------------------------------
| Tenant-routes (wél subdomein en tenant-middleware)
|--------------------------------------------------------------------------
|
| ALLE “tenant‐gebonden” routes (Livewire‐frontend en Filament‐admin)
| draaien onder de middleware‐groep [web, set.tenant, ensure.tenant.user].
| Daardoor binden we, op basis van het subdomein (bv. bedrijf1.localhost),
| automatisch de juiste Tenant en filteren we al je Eloquent‐queries
| op tenant_id = currentTenant->id.
|
*/

Route::middleware(['web', 'set.tenant', 'ensure.tenant.user'])->group(function () {
    //
    // 1) Openbare Livewire‐routes voor de webshop (per tenant)
    //
    Route::get('/', HomePage::class)->name('tenant.home');
    Route::get('/categories', CategoriesPage::class)->name('tenant.categories');
    Route::get('/products', ProductsPage::class)->name('tenant.products');
    Route::get('/product/{slug}', ProductDetailPage::class)
        ->name('tenant.product.detail');
    Route::get('/cart', CartPage::class)->name('tenant.cart');

    //
    // 2) Auth‐routes voor niet‐ingelogde gebruikers (guest) per tenant
    //
    Route::middleware('guest')->group(function () {
        Route::get('/login', LoginPage::class)->name('login');
        Route::get('/register', RegisterPage::class)->name('register');
        Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
        Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
    });

    //
    // 3) Routes voor ingelogde gebruikers (auth) per tenant
    //
    Route::middleware('auth')->group(function () {
        // Logout‐route
        Route::get('/logout', function () {
            auth()->logout();
            return redirect('/');
        })->name('logout');

        Route::get('/checkout', CheckoutPage::class)->name('tenant.checkout');
        Route::get('/my-orders', MyOrdersPage::class)->name('tenant.my-orders');
        Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)
            ->name('tenant.my-orders.show');
        Route::get('/success', SuccessPage::class)->name('tenant.success');
        Route::get('/cancel', CancelPage::class)->name('tenant.cancel');

        // PDF‐download factuur (alleen eigen orders)
        Route::get('/my-orders/{order_id}/invoice', function ($order_id) {
            $order = \App\Models\Order::with(['items.product', 'user', 'address'])
                ->findOrFail($order_id);

            if ($order->user_id !== auth()->id()) {
                abort(403, 'Je mag alleen je eigen facturen downloaden.');
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', [
                'order' => $order,
            ]);

            return $pdf->download('factuur-order-' . $order->id . '.pdf');
        })->name('tenant.my-orders.invoice');
    });

    //
    // 4) Filament‐adminpaneel voor deze tenant
    //    (aanroep: {subdomein}/admin, met login via Filament)
    //
    Filament::routes();
});
