<?php

namespace App\Services;

use App\Models\ShoppingCartLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CartSessionService
{
    public const COOKIE_NAME = 'cart_session_id';
    public const COOKIE_LIFETIME_MINUTES = 525600; // 1 year (365 days)

    /**
     * Get the active cart session ID from query parameters (abandoned cart links),
     * existing cookie, or generate a new UUID. Always queues/refreshes persistent cookie.
     */
    public static function getCartSessionId(): string
    {
        // 1. Check if token passed in URL query (e.g. from abandoned cart email link)
        $urlToken = request()->query('cart_token') ?: request()->query('cart_session');
        if (!empty($urlToken) && is_string($urlToken)) {
            $token = trim($urlToken);
            cookie()->queue(self::COOKIE_NAME, $token, self::COOKIE_LIFETIME_MINUTES);
            return $token;
        }

        // 2. Check existing cookie in request
        $cookieSessionId = request()->cookie(self::COOKIE_NAME);
        if (!empty($cookieSessionId) && is_string($cookieSessionId)) {
            $sessionId = trim($cookieSessionId);
            cookie()->queue(self::COOKIE_NAME, $sessionId, self::COOKIE_LIFETIME_MINUTES);
            return $sessionId;
        }

        // 3. Generate a new UUID if no session exists yet
        $newSessionId = (string) Str::uuid();
        cookie()->queue(self::COOKIE_NAME, $newSessionId, self::COOKIE_LIFETIME_MINUTES);

        return $newSessionId;
    }

    /**
     * Get active shopping cart query (order_id = 0) for current user / session.
     * Allows guest users to view retained carts matching their cookie/token
     * without forcing login.
     */
    public static function getCartQuery(?string $sessionId = null): Builder
    {
        $sessionId = $sessionId ?: self::getCartSessionId();
        $userId = auth()->id() ?? 0;

        return ShoppingCartLog::query()
            ->where('order_id', 0)
            ->where(function (Builder $query) use ($sessionId, $userId) {
                if ($userId > 0) {
                    $query->where('user_id', $userId)
                          ->orWhere('cart_log_session', $sessionId);
                } else {
                    $query->where('cart_log_session', $sessionId);
                }
            });
    }

    /**
     * Associate unassigned cart items with logged in user while preserving cart_log_session.
     */
    public static function associateCartOnLogin(int $userId, ?string $sessionId = null): void
    {
        if ($userId <= 0) {
            return;
        }

        $sessionId = $sessionId ?: self::getCartSessionId();
        ShoppingCartLog::where('cart_log_session', $sessionId)
            ->where('order_id', 0)
            ->where('user_id', 0)
            ->update(['user_id' => $userId]);
    }

    /**
     * Calculate total item count in active cart.
     */
    public static function getCartCount(?string $sessionId = null): float
    {
        return (float) self::getCartQuery($sessionId)->sum('item_qty');
    }

    /**
     * Format cart/order item title according to global and per-product SKU & Variant display settings.
     * Combinations:
     * - Both ON: Name (SKU) (Variants)
     * - Only SKU ON: Name (SKU)
     * - Only Variant ON: Name (Variants)
     * - Both OFF: Name
     */
    public static function formatCartItemName(\App\Models\Product $product, \App\Models\ProductVariant $variant): string
    {
        // 1. Resolve Show SKU setting (per-product override takes precedence, fallback to global setting)
        $showSku = ($product->show_sku_in_cart !== null)
            ? (bool) $product->show_sku_in_cart
            : (bool) \App\Models\CmsSetting::isEnabled('cart_show_sku', true);

        // 2. Resolve Show Part Number setting (per-product override, defaults to true)
        $showPartNumber = ($product->show_part_number_in_cart !== null)
            ? (bool) $product->show_part_number_in_cart
            : true;

        // 3. Resolve Show Variant setting (per-product override takes precedence, fallback to global setting)
        $showVariant = ($product->show_variant_in_cart !== null)
            ? (bool) $product->show_variant_in_cart
            : (bool) \App\Models\CmsSetting::isEnabled('cart_show_variant_name', false);

        $name = trim($product->title);

        $skuPart = '';
        if ($showSku && !empty(trim((string)$variant->sku))) {
            $skuPart = ' (' . trim((string)$variant->sku) . ')';
        }

        $partNumberPart = '';
        if ($showPartNumber && !empty(trim((string)$variant->part_number))) {
            $partNumberPart = ' (' . trim((string)$variant->part_number) . ')';
        }

        $variantPart = '';
        $rawAttributes = $variant->getAttribute('attributes') ?: $variant->getAttribute('variant_attributes');
        if ($showVariant && !empty($rawAttributes)) {
            $attrValues = [];
            $raw = $rawAttributes;
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;

            if (is_array($decoded)) {
                foreach ($decoded as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $subV) {
                            $subV = trim((string)$subV);
                            if ($subV !== '') $attrValues[] = $subV;
                        }
                    } else {
                        $v = trim((string)$v);
                        if ($v !== '' && !in_array(strtolower((string)$k), ['customizations', 'is_donation_or_bill_pay', 'custom_amount', 'sku', 'price', 'weight', 'inventory'])) {
                            $attrValues[] = $v;
                        }
                    }
                }
            } elseif (is_string($raw)) {
                $pairs = explode(',', $raw);
                foreach ($pairs as $pair) {
                    if (str_contains($pair, ':')) {
                        [, $val] = explode(':', $pair, 2);
                        $val = trim($val);
                        if ($val !== '') $attrValues[] = $val;
                    }
                }
            }

            if (!empty($attrValues)) {
                $variantStr = implode(' > ', $attrValues);
                $variantPart = ' (' . $variantStr . ')';
            }
        }

        return $name . $skuPart . $partNumberPart . $variantPart;
    }
}
