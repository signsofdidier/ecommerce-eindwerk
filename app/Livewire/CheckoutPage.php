<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\InvoicePaidMail;
use App\Mail\OrderPlacedMail;
use App\Models\Address;
use App\Models\Order;
use App\Models\ProductColorStock;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $sameAsBilling = true;

    public $billing_first_name, $billing_last_name, $billing_email, $billing_phone;
    public $billing_address, $billing_city, $billing_state, $billing_zip_code;

    public $shipping_first_name, $shipping_last_name, $shipping_email, $shipping_phone;
    public $shipping_address, $shipping_city, $shipping_state, $shipping_zip_code;

    public $payment_method;

    public float $sub_total = 0;
    public float $shipping_amount = 0;
    public float $free_shipping_threshold = 0;

    public function mount(){
        $cart_items = CartManagement::getCartItemsFromSession();
        if(count($cart_items) == 0){
            return redirect('/products');
        }
        $this->sameAsBilling = true;

        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0;

        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        // AUTO-FILL adresgegevens indien beschikbaar
        $user = auth()->user();
        if ($user && $user->address) {
            $this->billing_first_name = $user->address->first_name;
            $this->billing_last_name = $user->address->last_name;
            $this->billing_email = $user->email;
            $this->billing_phone = $user->address->phone;
            $this->billing_address = $user->address->street_address;
            $this->billing_city = $user->address->city;
            $this->billing_state = $user->address->state;
            $this->billing_zip_code = $user->address->zip_code;

            // Als 'same as billing' actief is, vul shipping ook vooraf in
            if ($this->sameAsBilling) {
                $this->syncShippingWithBilling();
            }
        }
    }

    private function syncShippingWithBilling()
    {
        $this->shipping_first_name = $this->billing_first_name;
        $this->shipping_last_name = $this->billing_last_name;
        $this->shipping_email = $this->billing_email;
        $this->shipping_phone = $this->billing_phone;
        $this->shipping_address = $this->billing_address;
        $this->shipping_city = $this->billing_city;
        $this->shipping_state = $this->billing_state;
        $this->shipping_zip_code = $this->billing_zip_code;
    }


    public function placeOrder(){
        $this->validate([
            'billing_first_name' => 'required',
            'billing_last_name' => 'required',
            'billing_email' => 'required|email',
            'billing_phone' => 'required',
            'billing_address' => 'required',
            'billing_city' => 'required',
            'billing_state' => 'required',
            'billing_zip_code' => 'required',

            'shipping_first_name' => 'required_if:sameAsBilling,false',
            'shipping_last_name' => 'required_if:sameAsBilling,false',
            'shipping_email' => 'required_if:sameAsBilling,false|email',
            'shipping_phone' => 'required_if:sameAsBilling,false',
            'shipping_address' => 'required_if:sameAsBilling,false',
            'shipping_city' => 'required_if:sameAsBilling,false',
            'shipping_state' => 'required_if:sameAsBilling,false',
            'shipping_zip_code' => 'required_if:sameAsBilling,false',

            'payment_method' => 'required',
        ]);

        $cart_items = CartManagement::getCartItemsFromSession();

        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        // Voor Cash on Delivery: maak direct het order aan
        if($this->payment_method == 'cod'){
            return $this->createOrder($cart_items);
        }

        // Voor Stripe: maak geen order aan, maar sla orderdata op in session
        // en stuur door naar Stripe
        if($this->payment_method == 'stripe'){
            return $this->createStripeCheckout($cart_items);
        }
    }

    private function createStripeCheckout($cart_items)
    {
        // Sla orderdata op in sessie (zodat we het later kunnen gebruiken)
        session()->put('pending_order_data', [
            'cart_items' => $cart_items,
            'billing' => [
                'first_name' => $this->billing_first_name,
                'last_name' => $this->billing_last_name,
                'email' => $this->billing_email,
                'phone' => $this->billing_phone,
                'address' => $this->billing_address,
                'city' => $this->billing_city,
                'state' => $this->billing_state,
                'zip_code' => $this->billing_zip_code,
            ],
            'shipping' => [
                'first_name' => $this->shipping_first_name,
                'last_name' => $this->shipping_last_name,
                'email' => $this->shipping_email,
                'phone' => $this->shipping_phone,
                'address' => $this->shipping_address,
                'city' => $this->shipping_city,
                'state' => $this->shipping_state,
                'zip_code' => $this->shipping_zip_code,
            ],
            'sub_total' => $this->sub_total,
            'shipping_amount' => $this->shipping_amount,
        ]);


        $line_items = [];

        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item['unit_amount'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        if ($this->shipping_amount > 0) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $this->shipping_amount * 100,
                    'product_data' => [
                        'name' => 'Shipping',
                    ],
                ],
                'quantity' => 1,
            ];
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionCheckout = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => auth()->user()->email,
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        return redirect($sessionCheckout->url);
    }

    private function createOrder($cart_items)
    {
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->sub_total = $this->sub_total;
        $order->grand_total = $this->sub_total + $this->shipping_amount;
        $order->payment_method = 'cash on delivery';
        $order->payment_status = $this->payment_method == 'cod' ? 'pending' : 'paid';
        $order->status = 'new';
        $order->currency = 'EUR';
        $order->shipping_amount = $this->shipping_amount;
        $order->shipping_method = 'Truck Delivery';
        $order->notes = 'Order placed by ' . auth()->user()->name;

        $order->billing_first_name = $this->billing_first_name;
        $order->billing_last_name = $this->billing_last_name;
        $order->billing_email = $this->billing_email;
        $order->billing_phone = $this->billing_phone;
        $order->billing_address = $this->billing_address;
        $order->billing_city = $this->billing_city;
        $order->billing_state = $this->billing_state;
        $order->billing_zip_code = $this->billing_zip_code;

        $order->shipping_first_name = $this->shipping_first_name;
        $order->shipping_last_name = $this->shipping_last_name;
        $order->shipping_email = $this->shipping_email;
        $order->shipping_phone = $this->shipping_phone;
        $order->shipping_address = $this->shipping_address;
        $order->shipping_city = $this->shipping_city;
        $order->shipping_state = $this->shipping_state;
        $order->shipping_zip_code = $this->shipping_zip_code;

        $order->save();

        // Maak het adres aan
        $address = new Address();
        $address->order_id = $order->id;
        $address->user_id = auth()->user()->id;
        $address->first_name = $this->billing_first_name;
        $address->last_name = $this->billing_last_name;
        $address->phone = $this->billing_phone;
        $address->street_address = $this->billing_address;
        $address->city = $this->billing_city;
        $address->state = $this->billing_state;
        $address->zip_code = $this->billing_zip_code;

        $address->save();

        // Sla als profieladres op
        $user = auth()->user();
        if (!$user->address) {
            $user->address()->associate($address);
            $user->save();
        }

        // Sla order items op
        $order->items()->createMany($cart_items);

        // Verlaag stock
        foreach ($cart_items as $item) {
            $stockEntry = ProductColorStock::where('product_id', $item['product_id'])
                ->where('color_id', $item['color_id'])
                ->first();

            if ($stockEntry) {
                $stockEntry->decrement('stock', $item['quantity']);
            }
        }

        // Leeg cart alleen na succesvol order
        CartManagement::clearCartItems();

        // Stuur mail voor COD
        if ($this->payment_method === 'cod') {
            Mail::to($order->user->email)->send(new OrderPlacedMail($order));
        }

        return redirect()->route('success');
    }

    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromSession();
        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'sub_total' => $this->sub_total,
            'shipping_amount' => $this->shipping_amount,
            'free_shipping_threshold' => $this->free_shipping_threshold,
            'grand_total' => $this->sub_total + $this->shipping_amount,
        ]);
    }

    private function calculateShippingAmount(array $cart_items): void
    {
        $this->shipping_amount = CartManagement::calculateShippingAmount($cart_items);
    }
}
