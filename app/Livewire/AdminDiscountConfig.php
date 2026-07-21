<?php

namespace App\Livewire;

use App\Models\DiscountConfiguration;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminDiscountConfig extends Component
{
    public int $coupon_codes = 1;
    public int $preferred_customers = 1;
    public int $category_discounts = 1;
    public int $quantity_based = 1;
    public int $value_based = 1;
    public int $new_customer_discount = 1;
    public int $item_specific = 1;
    public int $allow_multiple_order_discounts = 1;

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        
        $config = DiscountConfiguration::first();
        if ($config) {
            $this->coupon_codes = (int) $config->coupon_codes;
            $this->preferred_customers = (int) $config->preferred_customers;
            $this->category_discounts = (int) $config->category_discounts;
            $this->quantity_based = (int) $config->quantity_based;
            $this->value_based = (int) $config->value_based;
            $this->new_customer_discount = (int) $config->new_customer_discount;
            $this->item_specific = (int) $config->item_specific;
            $this->allow_multiple_order_discounts = (int) $config->allow_multiple_order_discounts;
        }
    }

    public function save(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $config = DiscountConfiguration::firstOrNew(['id' => 1]);
        $config->fill([
            'coupon_codes' => $this->coupon_codes,
            'preferred_customers' => $this->preferred_customers,
            'category_discounts' => $this->category_discounts,
            'quantity_based' => $this->quantity_based,
            'value_based' => $this->value_based,
            'new_customer_discount' => $this->new_customer_discount,
            'item_specific' => $this->item_specific,
            'allow_multiple_order_discounts' => $this->allow_multiple_order_discounts,
        ]);
        $config->save();

        session()->flash('status', 'Store discount configuration updated successfully.');
        $this->redirectRoute('admin.discounts.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin-discount-config');
    }
}
