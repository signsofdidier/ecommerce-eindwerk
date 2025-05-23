<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

// Titel (tablat)
#[Title('Home Page - Ecommrce')]

class HomePage extends Component
{
    public function render()
    {
        // Haal de huidige company op (als die er is)
        $company = app()->has('current_company') ? app()->make('current_company') : null;

        // Pas de query aan om alleen brands van de huidige tenant te tonen (indien een tenant is ingesteld)
        $brandsQuery = Brand::where('is_active', 1);
        if ($company) {
            $brandsQuery->where('company_id', $company->id);
        }
        $brands = $brandsQuery->get();

        // Pas de query aan om alleen categories van de huidige tenant te tonen (indien een tenant is ingesteld)
        $categoriesQuery = Category::where('is_active', 1);
        if ($company) {
            $categoriesQuery->where('company_id', $company->id);
        }
        $categories = $categoriesQuery->get();


        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories,
            'company' => $company, // Geef de company door aan de view voor URL generatie
        ]);
    }
}
