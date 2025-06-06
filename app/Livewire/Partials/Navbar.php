<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use App\Models\Setting;
use App\Services\TenantService;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public $total_count = 0;

    public float $free_shipping_threshold = 0;

    public ?string $slug = null;

    public function mount(?string $slug = null)
    {
        // Haal de slug op van de actieve tenant (company)
        $this->slug = $slug ?? \App\Services\TenantService::slug();

        // Telt alle items in de cart op inclusief quantity
        $this->total_count = array_sum(array_column(CartManagement::getCartItemsFromSession(), 'quantity'));

        // Haal de free_shipping_threshold uit de database
        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0.0;
    }

    #[On('update-cart-count')]
    public function updateCartCount($total_count)
    {
        $this->total_count = $total_count;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
