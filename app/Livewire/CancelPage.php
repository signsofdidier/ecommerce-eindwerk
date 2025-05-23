<?php

namespace App\Livewire;

use Livewire\Component;

class CancelPage extends Component
{
    public function render()
    {
        // Haal de huidige company op (als die er is)
        $company = app()->has('current_company') ? app()->make('current_company') : null;
        return view('livewire.cancel-page');
    }
}
