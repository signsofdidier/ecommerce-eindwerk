<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Order Detail')]
class MyOrderDetailPage extends Component
{
    public $order_id;
    public $current_company;

    public function mount($order_id){
        $this->current_company = app()->has('current_company') ? app()->make('current_company') : null;
        $this->order_id = $order_id;

    }

    public function render()
    {
        // TENANT toevoegen
        $order = Order::where('id', $this->order_id)
            ->where('user_id', auth()->id())
            ->when($this->current_company, function ($query) {
                $query->where('company_id', $this->current_company->id);
            })
            ->firstOrFail();

        $order_items = OrderItem::with(['product' => function($query) {
            $company = app()->make('current_company');
            $query->where('company_id', $company->id);
        }])
            ->where('order_id', $this->order_id)
            ->where('company_id', $this->current_company->id) // <--- BELANGRIJK!
            ->get();


        $address = Address::where('order_id', $this->order_id)->first();

        return view('livewire.my-order-detail-page', [
            'order_items' => $order_items,
            'address' => $address,
            'order' => $order
        ]);
    }
}
