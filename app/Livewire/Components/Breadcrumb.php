<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

class Breadcrumb extends Component
{
    public $breadcrumbs = [];

    public function mount()
    {
        $this->breadcrumbs = $this->generateBreadcrumbs();
    }

    protected function generateBreadcrumbs()
    {
        $segments = request()->segments();
        $breadcrumbs = [];

        $url = '';

        foreach ($segments as $index => $segment) {
            $url .= '/' . $segment;

            $breadcrumbs[] = [
                'name' => $index === 0 ? 'Home' : ucfirst(str_replace('-', ' ', $segment)),
                'url' => $url,
            ];
        }

        return $breadcrumbs;
    }


    public function render()
    {
        return view('livewire.components.breadcrumb');
    }
}
