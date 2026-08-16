<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class FeaturedItemsWidget extends Component
{
    // ── Display config (passed from plugin) ─────────────────────────────────
    public string  $display    = 'grid';
    public int     $max        = 12;
    public string  $sort       = 'random';
    public string  $header     = '';
    public int     $cols       = 4;
    public string  $nav        = 'on';
    public string  $autoplay   = 'on';
    public int     $slides     = 4;
    public int     $speed      = 4000;
    public string  $instanceId = '';
    public string  $showBadge  = 'on';

    public function mount(
        string $display    = 'grid',
        int    $max        = 12,
        string $sort       = 'random',
        string $header     = '',
        int    $cols       = 4,
        string $nav        = 'on',
        string $autoplay   = 'on',
        int    $slides     = 4,
        int    $speed      = 4000,
        string $instanceId = '',
        string $showBadge  = 'on'
    ): void {
        $this->display    = $display;
        $this->max        = $max;
        $this->sort       = $sort;
        $this->header     = $header;
        $this->cols       = max(2, min(6, $cols));
        $this->nav        = $nav;
        $this->autoplay   = $autoplay;
        $this->slides     = max(1, $slides);
        $this->speed      = max(500, $speed);
        $this->instanceId = $instanceId ?: 'fi_' . Str::random(8);
        $this->showBadge  = $showBadge;
    }

    // ── Cart add ─────────────────────────────────────────────────────────────

    private function getCartSessionId(): string
    {
        return \App\Services\CartSessionService::getCartSessionId();
    }

    private function resolveItemTaxable(ProductVariant $variant, ?Product $product): int
    {
        if ((int) ($variant->charge_tax ?? 1) === 1) {
            return 1;
        }
        return \App\Models\ProductField::where('product_id', $product?->id)
            ->where('charge_tax', 1)
            ->exists() ? 1 : 0;
    }

    public function buyNow(int $variantId)
    {
        $variant = ProductVariant::with(['inventory', 'product.fields'])->findOrFail($variantId);
        $product = $variant->product;

        // If product has custom fields or is a donation item → redirect to full product page
        if ($product && ($product->fields->isNotEmpty() || $product->is_donation_or_bill_pay)) {
            return redirect()->route('shop.product', $product->seo_slug);
        }

        $sessionId = $this->getCartSessionId();
        $userId    = auth()->id() ?? 0;

        // Check inventory
        if (!$variant->download_item && $variant->inventory) {
            $available = $variant->getStockForFulfillment(
                auth()->user()?->shipping_countrycode,
                auth()->user()?->shipping_state
            );
            if ($available <= 0) {
                $this->dispatch('show-cart-error', message: 'This item is currently out of stock.');
                return;
            }
        }

        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;

        $price         = $userType == 2 ? $variant->wholesale_price : $variant->public_price;
        $discountPrice = 0;
        if ($userType != 2 && $variant->isOnSaleActive()) {
            $discountPrice = $price - $variant->sale_price;
            $price         = $variant->sale_price;
        }

        $variantFee = $userType == 2 ? $variant->wholesale_variant_fee : $variant->variant_fee;
        if ($variantFee > 0) {
            $price += $variantFee;
        }

        // Standalone / duplicate guards (same logic as ShopCatalog)
        $cartItems = \App\Services\CartSessionService::getCartQuery($sessionId)->get();

        $skusInCart = [];
        foreach ($cartItems as $ci) {
            if (preg_match('/\(([^)]+)\)$/', $ci->item_name, $m)) {
                $skusInCart[] = $m[1];
            }
        }

        if (!empty($skusInCart)) {
            $hasStandalone = \App\Models\ProductVariant::whereIn('sku', $skusInCart)
                ->whereHas('product', fn($q) => $q->where('standalone_purchase', 1))
                ->exists();
            if ($hasStandalone) {
                $this->dispatch('show-cart-error', message: 'Your cart contains a standalone item which cannot be purchased with other items.');
                return;
            }
        }

        if ($product && $product->standalone_purchase == 1 && $cartItems->isNotEmpty()) {
            $onlySame = collect($skusInCart)->every(fn($s) => $s === $variant->sku);
            if (!$onlySame) {
                $this->dispatch('show-cart-error', message: 'This standalone item cannot be purchased with other items. Please empty your cart first.');
                return;
            }
        }

        // IMPORTANT: must also filter by item_name (which encodes the SKU) so we only
        // match THIS product's cart row — not any other simple product whose item_attributes
        // is also an empty string ''.
        $cartItem = ShoppingCartLog::where(function ($q) use ($sessionId, $userId) {
            if ($userId > 0) {
                $q->where('user_id', $userId);
            } else {
                $q->where('cart_log_session', $sessionId)->where('user_id', 0);
            }
        })
            ->where('item_name', 'like', '%(' . $variant->sku . ')')
            ->where('item_attributes', $variant->attributes)
            ->where('order_id', 0)
            ->first();

        if ($cartItem && $product && $product->max_qty == 1) {
            $this->dispatch('show-cart-error', message: 'You can only purchase a maximum of 1 unit of this item per order.');
            if ($product->checkout_redirect == 1 || $product->standalone_purchase == 1) {
                return redirect()->route('shop.checkout');
            }
            return;
        }

        if ($cartItem) {
            $cartItem->item_qty += 1;
            $cartItem->save();
        } else {
            ShoppingCartLog::create([
                'cart_log_session'    => $sessionId,
                'item_name'           => $product->title . ' (' . $variant->sku . ')',
                'item_qty'            => 1,
                'item_price'          => $price,
                'item_discount_price' => $discountPrice,
                'item_attributes'     => $variant->attributes ?? '',
                'item_shippable'      => $variant->shipping,
                'item_weight'         => $variant->weight ?? 0,
                'item_taxable'        => $this->resolveItemTaxable($variant, $product),
                'item_downloadable'   => $variant->download_item,
                'order_id'            => 0,
                'user_id'             => $userId,
            ]);
        }

        $this->dispatch('cart-updated');
        session()->flash('status', 'Item successfully added to your cart!');

        // checkout_redirect or standalone → go straight to checkout
        if ($product && ($product->checkout_redirect == 1 || $product->standalone_purchase == 1)) {
            return redirect()->route('shop.checkout');
        }

        // Fire browser event — the global modal in public.blade.php handles display.
        $this->dispatch('show-cart-modal',
            itemName: $product->title . ' (' . $variant->sku . ')',
            qty: 1,
        );
    }

    // ── Render ───────────────────────────────────────────────────────────────

    public function render(): View
    {
        $query = Product::with(['variants.inventory', 'variants.images'])
            ->withCurrentTranslations()
            ->where('featured_item', 1)
            ->whereHas('variants');

        match ($this->sort) {
            'name'   => $query->orderBy('title'),
            'newest' => $query->orderByDesc('created_at'),
            default  => $query->inRandomOrder(),
        };

        $products = $query->limit($this->max)->get();

        $display    = $this->display;
        $header     = $this->header;
        $cols       = $this->cols;
        $nav        = $this->nav;
        $autoplay   = $this->autoplay;
        $slides     = $this->slides;
        $speed      = $this->speed;
        $instanceId = $this->instanceId;
        $showBadge  = $this->showBadge === 'on';

        return view('livewire.featured-items-widget', compact(
            'products', 'display', 'header', 'cols',
            'nav', 'autoplay', 'slides', 'speed', 'instanceId', 'showBadge'
        ));
    }
}
