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
use App\Livewire\Pages\Blog\Index;
use App\Livewire\Pages\Blog\Show;
use App\Livewire\Pages\PrivacyPolicyPage;
use App\Livewire\Pages\TermsConditionsPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\ProfileForm;
use App\Livewire\SuccessPage;
use App\Livewire\WishlistPage;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Livewire\Auth\VerifyEmailPage;


// openbare routes voor alle users
Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/products', ProductsPage::class);
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/products/{slug}', ProductDetailPage::class);
Route::get('/blog', Index::class)->name('blog.index');
Route::get('/blog/{slug}', Show::class)->name('blog.show');
Route::get('/privacy-policy', PrivacyPolicyPage::class)->name('privacy-policy');
Route::get('/terms-conditions', TermsConditionsPage::class)->name('terms-conditions');


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
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/profile', ProfileForm::class)->name('profile');
    Route::get('/my-orders', MyOrdersPage::class);
    Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');
    Route::get('/success', SuccessPage::class)->name('success');
    Route::get('/cancel', CancelPage::class)->name('cancel');
    Route::get('/wishlist', WishlistPage::class)->name('wishlist');

    Route::get('/my-orders/{order_id}/invoice', function ($order_id) {
        $order = Order::with('items.product', 'user', 'address')->findOrFail($order_id);

        // Je mag alleen je eigen orders zien
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Barry VDB DOM PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', [
            'order' => $order
        ]);

        // Download factuur van dit order
        return $pdf->download('invoice-order-' . $order->id . '.pdf');
    })->name('my-orders.invoice');
});



// VERIFICATIE E-MAIL
Route::get('/email/verify', VerifyEmailPage::class)->middleware('auth')->name('verification.notice');

// dit route wordt gebruikt om de gebruiker te verifiëren
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

// verzend de verification mail opnieuw
Route::post('/email/verification-notification', function () {
    auth()->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



