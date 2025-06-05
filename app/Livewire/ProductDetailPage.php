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
    public $product;
    public $quantity = 1;
    public $selectedColorId;

    public function mount($slug)
    {
        $this->slug = $slug;

        // tenant filter
        $this->product = Product::with('colors')
            ->where('slug', $slug)
            ->where('company_id', currentCompany()?->id) //  multi-tenancy filter
            ->first();

        if (!$this->product) {
            abort(404); // Product niet gevonden of hoort niet bij deze tenant
        }

        // Standaard eerste kleur nemen
        $this->selectedColorId = optional($this->product->colors->first())->id;
    }

    public function increaseQuantity(){
        $this->quantity++;
    }

    public function decreaseQuantity(){
        if($this->quantity > 1){
            $this->quantity--;
        }
    }

    // add product to cart method
    public function addToCart($product_id){
        // Voeg product met geselecteerde kleur toe aan cart
        $total_count = CartManagement::addItemToCartWithQuantity(
            $product_id,
            $this->quantity,
            $this->selectedColorId
        );

        // Update cart count in Navbar via event
        $this->dispatch('update-cart-count', $total_count)
            ->to(Navbar::class);

        // LIVEWIRE SWEETALERT
        $this->dispatch('alert');

        /*$this->dispatch('alert',
            type: 'success',
            title: 'Product added to cart',
            position: 'bottom-end',
            timer: 3000,
            toast: true
        );*/
    }

    public function render()
    {
        // haal alleen de actieve, featured producten op (max. 8)
        $featuredProducts = Product::query()
            ->where('company_id', currentCompany()?->id) // multi-tenant
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->take(8)
            ->get();

        return view('livewire.product-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrFail(),
            'featuredProducts' => $featuredProducts
        ]);
    }
}
