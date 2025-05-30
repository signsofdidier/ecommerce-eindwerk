<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;
use Stripe\Stripe;

#[Title('Success - E-Commerce')]
class SuccessPage extends Component
{
    #[Url('session_id')]
    public ?string $session_id = null;

    public Order $order;
    public ?string $transactionId = null;

    public function mount()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        if ($this->session_id) {
            // Load the associated order via stored session_id
            $this->order = Order::with('address')
                ->where('stripe_session_id', $this->session_id)
                ->firstOrFail();

            // Retrieve the Checkout Session
            $session = StripeSession::retrieve($this->session_id);

            // Retrieve PaymentIntent for actual charge details
            $pi = PaymentIntent::retrieve($session->payment_intent);
            $charge = $pi->charges->data[0] ?? null;

            // If no valid succeeded charge, mark failed and redirect
            if (! $charge || $charge->status !== 'succeeded') {
                $this->order->payment_status = 'failed';
                $this->order->save();

                return redirect()->route('cancel');
            }

            // Payment succeeded: store the transaction ID and update status
            $this->transactionId        = $charge->id;   // e.g. ch_1JH...
            $this->order->payment_status = 'paid';
            $this->order->save();

        } else {
            // No Stripe session (e.g. COD) – show the latest order
            $this->order = auth()->user()
                ->orders()
                ->with('address')
                ->latest()
                ->firstOrFail();
        }
    }

    public function render()
    {
        return view('livewire.success-page', [
            'order'         => $this->order,
            'transactionId' => $this->transactionId,
        ]);
    }
}
