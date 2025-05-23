<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('My Orders - E-Commerce')]
class MyOrdersPage extends Component
{
    use WithPagination;

    public $current_company;

    public function mount()
    {
        $this->current_company = app()->has('current_company') ? app()->make('current_company') : null;
    }

    public function render()
    {

        $my_orders = Order::where('user_id', auth()->id())
            ->when($this->current_company, function ($query) {
                $query->where('company_id', $this->current_company->id);
            })
            ->latest()
            ->paginate(6);

        return view('livewire.my-orders-page', [
            'orders' => $my_orders,
        ]);
    }
}
