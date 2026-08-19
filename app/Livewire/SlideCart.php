<?php

namespace App\Livewire;

use App\Models\ShoppingCartLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class SlideCart extends Component
{
    public bool $isOpen = false;
    public int $cartCount = 0;
    public array $itemsData = [];
    public float $subtotal = 0.00;
    public float $total = 0.00;

    #[On('open-cart')]
    public function openCart(): void
    {
        $this->isOpen = true;
        $this->loadCart();

        if (\App\Services\GoogleAnalyticsService::isEnabled() && !empty($this->itemsData)) {
            $this->dispatch('ga-ecommerce-event', [
                'event' => 'view_cart',
                'data'  => \App\Services\GoogleAnalyticsService::formatCart($this->itemsData, $this->total)
            ]);
        }
    }

    #[On('cart-updated')]
    public function handleCartUpdated(): void
    {
        $this->loadCart();
    }

    public function mount(): void
    {
        $this->loadCart();
    }

    public function closeCart(): void
    {
        $this->isOpen = false;
    }

    private function getCartSessionId(): string
    {
        return \App\Services\CartSessionService::getCartSessionId();
    }

    private function getCartQuery()
    {
        return \App\Services\CartSessionService::getCartQuery();
    }

    public function loadCart(): void
    {
        if (auth()->check()) {
            \App\Services\CartSessionService::associateCartOnLogin(auth()->id());
        }

        $items = $this->getCartQuery()->get();
        $discountResult = \App\Services\DiscountService::applyDiscountsToCart($items, auth()->user());

        $items = $discountResult['items']->map(function ($item) {
            $sku = null;
            if (preg_match('/\(([^)]+)\)$/', $item->item_name, $matches)) {
                $sku = $matches[1];
            }
            $item->max_qty = 0;
            if ($sku) {
                $variant = \App\Models\ProductVariant::where('sku', $sku)->with('product')->first();
                if ($variant && $variant->product) {
                    $item->max_qty = (int) $variant->product->max_qty;
                }
            }
            $attrs = json_decode($item->item_attributes, true) ?: [];
            $item->is_bogo_target = $attrs['is_bogo_target'] ?? false;
            $item->bogo_cart_text = $attrs['bogo_cart_text'] ?? null;
            $item->is_donation_or_bill_pay = $attrs['is_donation_or_bill_pay'] ?? false;
            return $item;
        });

        $this->itemsData = $items->toArray();
        $this->cartCount = (int) $items->sum('item_qty');

        $this->subtotal = (float) $discountResult['subtotal'];
        $this->total = (float) $discountResult['adjusted_subtotal'];

    }

    public function updateQty(int $itemId, float $qty): void
    {
        if ($qty <= 0) {
            $this->removeItem($itemId);
            return;
        }

        $item = $this->getCartQuery()->findOrFail($itemId);

        // Block manual quantity adjustments on BOGO target items and donation/bill pay items
        $attrs = json_decode($item->item_attributes, true) ?: [];
        if (!empty($attrs['is_bogo_target'])) {
            session()->flash('error', $attrs['bogo_cart_text'] ?? 'Quantity of promotional package items cannot be edited manually.');
            return;
        }
        if (!empty($attrs['is_donation_or_bill_pay'])) {
            if ($qty > 1) {
                session()->flash('error', 'Quantity for donation/bill pay items is fixed at 1.');
                return;
            }
        }

        // Find associated variant from item name (which has the SKU inside parentheses)
        $sku = null;
        if (preg_match('/\(([^)]+)\)$/', $item->item_name, $matches)) {
            $sku = $matches[1];
        }

        if ($sku) {
            $variant = \App\Models\ProductVariant::where('sku', $sku)->with(['inventory', 'product'])->first();
            if ($variant) {
                if ($variant->product && $variant->product->max_qty == 1 && $qty > 1) {
                    session()->flash('error', "Maximum 1 unit per order is allowed for '{$item->item_name}'.");
                    return;
                }
                if ($variant->inventory) {
                    $available = $variant->getStockForFulfillment(
                        auth()->user()?->shipping_countrycode,
                        auth()->user()?->shipping_state
                    );
                    if ($qty > $available) {
                        session()->flash('error', "Only {$available} units of '{$item->item_name}' are available in stock.");
                        return;
                    }
                }
            }
        }

        $item->item_qty = $qty;
        $item->save();

        $this->dispatch('cart-updated');
        $this->loadCart();
        session()->flash('status', 'Quantity updated.');
    }

    public function removeItem(int $itemId): void
    {
        $item = $this->getCartQuery()->findOrFail($itemId);

        if (\App\Services\GoogleAnalyticsService::isEnabled()) {
            $sku = null;
            if (preg_match('/\(([^)]+)\)$/', $item->item_name ?? '', $matches)) {
                $sku = $matches[1];
            }
            $this->dispatch('ga-ecommerce-event', [
                'event' => 'remove_from_cart',
                'data'  => [
                    'currency' => \App\Services\GoogleAnalyticsService::getCurrency(),
                    'value'    => round((float)($item->item_price ?? 0) * (int)($item->item_qty ?? 1), 2),
                    'items'    => [
                        [
                            'item_id'   => $sku ?: ('ITEM-' . $item->id),
                            'item_name' => preg_replace('/\s*\([^)]+\)$/', '', $item->item_name ?? 'Product'),
                            'price'     => round((float)($item->item_price ?? 0), 2),
                            'quantity'  => (int)($item->item_qty ?? 1),
                        ]
                    ]
                ]
            ]);
        }

        $item->delete();

        $this->dispatch('cart-updated');
        $this->loadCart();
        session()->flash('status', 'Item removed from cart.');
    }

    public function render(): View
    {
        return view('livewire.slide-cart', [
            'currencySymbol' => \App\Services\CurrencyService::symbol(),
        ]);
    }
}
