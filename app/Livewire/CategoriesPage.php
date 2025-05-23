<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Categories - E-Commerce')]

class CategoriesPage extends Component
{
    public function render()
    {
        // Haal de huidige company op (als die er is)
        $company = app()->has('current_company') ? app()->make('current_company') : null;

        $categories = Category::where('is_active', 1)
            ->when($company, function($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->get();

        return view('livewire.categories-page', [
            'categories' => $categories
        ]);
    }
}
