<div>
    <div class="checkout-page mt-100">
        <div class="container">
            <div class="checkout-page-wrapper">
                <div class="row">
                    <div class="col-xl-9 col-lg-8 col-md-12 col-12">
                        <div class="section-header mb-3">
                            <h2 class="section-heading">Check out</h2>
                        </div>

                        <div class="checkout-user-area overflow-hidden d-flex align-items-center">
                            <div class="checkout-user-img me-4">
                                <img class="rounded-circle" style="height: 80px; width: 80px;" src="{{ Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : asset('assets/img/default-avatar.png') }}" alt="{{ Auth::user()->name }}">
                            </div>
                            <div class="checkout-user-details d-flex align-items-center justify-content-between w-100">
                                <div class="checkout-user-info">
                                    <h2 class="checkout-user-name">{{ Auth::user()->name }}</h2>
                                    <p class="text-light mb-0">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="edit-user btn">EDIT PROFILE</a>
                            </div>
                        </div>

                        <div class="shipping-address-area">
                            <h2 class="shipping-address-heading pb-1">Shipping address</h2>
                            <div class="shipping-address-form-wrapper">
                                <form wire:submit.prevent="placeOrder" class="shipping-address-form common-form">
                                    <div class="row">
                                        <div class="col-12">
                                            <fieldset class="form-check mb-3 d-flex">
                                                <input type="checkbox" id="sameAsBilling" wire:model="sameAsBilling" class="">
                                                <label for="sameAsBilling" class="form-check-label">Shipping address is the same as billing address</label>
                                            </fieldset>
                                        </div>

                                        {{-- Billing First Name --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label for="billing_first_name" class="label">First name</label>
                                                <input wire:model="billing_first_name" id="billing_first_name" type="text" class="form-control" />
                                            </fieldset>
                                            @error('billing_first_name')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        {{-- Billing Last Name --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label for="billing_last_name" class="label">Last name</label>
                                                <input wire:model="billing_last_name" id="billing_last_name" type="text" class="form-control" />
                                            </fieldset>
                                            @error('billing_last_name')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        {{-- Billing Phone --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label for="billing_phone" class="label">Phone</label>
                                                <input wire:model="billing_phone" id="billing_phone" type="text" class="form-control" />
                                            </fieldset>
                                            @error('billing_phone')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        {{-- Billing Address --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label for="billing_address" class="label">Address</label>
                                                <input wire:model="billing_address" id="billing_address" type="text" class="form-control" />
                                            </fieldset>
                                            @error('billing_address')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        {{-- Billing City --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label for="billing_city" class="label">City</label>
                                                <input wire:model="billing_city" id="billing_city" type="text" class="form-control" />
                                            </fieldset>
                                            @error('billing_city')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        {{-- Billing Zip Code --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label for="billing_zip_code" class="label">Zip code</label>
                                                <input wire:model="billing_zip_code" id="billing_zip_code" type="text" class="form-control" />
                                            </fieldset>
                                            @error('billing_zip_code')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        {{-- Billing State --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label for="billing_state" class="label">State</label>
                                                <input wire:model="billing_state" id="billing_state" type="text" class="form-control" />
                                            </fieldset>
                                            @error('billing_state')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        {{-- Country --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label for="country" class="label">Country</label>
                                                <input type="text" class="form-control" value="Belgium" disabled readonly>
                                            </fieldset>
                                        </div>

                                        @if(!$sameAsBilling)
                                            {{-- Shipping Fields --}}
                                            <div class="col-12 mt-3">
                                                <h4 class="mb-3">Shipping Address</h4>
                                            </div>

                                            <div class="col-lg-6 col-md-12 col-12">
                                                <fieldset>
                                                    <label for="shipping_first_name" class="label">First name</label>
                                                    <input wire:model="shipping_first_name" id="shipping_first_name" type="text" class="form-control" />
                                                </fieldset>
                                                @error('shipping_first_name')<div class="text-danger small">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-lg-6 col-md-12 col-12">
                                                <fieldset>
                                                    <label for="shipping_last_name" class="label">Last name</label>
                                                    <input wire:model="shipping_last_name" id="shipping_last_name" type="text" class="form-control" />
                                                </fieldset>
                                                @error('shipping_last_name')<div class="text-danger small">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-lg-6 col-md-12 col-12">
                                                <fieldset>
                                                    <label for="shipping_phone" class="label">Phone</label>
                                                    <input wire:model="shipping_phone" id="shipping_phone" type="text" class="form-control" />
                                                </fieldset>
                                                @error('shipping_phone')<div class="text-danger small">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-lg-6 col-md-12 col-12">
                                                <fieldset>
                                                    <label for="shipping_address" class="label">Address</label>
                                                    <input wire:model="shipping_address" id="shipping_address" type="text" class="form-control" />
                                                </fieldset>
                                                @error('shipping_address')<div class="text-danger small">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-lg-6 col-md-12 col-12">
                                                <fieldset>
                                                    <label for="shipping_city" class="label">City</label>
                                                    <input wire:model="shipping_city" id="shipping_city" type="text" class="form-control" />
                                                </fieldset>
                                                @error('shipping_city')<div class="text-danger small">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-lg-6 col-md-12 col-12">
                                                <fieldset>
                                                    <label for="shipping_zip_code" class="label">Zip code</label>
                                                    <input wire:model="shipping_zip_code" id="shipping_zip_code" type="text" class="form-control" />
                                                </fieldset>
                                                @error('shipping_zip_code')<div class="text-danger small">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-lg-6 col-md-12 col-12">
                                                <fieldset>
                                                    <label for="shipping_state" class="label">State</label>
                                                    <input wire:model="shipping_state" id="shipping_state" type="text" class="form-control" />
                                                </fieldset>
                                                @error('shipping_state')<div class="text-danger small">{{ $message }}</div>@enderror
                                            </div>
                                        @endif

                                        {{-- Payment Method --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label class="label">Payment method</label>
                                                <div class="d-flex gap-2">
                                                    <input type="radio" id="payment-cod" wire:model="payment_method" value="cod" class="btn-check">
                                                    <label for="payment-cod" class="btn btn-outline-secondary flex-fill">CASH ON DELIVERY</label>
                                                    <input type="radio" id="payment-stripe" wire:model="payment_method" value="stripe" class="btn-check">
                                                    <label for="payment-stripe" class="btn btn-outline-secondary flex-fill">BANCONTACT</label>
                                                </div>
                                            </fieldset>
                                            @error('payment_method')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class=" shipping-address-area billing-area mt-4">
                                            <div class="minicart-btn-area d-flex align-items-center justify-content-between flex-wrap">
                                                <a href="{{ url('/cart') }}" class="checkout-page-btn minicart-btn btn-secondary">BACK TO CART</a>
                                                <button type="submit" class="checkout-page-btn minicart-btn btn-primary">PLACE ORDER</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    {{-- ORDER SUMMARY --}}
                    <div class="col-xl-3 col-lg-4 col-md-12 col-12">
                        <div class="cart-total-area checkout-summary-area">
                            <h3 class="d-none d-lg-block mb-0 text-center heading_24 mb-4">Order summary</h3>

                            @forelse($cart_items as $item)
                                <div class="minicart-item d-flex">
                                    <div class="mini-img-wrapper">
                                        <img class="mini-img" src="{{ url('storage', $item['image']) }}" alt="{{ $item['name'] }}">
                                    </div>
                                    <div class="product-info">
                                        <h2 class="product-title">
                                            <a href="{{ url('/products') }}/{{ $item['slug'] }}">
                                                {{ $item['name'] }}
                                            </a>
                                        </h2>

                                        {{-- COLOR --}}
                                        @if(! empty($item['color_name']))
                                            <p class="product-vendor d-flex align-items-center">
                                                    <span
                                                        class="me-2 rounded-circle"
                                                        style="
                                                          display:inline-block;
                                                          width:0.7rem;
                                                          height:0.7rem;
                                                          background-color: {{ $item['color_hex'] }};
                                                          border: 1px solid #ccc;
                                                        ">
                                                    </span>
                                                {{ $item['color_name'] }}
                                            </p>
                                        @endif

                                        <p class="product-vendor mb-1">
                                            {{ Number::currency($item['unit_amount'], 'EUR') }} × {{ $item['quantity'] }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                {{-- Als je om de een of andere reden geen items hebt… (maar dat zou niet mogen) --}}
                            @endforelse

                            {{-- Veld voor kortingscode (optioneel) --}}
                            {{--<div class="mb-4">
                                <label for="discount_code" class="form-label">Discount code:</label>
                                <input
                                    type="text"
                                    wire:model.defer="discount_code"
                                    id="discount_code"
                                    class="form-control @error('discount_code') is-invalid @enderror"
                                >
                                @error('discount_code')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>--}}

                            <div class="cart-total-box mt-4 bg-transparent p-0">

                                {{-- 1) Subtotals: som van alle items --}}
                                <div class="subtotal-item subtotal-box d-flex justify-content-between">
                                    <h4 class="subtotal-title">Subtotals:</h4>
                                    <p class="subtotal-value">{{ Number::currency($sub_total, 'EUR') }}</p>
                                </div>

                                {{-- 2) Taxes (21%) nog altijd 0 in dit voorbeeld --}}
                                <div class="subtotal-item discount-box d-flex justify-content-between">
                                    <h4 class="subtotal-title small text-muted">Taxes (21%):</h4>
                                    <p class="subtotal-value small text-muted">{{ Number::currency($sub_total * 0.21, 'EUR') }}</p>
                                </div>

                                {{-- 3) Shipping Cost --}}
                                <div class="subtotal-item shipping-box d-flex justify-content-between">

                                    @if($free_shipping_threshold > 0 && $sub_total >= $free_shipping_threshold)
                                        <p class="subtotal-value small">
                                            Free Shipping
                                        </p>
                                    @else
                                        <h4 class="subtotal-title small">Shipping Cost:</h4>
                                        <p class="subtotal-value small">
                                            {{ Number::currency($shipping_amount, 'EUR') }}
                                        </p>
                                    @endif

                                </div>

                                <hr />

                                {{-- 4) Total: sub_total + shipping_amount --}}
                                <div class="subtotal-item discount-box d-flex justify-content-between">
                                    <h4 class="subtotal-title">Total:</h4>
                                    <p class="subtotal-value">
                                        {{ Number::currency($grand_total, 'EUR') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .btn-check:checked + .btn-outline-secondary {
            background-color: #00234D;
            color: #fff;
            border-color: #00234D;
        }
    </style>

</div>
