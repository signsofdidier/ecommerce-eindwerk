<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureTenantUserIsValid;
use App\Http\Middleware\SetTenant;

// Livewire-componenten (controleer dat deze klassen écht bestaan in app/Livewire/…)
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\HomePage;
use App\Livewire\CategoriesPage;
use App\Livewire\ProductsPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\SuccessPage;
use App\Livewire\CancelPage;

/*
|--------------------------------------------------------------------------
| 1) Tenant‐routes (alleen op <subdomain>.localhost)
|--------------------------------------------------------------------------
|
| Door Route::domain('{subdomain}.localhost') open je deze groep
| alléén als er wél een subdomein voor “.localhost” staat (bijv. “bedrijf1.localhost”).
| Op “http://localhost/” zonder subdomein matcht dit dus niet en wordt deze
| groep níet geladen. Daardoor zie je de webshop alléén op echte subdomeinen.
|
*/

Route::domain('{subdomain}.localhost')->middleware([
    'web',
    'set.tenant',
    'ensure.tenant.user',
])->group(function () {
    //
    // Openbare Livewire‐routes voor de tenant-shop
    //
    Route::get('/', HomePage::class)->name('tenant.home');
    Route::get('/categories', CategoriesPage::class)->name('tenant.categories');
    Route::get('/products', ProductsPage::class)->name('tenant.products');
    Route::get('/product/{slug}', ProductDetailPage::class)
        ->name('tenant.product.detail');
    Route::get('/cart', CartPage::class)->name('tenant.cart');

    //
    // Auth-routes voor niet-ingelogde bezoekers (guest)
    //
    Route::middleware('guest')->group(function () {
        Route::get('/login', LoginPage::class)->name('login');
        Route::get('/register', RegisterPage::class)->name('register');
        Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
        Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
    });

    //
    // Routes voor ingelogde gebruikers (auth)
    //
    Route::middleware('auth')->group(function () {
        // Logout
        Route::get('/logout', function () {
            auth()->logout();
            return redirect('/');
        })->name('logout');

        Route::get('/checkout', CheckoutPage::class)->name('tenant.checkout');
        Route::get('/my-orders', MyOrdersPage::class)->name('tenant.my-orders');
        Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)
            ->name('tenant.my-orders.show');
        Route::get('/success', SuccessPage::class)->name('tenant.success');
        Route::get('/cancel',   CancelPage::class)->name('tenant.cancel');

        // PDF-download factuur
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
    // Filament-adminpaneel voor deze tenant
    // (Filament v3 registreert de routes automatisch via de ServiceProvider)
    //
});

/*
|--------------------------------------------------------------------------
| 2) Fallback-route voor “localhost” (zonder subdomein)
|--------------------------------------------------------------------------
|
| Als iemand naar http://localhost/ gaat, matcht dat níet in de bovenstaande
| domain-groep, dus hier komen we terecht. Je kunt hier eenvoudig redirecten
| naar de superadmin-login (http://localhost/superadmin/login).
|
*/

// … jouw tenant‐routes …

// Fallback op “localhost/” (zonder subdomein)
Route::get('/', function () {
    return redirect('/superadmin/login');
});

// *** BELANGRIJK: wikkel superadmin.php in web-middleware ***
Route::middleware('web')->group(function () {
    require __DIR__ . '/superadmin.php';
});

