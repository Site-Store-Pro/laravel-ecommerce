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

        // Find associated variant from variant_id or item name (SKU in parentheses)
        $variant = null;
        if (!empty($item->variant_id)) {
            $variant = \App\Models\ProductVariant::with(['inventory', 'product'])->find($item->variant_id);
        }
        if (!$variant && preg_match('/\(([^)]+)\)$/', $item->item_name, $matches)) {
            $variant = \App\Models\ProductVariant::where('sku', $matches[1])->with(['inventory', 'product'])->first();
        }

        if ($variant) {
            $otherQty = (int) $this->getCartQuery()
                ->where('id', '!=', $itemId)
                ->where(function ($q) use ($variant) {
                    $q->where('variant_id', $variant->id);
                    if (!empty($variant->sku)) {
                        $q->orWhere('item_name', 'like', '%(' . $variant->sku . ')%');
                    }
                })
                ->sum('item_qty');

            if ($variant->product && (int) $variant->product->max_qty > 0 && ($qty + $otherQty) > (int) $variant->product->max_qty) {
                $maxLimit = (int) $variant->product->max_qty;
                $rem = max(0, $maxLimit - $otherQty);
                session()->flash('error', $maxLimit === 1
                    ? "Maximum 1 unit per order is allowed for '{$item->item_name}'."
                    : "Maximum {$maxLimit} units per order are allowed for '{$item->item_name}'. You can only set this item to {$rem}.");
                return;
            }

            if ($variant->inventory && !$variant->download_item) {
                $available = $variant->getStockForFulfillment(
                    auth()->user()?->shipping_countrycode,
                    auth()->user()?->shipping_state
                );
                if (($qty + $otherQty) > $available) {
                    session()->flash('error', "Only {$available} unit" . ($available === 1 ? '' : 's') . " of '{$item->item_name}' available in stock.");
                    return;
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
            $variant = null;
            if (!empty($item->variant_id)) {
                $variant = \App\Models\ProductVariant::where('id', $item->variant_id)->with('product')->first();
            }
            if (!$variant && preg_match('/\(([^)]+)\)$/', $item->item_name, $matches)) {
                $variant = \App\Models\ProductVariant::where('sku', $matches[1])->with('product')->first();
            }
            $item->max_qty = 0;
            if ($variant && $variant->product) {
                $item->max_qty = (int) $variant->product->max_qty;
            }
            // Decode attributes to retrieve BOGO details
            $attrs = json_decode($item->item_attributes, true) ?: [];
            $item->is_bogo_target = $attrs['is_bogo_target'] ?? false;
            $item->bogo_cart_text = $attrs['bogo_cart_text'] ?? null;
            return $item;
        });

        $gaEcommerceData = null;
        if (\App\Services\GoogleAnalyticsService::isEnabled() && $items->isNotEmpty()) {
            $gaEcommerceData = \App\Services\GoogleAnalyticsService::formatCart($items, (float)$discountResult['adjusted_subtotal']);
        }

        return view('livewire.shopping-cart', [
            'items'           => $items,
            'subtotal'        => $discountResult['subtotal'],
            'discounts'       => $discountResult['discounts'],
            'total_discount'  => $discountResult['total_discount'],
            'total'           => $discountResult['adjusted_subtotal'],
            'currencySymbol'  => \App\Services\CurrencyService::symbol(),
            'gaEcommerceData' => $gaEcommerceData,
        ]);
    }
}
