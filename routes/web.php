<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomePage;
use App\Livewire\CategoriesPage;
use App\Livewire\ProductsPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\SuccessPage;
use App\Livewire\CancelPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\ResetPasswordPage;

// ✳️ Optioneel: algemene homepage zonder tenant (komt later)
// Route::get('/', function () {
//     return view('welcome');
// });

Route::group([
    'prefix' => '{company}',
    'middleware' => ['setFrontendCompany'],
], function () {

    // 🟢 Publieke routes
    Route::get('/', HomePage::class)->name('home');
    Route::get('/categories', CategoriesPage::class)->name('categories');
    Route::get('/products', ProductsPage::class)->name('products');
    Route::get('/products/{slug}', ProductDetailPage::class)->name('product.show');
    Route::get('/cart', CartPage::class)->name('cart');

    // 🔐 Niet-ingelogde gebruikers
    Route::middleware('guest')->group(function () {
        Route::get('/login', LoginPage::class)->name('login');
        Route::get('/register', RegisterPage::class)->name('register');
        Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
        Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
    });

    // 🔐 Ingelogde gebruikers
    Route::middleware('auth')->group(function () {

        // Logout (via route, eventueel vervangen door post later)
        Route::post('/logout', function (\Illuminate\Http\Request $request, $company) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home', ['company' => $company]);
        })->name('logout');



        Route::get('/checkout', CheckoutPage::class)->name('checkout');
        Route::get('/success', SuccessPage::class)->name('success');
        Route::get('/cancel', CancelPage::class)->name('cancel');

        Route::get('/my-orders', MyOrdersPage::class)->name('my-orders');
        Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');

        // PDF factuur downloaden
        Route::get('/my-orders/{order_id}/invoice', function ($company, $order_id) {
            $order = \App\Models\Order::with('items.product', 'user', 'address')->findOrFail($order_id);

            if ($order->user_id !== auth()->id()) {
                abort(403);
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', [
                'order' => $order
            ]);

            return $pdf->download('factuur-order-' . $order->id . '.pdf');
        })->name('my-orders.invoice');
    });
});
