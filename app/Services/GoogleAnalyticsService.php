<?php

namespace App\Services;

use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use Illuminate\Support\Collection;

class GoogleAnalyticsService
{
    /**
     * Check if Google Analytics tracking is enabled in Admin Settings.
     */
    public static function isEnabled(): bool
    {
        return !empty(self::getMeasurementId());
    }

    /**
     * Get the configured Measurement ID (e.g. G-XXXXXXXXXX).
     */
    public static function getMeasurementId(): string
    {
        return trim((string) CmsSetting::get('google_analytics_id', ''));
    }

    /**
     * Get the active store currency code (default: USD).
     */
    public static function getCurrency(): string
    {
        return strtoupper(CurrencyService::code() ?: 'USD');
    }

    /**
     * Format a single product/variant for GA4 items array.
     */
    public static function formatItem(
        Product $product,
        ?ProductVariant $variant = null,
        int|float $qty = 1,
        ?float $overridePrice = null,
        int $index = 0
    ): array {
        $variant = $variant ?: $product->variants->first();
        $price = $overridePrice !== null ? $overridePrice : (float) ($variant?->public_price ?? 0.00);

        $categoryName = $product->categories->first()?->name ?? 'General';
        $brandName = $product->brand?->name ?? '';

        $item = [
            'item_id'       => $variant?->sku ?: (string) $product->id,
            'item_name'     => $product->title,
            'currency'      => self::getCurrency(),
            'price'         => round($price, 2),
            'quantity'      => max(1, (int) $qty),
            'item_category' => $categoryName,
            'index'         => $index,
        ];

        if (!empty($brandName)) {
            $item['item_brand'] = $brandName;
        }

        if ($variant && !empty($variant->attributes)) {
            $attrs = is_array($variant->attributes) ? $variant->attributes : (json_decode($variant->attributes, true) ?: []);
            if (!empty($attrs)) {
                $attrStrings = [];
                foreach ($attrs as $k => $v) {
                    if (is_string($v) || is_numeric($v)) {
                        $attrStrings[] = "{$k}: {$v}";
                    }
                }
                if (!empty($attrStrings)) {
                    $item['item_variant'] = implode(', ', $attrStrings);
                }
            }
        }

        if ($variant && $variant->isOnSaleActive() && $variant->sale_price !== null) {
            $discount = max(0, (float) $variant->public_price - (float) $variant->sale_price);
            if ($discount > 0) {
                $item['discount'] = round($discount, 2);
            }
        }

        return $item;
    }

    /**
     * Format a collection/paginator of products for view_item_list.
     */
    public static function formatItemList(
        Collection|array $products,
        string $listName = 'Catalog Products',
        string $listId = 'catalog_products'
    ): array {
        $items = [];
        $index = 0;

        foreach ($products as $product) {
            if (!$product instanceof Product) {
                continue;
            }
            $items[] = self::formatItem($product, null, 1, null, $index++);
        }

        return [
            'item_list_id'   => $listId,
            'item_list_name' => $listName,
            'items'          => $items,
        ];
    }

    /**
     * Format a shopping cart collection for GA4 cart events (view_cart, begin_checkout).
     */
    public static function formatCart(Collection|array $cartItems, float $totalValue = 0.0, ?string $coupon = null): array
    {
        $items = [];
        $index = 0;
        $calculatedTotal = 0.0;

        foreach ($cartItems as $cartItem) {
            $sku = null;
            if (preg_match('/\(([^)]+)\)$/', $cartItem->item_name ?? '', $matches)) {
                $sku = $matches[1];
            }

            $price = (float) ($cartItem->item_price ?? 0.00);
            $qty = (int) ($cartItem->item_qty ?? 1);
            $calculatedTotal += ($price * $qty);

            $itemData = [
                'item_id'   => $sku ?: ('ITEM-' . ($cartItem->id ?? $index)),
                'item_name' => preg_replace('/\s*\([^)]+\)$/', '', $cartItem->item_name ?? 'Product'),
                'currency'  => self::getCurrency(),
                'price'     => round($price, 2),
                'quantity'  => $qty,
                'index'     => $index++,
            ];

            if (!empty($cartItem->item_discount_price) && (float) $cartItem->item_discount_price > 0) {
                $itemData['discount'] = round((float) $cartItem->item_discount_price, 2);
            }

            $items[] = $itemData;
        }

        $payload = [
            'currency' => self::getCurrency(),
            'value'    => round($totalValue > 0 ? $totalValue : $calculatedTotal, 2),
            'items'    => $items,
        ];

        if (!empty($coupon)) {
            $payload['coupon'] = $coupon;
        }

        return $payload;
    }

    /**
     * Format an Order model for the GA4 purchase event.
     */
    public static function formatOrder(Order $order): array
    {
        $items = [];
        $index = 0;

        foreach ($order->details as $detail) {
            $sku = null;
            if (preg_match('/\(([^)]+)\)$/', $detail->item_name ?? '', $matches)) {
                $sku = $matches[1];
            }

            $price = (float) ($detail->item_price ?? 0.00);
            $qty = (int) ($detail->item_qty ?? 1);

            $itemData = [
                'item_id'   => $sku ?: ('PROD-' . $detail->id),
                'item_name' => preg_replace('/\s*\([^)]+\)$/', '', $detail->item_name ?? 'Product'),
                'currency'  => self::getCurrency(),
                'price'     => round($price, 2),
                'quantity'  => $qty,
                'index'     => $index++,
            ];

            if (!empty($detail->item_discount_price) && (float) $detail->item_discount_price > 0) {
                $itemData['discount'] = round((float) $detail->item_discount_price, 2);
            }

            $items[] = $itemData;
        }

        $payload = [
            'transaction_id' => $order->order_external_id ?: ($order->order_invoice_no ?: (string) $order->id),
            'value'          => round((float) $order->order_total, 2),
            'tax'            => round((float) ($order->order_taxes ?? 0.00), 2),
            'shipping'       => round((float) ($order->order_shipping ?? 0.00), 2),
            'currency'       => self::getCurrency(),
            'items'          => $items,
        ];

        if (!empty($order->order_discounts) && (float) $order->order_discounts > 0) {
            $payload['discount'] = round((float) $order->order_discounts, 2);
        }

        return $payload;
    }
}