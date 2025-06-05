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

//route homepage los van tenants
/*Route::get('/', function () {
    return view('welcome');
});*/

// Home zonder bedrijf => lijst van companies of redirect naar een
/*Route::get('/', function () {

    $firstCompany = \App\Models\Company::first();
    if ($firstCompany) return redirect('/' . $firstCompany->slug);
});*/


Route::group([
    'prefix' => '{company}',
    'middleware' => ['setFrontendCompany'],
], function () {

    // openbare routes voor alle users
    Route::get('/', HomePage::class)->name('home');
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
        Route::get('/my-orders/{order_id}/invoice', function ($company, $order_id) {
            $order = \App\Models\Order::with('items.product', 'user', 'address')->findOrFail($order_id);

            if ($order->user_id !== auth()->id()) {
                abort(403);
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', [
                'order' => $order
            ]);

            return $pdf->download('factuur-order-' . $order->id . '.pdf');
        })->name('my-orders.invoice')->middleware('auth');
    });
});



