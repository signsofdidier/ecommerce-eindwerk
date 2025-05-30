<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PromotionCode;

#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;
    public $coupon_code;

    public function mount()
    {
        $cart_items = CartManagement::getCartItemsFromSession();
        if (count($cart_items) === 0) {
            return redirect('/products');
        }
    }

    public function placeOrder()
    {
        // 1) Validatie
        $this->validate([
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'phone'          => 'required|string',
            'street_address' => 'required|string',
            'city'           => 'required|string',
            'state'          => 'required|string',
            'zip_code'       => 'required|string',
            'payment_method' => 'required|in:stripe,cod',
            'coupon_code'    => 'nullable|string',
        ]);

        $cart_items = CartManagement::getCartItemsFromSession();

        // 2) Maak Order + Address
        $order = Order::create([
            'user_id'        => auth()->id(),
            'grand_total'    => CartManagement::calculateGrandTotal($cart_items),
            'payment_method' => $this->payment_method,
            'payment_status' => 'pending',
            'status'         => 'new',
            'currency'       => 'EUR',
            'shipping_amount'=> 0,
            'shipping_method'=> 'none',
            'notes'          => 'Order placed by ' . auth()->user()->name,
        ]);

        Address::create([
            'order_id'      => $order->id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'phone'         => $this->phone,
            'street_address'=> $this->street_address,
            'city'          => $this->city,
            'state'         => $this->state,
            'zip_code'      => $this->zip_code,
        ]);

        // 3) Betaalmethode afhandelen
        if ($this->payment_method === 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // a) Coupon valideren (optioneel)
            $promotion_code_id = null;
            if ($this->coupon_code) {
                try {
                    $promo = PromotionCode::retrieve($this->coupon_code);
                    if (! $promo->active) {
                        throw new \Exception('Coupon is niet actief');
                    }
                    $promotion_code_id = $promo->id;
                } catch (\Exception $e) {
                    $this->addError('coupon_code', 'Ongeldige of verlopen kortingscode.');
                    return;
                }
            }

            // b) Bouw line_items voor Stripe Checkout
            $line_items = [];
            foreach ($cart_items as $item) {
                $line_items[] = [
                    'price_data' => [
                        'currency'     => 'eur',
                        'unit_amount'  => (int) round(floatval($item['total_amount']) * 100),
                        'product_data' => ['name' => $item['name']],
                    ],
                    'quantity'    => $item['quantity'],
                ];
            }

            $sessionParams = [
                'payment_method_types' => ['card'],
                'customer_email'       => auth()->user()->email,
                'line_items'           => $line_items,
                'mode'                 => 'payment',
                'success_url'          => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => route('cancel'),
            ];

            if ($promotion_code_id) {
                $sessionParams['discounts'] = [
                    ['promotion_code' => $promotion_code_id],
                ];
            }

            // c) Creëer Checkout Session
            $session = Session::create($sessionParams);

            // d) Sla session-id op en redirect
            $order->stripe_session_id = $session->id;
            $order->save();

            $redirect_url = $session->url;
        } else {
            // Cash on Delivery
            $redirect_url = route('success');
        }

        // 4) Sla de order-items op, clear cart en mail bevestiging
        $order->items()->createMany($cart_items);
        CartManagement::clearCartItems();
        Mail::to(auth()->user())->send(new OrderPlaced($order));

        return redirect($redirect_url);
    }

    public function render()
    {
        $cart_items  = CartManagement::getCartItemsFromSession();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);

        return view('livewire.checkout-page', compact('cart_items', 'grand_total'));
    }
}
