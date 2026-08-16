<?php

namespace App\Livewire;

use App\Models\ShoppingCartLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class ShoppingCart extends Component
{
    private function getCartSessionId(): string
    {
        return \App\Services\CartSessionService::getCartSessionId();
    }

    private function getCartQuery()
    {
        return \App\Services\CartSessionService::getCartQuery();
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
        session()->flash('status', 'Quantity updated.');
    }

    public function removeItem(int $itemId): void
    {
        $item = $this->getCartQuery()->findOrFail($itemId);
        $item->delete();

        $this->dispatch('cart-updated');
        session()->flash('status', 'Item removed from cart.');
    }

    public function render(): View
    {
        // Consolidate/transfer guest session if logged in
        if (auth()->check()) {
            $sessionId = $this->getCartSessionId();
            ShoppingCartLog::where('cart_log_session', $sessionId)
                ->where('user_id', 0)
                ->where('order_id', 0)
                ->update(['user_id' => auth()->id()]);
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
            // Decode attributes to retrieve BOGO details
            $attrs = json_decode($item->item_attributes, true) ?: [];
            $item->is_bogo_target = $attrs['is_bogo_target'] ?? false;
            $item->bogo_cart_text = $attrs['bogo_cart_text'] ?? null;
            return $item;
        });

        return view('livewire.shopping-cart', [
            'items'          => $items,
            'subtotal'       => $discountResult['subtotal'],
            'discounts'      => $discountResult['discounts'],
            'total_discount' => $discountResult['total_discount'],
            'total'          => $discountResult['adjusted_subtotal'],
            'currencySymbol' => \App\Services\CurrencyService::symbol(),
        ]);
    }
}
