<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - E-Commerce')]
class ProductDetailPage extends Component
{
    public $slug;
    public $quantity = 1;
    public $current_company;

    public function mount($slug) {
        $this->slug = $slug;
        $this->current_company = app()->has('current_company') ? app()->make('current_company') : null;
    }

    public function increaseQuantity() {
        $this->quantity++;
    }

    public function decreaseQuantity() {
        if($this->quantity > 1) {
            $this->quantity--;
        }
    }

    // add product to cart method
    public function addToCart($product_id) {
        // Controleer of het product bij de huidige tenant hoort
        $product = Product::where('id', $product_id)
            ->when($this->current_company, function($query) {
                $query->where('company_id', $this->current_company->id);
            })
            ->first();

        if (!$product) {
            $this->dispatch('alert',
                type: 'error',
                title: 'Product not available in this store',
                position: 'bottom-end',
                timer: 3000,
                toast: true
            );
            return;
        }

        $total_count = CartManagement::addItemToCartWithQuantity($product_id, $this->quantity);

        // Update cart count in navbar
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->dispatch('alert',
            type: 'success',
            title: 'Product added to cart',
            position: 'bottom-end',
            timer: 3000,
            toast: true
        );
    }

    public function render()
    {
        $product = Product::where('slug', $this->slug)
            ->when($this->current_company, function($query) {
                $query->where('company_id', $this->current_company->id);
            })
            ->first();

        if (!$product) {
            abort(404, 'Product not found in this store');
        }

        return view('livewire.product-detail-page', [
            'product' => $product
        ]);
    }
}
