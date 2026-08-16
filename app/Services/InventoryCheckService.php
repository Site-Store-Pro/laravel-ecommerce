<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class InventoryCheckService
{
    /**
     * Checks all cart items for available inventory stock.
     * If any non-download item is out of stock (or quantity exceeds available stock),
     * it deletes the item from shopping_cart_log and returns an array of removed item names/SKUs.
     *
     * @param Collection $cartItems
     * @return array List of removed item identifiers. Empty if all items are valid and in stock.
     */
    public static function validateAndCleanCart(Collection $cartItems): array
    {
        $removedItems = [];

        foreach ($cartItems as $item) {
            // Download items do not track physical inventory stock
            if (!empty($item->item_downloadable)) {
                continue;
            }

            // Resolve variant
            $variant = null;
            if (!empty($item->variant_id)) {
                $variant = ProductVariant::with(['inventory.warehouseInventories'])->find($item->variant_id);
            }

            if (!$variant) {
                preg_match('/\(([^)]+)\)$/', $item->item_name, $matches);
                $sku = $matches[1] ?? '';
                if ($sku) {
                    $variant = ProductVariant::with(['inventory.warehouseInventories'])->where('sku', $sku)->first();
                }
            }

            // If variant has an inventory record (stock tracking enabled)
            if ($variant && $variant->inventory) {
                $availableStock = $variant->inventory->available_stock;
                $requestedQty = (int) $item->item_qty;

                if ($availableStock <= 0 || $requestedQty > $availableStock) {
                    $itemLabel = !empty($variant->sku) ? "{$item->item_name} (SKU: {$variant->sku})" : $item->item_name;
                    $removedItems[] = $itemLabel;
                    $item->delete();
                }
            }
        }

        return array_unique($removedItems);
    }

    /**
     * Formats out of stock notification message using dynamic translatable site labels.
     * Managed under /admin/site-labels via keys:
     * - checkout.inventory_out_of_stock_single
     * - checkout.inventory_out_of_stock_multiple
     */
    public static function formatOutOfStockMessage(array $removedItems): string
    {
        if (empty($removedItems)) {
            return '';
        }

        if (count($removedItems) === 1) {
            $template = siteLabel('checkout.inventory_out_of_stock_single', "The item ':item' is out of stock and has been removed from your cart.");
            return str_replace(':item', $removedItems[0], $template);
        }

        $template = siteLabel('checkout.inventory_out_of_stock_multiple', "The following items were out of stock and have been removed from your cart: :items.");
        return str_replace(':items', implode(', ', $removedItems), $template);
    }
}
