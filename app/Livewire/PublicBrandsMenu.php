<?php

namespace App\Livewire;

use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PublicBrandsMenu extends Component
{
    public function render(): View
    {
        $brands = Brand::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('livewire.public-brands-menu', [
            'brands' => $brands
        ]);
    }
}
