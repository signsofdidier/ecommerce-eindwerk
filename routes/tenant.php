<?php

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
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
use App\Livewire\Pages\Blog\Index;
use App\Livewire\Pages\Blog\Show;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    Authenticate::class, // belangrijk voor Filament auth
])->group(function () {

    Route::get('/', HomePage::class);
    Route::get('/categories', CategoriesPage::class);
    Route::get('/products', ProductsPage::class);
    Route::get('/cart', CartPage::class);
    Route::get('/products/{slug}', ProductDetailPage::class);
    Route::get('/blog', Index::class)->name('blog.index');
    Route::get('/blog/{slug}', Show::class)->name('blog.show');

    Route::middleware('guest')->group(function() {
        Route::get('/login', LoginPage::class)->name('login');
        Route::get('/register', RegisterPage::class);
        Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
        Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
    });

    Route::middleware('auth')->group(function() {
        Route::get('/logout', function () {
            auth()->logout();
            return redirect('/');
        });

        Route::get('/checkout', CheckoutPage::class);
        Route::get('/my-orders', MyOrdersPage::class);
        Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');
        Route::get('/success', SuccessPage::class)->name('success');
        Route::get('/cancel', CancelPage::class)->name('cancel');

        Route::get('/my-orders/{order_id}/invoice', function ($order_id) {
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
