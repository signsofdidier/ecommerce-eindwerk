<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - E-Commerce')]
class CartPage extends Component
{
    public $cart_items = [];
    public $grand_total;
    public $current_company;

    public function mount() {
        $this->current_company = app()->has('current_company') ? app()->make('current_company') : null;
        CartManagement::validateCartItems();

        $this->cart_items = CartManagement::getCartItemsFromSession();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    public function removeItem($product_id) {
        $product = Product::where('id', $product_id)
            ->when($this->current_company, function($query) {
                $query->where('company_id', $this->current_company->id);
            })
            ->first();

        if (!$product) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Product not found in current store',
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true
            ]);
            return;
        }

        $this->cart_items = CartManagement::removeCartItem($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count',
            total_count: array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);
    }

    public function increaseQuantity($product_id) {
        $product = Product::where('id', $product_id)
            ->when($this->current_company, function($query) {
                $query->where('company_id', $this->current_company->id);
            })
            ->first();

        if (!$product) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Product not found in current store',
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true
            ]);
            return;
        }

        CartManagement::incrementQuantityToCartItem($product_id);
        $this->cart_items = CartManagement::getCartItemsFromSession();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count',
            total_count: array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);
    }

    public function decreaseQuantity($product_id) {
        $product = Product::where('id', $product_id)
            ->when($this->current_company, function($query) {
                $query->where('company_id', $this->current_company->id);
            })
            ->first();

        if (!$product) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Product not found in current store',
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true
            ]);
            return;
        }

        CartManagement::decrementQuantityToCartItem($product_id);
        $this->cart_items = CartManagement::getCartItemsFromSession();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count',
            total_count: array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);
    }

    public function render() {
        return view('livewire.cart-page');
    }
}
