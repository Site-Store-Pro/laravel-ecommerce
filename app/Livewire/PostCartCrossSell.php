<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.public')]
class PostCartCrossSell extends Component
{
    public int $variantId = 0;
    public ?ProductVariant $variant = null;
    public ?Product $addedProduct = null;

    public function mount(int $variantId): void
    {
        $this->variantId   = $variantId;
        $this->variant     = ProductVariant::with('product')->find($variantId);
        $this->addedProduct = $this->variant?->product;
    }

    public function render(): View
    {
        return view('livewire.post-cart-cross-sell', [
            'variant'       => $this->variant,
            'addedProduct'  => $this->addedProduct,
        ]);
    }
}
