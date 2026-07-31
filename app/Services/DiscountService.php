<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\DiscountConfiguration;
use App\Models\ProductVariant;
use App\Models\ProductQuantityDiscount;
use App\Models\User;
use Carbon\Carbon;

class DiscountService
{
    /**
     * Re-calculate and apply discounts to the current shopping cart items.
     * Modifies the item_price and item_discount_price on the log items, saves them,
     * and returns the order-level discount details and adjusted subtotal.
     */
    public static function applyDiscountsToCart($items, ?User $user = null)
    {
        // 1. Load active discounts & configuration
        $config = DiscountConfiguration::first();
        if (!$config) {
            $config = new DiscountConfiguration(); // defaults
        }

        $now = now();
        $activeDiscounts = Discount::where('is_active', 1)
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('expiration_date')->orWhere('expiration_date', '>=', $now);
            })
            ->get();

        // 2. Identify user type
        $userType = ($user && $user->isWholesale()) ? 2 : 1; // 1 = Public/Retail, 2 = Wholesale
        
        // 3. Extract SKUs and map to ProductVariants
        $skus = [];
        foreach ($items as $item) {
            if (preg_match('/\(([^)]+)\)$/', $item->item_name, $matches)) {
                $skus[$item->id] = $matches[1];
            }
        }

        $variants = ProductVariant::whereIn('sku', array_unique(array_values($skus)))
            ->with(['product.categories', 'quantityDiscounts'])
            ->get()
            ->keyBy('sku');

        // 4. First phase: Apply Item-Level discounts to each item individually
        // Sequence:
        // - Category/SubCat/Style (Collection)
        // - Item Specific
        // - Special Price
        // - Quantity break
        // - Wholesale Price
        // Whichever is the last one in the sequence that is applicable overrides the price.
        
        foreach ($items as $item) {
            $sku = $skus[$item->id] ?? null;
            if (!$sku) continue;
            
            $variant = $variants->get($sku);
            if (!$variant) continue;
            
            $product = $variant->product;
            if (!$product) continue;
            
            $attrs = json_decode($item->item_attributes, true) ?: [];
            if (!empty($attrs['is_donation_or_bill_pay']) || $product->is_donation_or_bill_pay) {
                if (isset($attrs['custom_amount'])) {
                    $item->item_price = (float) $attrs['custom_amount'];
                }
                $item->item_discount_price = 0.00;
                $item->save();
                continue;
            }

            // Base price is the public price (standard retail base)
            $basePrice = (float) $variant->public_price;
            $finalPrice = $basePrice;
            
            // a) Category & Brand
            if ($config->category_discounts) {
                $catIds = $product->categories->pluck('id')->toArray();
                $catDiscs = $activeDiscounts->where('discount_type_id', 5); // 5 = Category & Brand
                
                foreach ($catDiscs as $disc) {
                    if ($disc->wholesale_only && $userType != 2) {
                        continue;
                    }

                    $matchesRule = false;
                    
                    // Check Category ID
                    if ($disc->category_id > 0 && in_array($disc->category_id, $catIds)) {
                        $catSubtotal = $item->item_qty * $basePrice;
                        $qtyMinSatisfied = ($disc->cat_qty_min == 0 || $item->item_qty >= $disc->cat_qty_min) && ($disc->cat_qty_max == 0 || $item->item_qty <= $disc->cat_qty_max);
                        $subtotalMinSatisfied = ($disc->cat_subtotal_min == 0 || $catSubtotal >= $disc->cat_subtotal_min) && ($disc->cat_subtotal_max == 0 || $catSubtotal <= $disc->cat_subtotal_max);
                        
                        if ($qtyMinSatisfied && $subtotalMinSatisfied) {
                            $matchesRule = true;
                        }
                    }

                    // Check Brand ID
                    if ($disc->brand_id > 0 && $product->brand_id == $disc->brand_id) {
                        $brandSubtotal = $item->item_qty * $basePrice;
                        $qtyMinSatisfied = ($disc->brand_qty_min == 0 || $item->item_qty >= $disc->brand_qty_min) && ($disc->brand_qty_max == 0 || $item->item_qty <= $disc->brand_qty_max);
                        $subtotalMinSatisfied = ($disc->brand_subtotal_min == 0 || $brandSubtotal >= $disc->brand_subtotal_min) && ($disc->brand_subtotal_max == 0 || $brandSubtotal <= $disc->brand_subtotal_max);
                        
                        if ($qtyMinSatisfied && $subtotalMinSatisfied) {
                            $matchesRule = true;
                        }
                    }
                    
                    if ($matchesRule) {
                        $val = (float) $disc->value;
                        if ($disc->value_type == 2) { // percent
                            $finalPrice = $basePrice * (1 - $val / 100);
                        } else { // specific amount
                            $finalPrice = max(0, $basePrice - $val);
                        }
                    }
                }
            }
            
            // b) Item Specific Discount
            if ($config->item_specific) {
                $itemDiscs = $activeDiscounts->where('discount_type_id', 6); // 6 = Item-Specific
                foreach ($itemDiscs as $disc) {
                    if ($disc->product_id == $product->id) {
                        if ($disc->wholesale_only && $userType != 2) {
                            continue;
                        }
                        // Validate item rules (qty and subtotal range)
                        $itemSubtotal = $item->item_qty * $basePrice;
                        $qtyMinSatisfied = ($disc->item_qty_min == 0 || $item->item_qty >= $disc->item_qty_min) && ($disc->item_qty_max == 0 || $item->item_qty <= $disc->item_qty_max);
                        $subtotalMinSatisfied = ($disc->item_subtotal_min == 0 || $itemSubtotal >= $disc->item_subtotal_min) && ($disc->item_subtotal_max == 0 || $itemSubtotal <= $disc->item_subtotal_max);
                        
                        if ($qtyMinSatisfied && $subtotalMinSatisfied) {
                            $val = (float) $disc->value;
                            if ($disc->value_type == 2) { // percent
                                $finalPrice = $basePrice * (1 - $val / 100);
                            } else { // specific amount
                                $finalPrice = max(0, $basePrice - $val);
                            }
                        }
                    }
                }
            }
            
            // c) Special Price (Sale Price)
            if ($userType != 2 && $variant->on_sale && $variant->sale_price > 0) {
                $finalPrice = (float) $variant->sale_price;
            }
            
            // d) Item-Based Quantity Discount
            if ($config->quantity_based) {
                $qtyDisc = $variant->quantityDiscounts
                    ->where('qty_min', '<=', (int)$item->item_qty)
                    ->where('qty_max', '>=', (int)$item->item_qty)
                    ->first();
                if ($qtyDisc) {
                    $val = (float) $qtyDisc->discount_value;
                    if ($qtyDisc->value_type == 2) { // percent
                        $finalPrice = $basePrice * (1 - $val / 100);
                    } else { // specific amount
                        $finalPrice = max(0, $basePrice - $val);
                    }
                }
            }
            
            // e) Wholesale Price
            if ($userType == 2 && $variant->wholesale_price > 0) {
                $finalPrice = (float) $variant->wholesale_price;
            }
            
            // Add variant fee and customizations surcharge
            $variantFee = $userType == 2 ? $variant->wholesale_variant_fee : $variant->variant_fee;
            
            // Customization surcharge (options fee)
            $attrs = json_decode($item->item_attributes, true) ?: [];
            $customizations = $attrs['customizations'] ?? [];
            $optionsFee = 0.00;
            foreach ($customizations as $cust) {
                if (isset($cust['price_modifier'])) {
                    $optionsFee += (float)$cust['price_modifier'];
                }
            }
            
            $item->item_price = $finalPrice + $variantFee + $optionsFee;
            $item->item_discount_price = max(0, ($basePrice + $variantFee + $optionsFee) - $item->item_price);
            $item->save();
        }
        
        // 5. BOGO discounts calculation
        // Find BOGO discounts
        $bogoDiscs = $activeDiscounts->where('discount_type_id', 7); // 7 = BOGO
        foreach ($bogoDiscs as $disc) {
            if ($disc->buy_x_get_y > 0 && $disc->product_id_y > 0) {
                // Check if trigger product X is in the cart
                $triggerItem = $items->first(function($i) use ($disc, $skus, $variants) {
                    $sku = $skus[$i->id] ?? null;
                    if (!$sku) return false;
                    $variant = $variants->get($sku);
                    return $variant && $variant->product_id == $disc->buy_x_get_y;
                });
                
                if ($triggerItem && $triggerItem->item_qty >= $disc->free_range1) {
                    // Check if target product Y is in the cart
                    $targetItem = $items->first(function($i) use ($disc, $skus, $variants) {
                        $sku = $skus[$i->id] ?? null;
                        if (!$sku) return false;
                        $variant = $variants->get($sku);
                        return $variant && $variant->product_id == $disc->product_id_y;
                    });
                    
                    if ($targetItem) {
                        // Apply percent off to Y item
                        $baseYPrice = self::getBasePriceForProduct($disc->product_id_y, $userType);
                        $discountedYPrice = $baseYPrice * (1 - $disc->product_y_percent / 100);
                        
                        // Calculate maximum BOGO items that can get discount
                        $timesBogoApplied = floor($triggerItem->item_qty / $disc->free_range1);
                        $bogoQtyMax = $timesBogoApplied * $disc->free_range2;
                        $qtyToDiscount = min($targetItem->item_qty, $bogoQtyMax);
                        
                        $originalPrice = (float) $targetItem->item_price;
                        $newPrice = (($qtyToDiscount * $discountedYPrice) + (($targetItem->item_qty - $qtyToDiscount) * $originalPrice)) / $targetItem->item_qty;
                        
                        $targetItem->item_price = $newPrice;
                        $targetItem->item_discount_price = max(0, $baseYPrice - $newPrice);
                        
                        // Keep track that this item is BOGO restricted (disables editing in cart view)
                        $attrs = json_decode($targetItem->item_attributes, true) ?: [];
                        $attrs['is_bogo_target'] = true;
                        $attrs['bogo_cart_text'] = $disc->bogo_cart_text;
                        $targetItem->item_attributes = json_encode($attrs);
                        
                        $targetItem->save();
                    }
                }
            }
        }
        
        // Recalculate subtotal after all item-level adjustments
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item->item_qty * $item->item_price;
        }
        
        // 6. Complete Order Discounts phase
        // Priority sequence:
        // - Coupon Code (Type 1)
        // - General Order (Type 3)
        // - Preferred User (Type 2)
        // - New User (Type 4)
        $orderDiscounts = [];
        $totalDiscountAmount = 0;
        $adjustedSubtotal = $subtotal;
        
        // Retrieve coupon code from session
        $sessionCoupon = session()->get('coupon_code');
        
        $discountTypesSeq = [1, 3, 2, 4]; // 1=Coupon, 3=General, 2=Preferred, 4=New Customer
        
        foreach ($discountTypesSeq as $typeId) {
            if ($typeId == 1 && !$config->coupon_codes) continue;
            if ($typeId == 2 && !$config->preferred_customers) continue;
            if ($typeId == 3 && !$config->value_based) continue;
            if ($typeId == 4 && !$config->new_customer_discount) continue;
            
            $eligible = $activeDiscounts->where('discount_type_id', $typeId);
            
            if ($typeId == 1) { // Coupon Code
                if (!$sessionCoupon) continue;
                $eligible = $eligible->where('code', $sessionCoupon);
            }
            
            if ($typeId == 2) { // Preferred User
                if (!$user || !$user->preferred_discount_id) continue;
                $eligible = $eligible->where('id', $user->preferred_discount_id);
            }
            
            if ($typeId == 4) { // New Customer
                if (!$user) continue;
                $hasPriorOrders = \App\Models\Order::where('order_user_id', $user->id)->exists();
                if ($hasPriorOrders) continue;
            }
            
            foreach ($eligible as $disc) {
                // Validate general order filters
                if ($adjustedSubtotal < $disc->order_minimum || $adjustedSubtotal > $disc->order_maximum) {
                    if ($typeId == 1) {
                        session()->forget('coupon_code');
                    }
                    continue;
                }
                
                $totalWeight = 0;
                foreach ($items as $i) {
                    $totalWeight += $i->item_qty * $i->item_weight;
                }
                if ($totalWeight < $disc->order_weight_min || $totalWeight > $disc->order_weight_max) {
                    if ($typeId == 1) {
                        session()->forget('coupon_code');
                    }
                    continue;
                }
                
                $totalQty = $items->sum('item_qty');
                if ($totalQty < $disc->order_qty_min || $totalQty > $disc->order_qty_max) {
                    if ($typeId == 1) {
                        session()->forget('coupon_code');
                    }
                    continue;
                }
                
                if ($disc->wholesale_only && $userType != 2) {
                    if ($typeId == 1) {
                        session()->forget('coupon_code');
                    }
                    continue;
                }
                
                // Calculate discount
                $amount = 0;
                $val = (float) $disc->value;
                if ($disc->value_type == 2) { // percent
                    $amount = $adjustedSubtotal * ($val / 100);
                } else { // specific amount
                    $amount = min($adjustedSubtotal, $val);
                }
                
                if ($amount > 0) {
                    $orderDiscounts[] = [
                        'discount_id' => $disc->id,
                        'name' => $disc->name,
                        'code' => $disc->code,
                        'type_id' => $disc->discount_type_id,
                        'amount' => $amount,
                        'free_shipping' => $disc->free_shipping
                    ];
                    $totalDiscountAmount += $amount;
                    $adjustedSubtotal -= $amount;
                    
                    if (!$config->allow_multiple_order_discounts) {
                        break 2; // stop processing any further order discounts
                    }
                }
            }
        }
        
        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'discounts' => $orderDiscounts,
            'total_discount' => $totalDiscountAmount,
            'adjusted_subtotal' => $adjustedSubtotal
        ];
    }

    /**
     * Calculate the final item-level price for a variant based on active discounts.
     * Mimics the logic inside applyDiscountsToCart but for a single variant.
     */
    public static function getDiscountedPriceForVariant(ProductVariant $variant, ?User $user = null, int $quantity = 1): float
    {
        $quantity = max(1, $quantity);
        $userType = ($user && $user->isWholesale()) ? 2 : 1; // 1 = Retail, 2 = Wholesale
        
        $basePrice = (float) $variant->public_price;
        $finalPrice = $basePrice;

        $config = DiscountConfiguration::first();
        if (!$config) {
            $config = new DiscountConfiguration(); // defaults
        }

        $now = now();
        $activeDiscounts = Discount::where('is_active', 1)
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('expiration_date')->orWhere('expiration_date', '>=', $now);
            })
            ->get();

        $product = $variant->product;
        if (!$product) {
            return $finalPrice;
        }

        // a) Category & Brand
        if ($config->category_discounts) {
            $catIds = $product->categories->pluck('id')->toArray();
            $catDiscs = $activeDiscounts->where('discount_type_id', 5); // 5 = Category & Brand
            
            foreach ($catDiscs as $disc) {
                if ($disc->wholesale_only && $userType != 2) {
                    continue;
                }

                $matchesRule = false;
                
                // Check Category ID
                if ($disc->category_id > 0 && in_array($disc->category_id, $catIds)) {
                    $catSubtotal = $quantity * $basePrice;
                    $qtyMinSatisfied = ($disc->cat_qty_min == 0 || $quantity >= $disc->cat_qty_min) && ($disc->cat_qty_max == 0 || $quantity <= $disc->cat_qty_max);
                    $subtotalMinSatisfied = ($disc->cat_subtotal_min == 0 || $catSubtotal >= $disc->cat_subtotal_min) && ($disc->cat_subtotal_max == 0 || $catSubtotal <= $disc->cat_subtotal_max);
                    
                    if ($qtyMinSatisfied && $subtotalMinSatisfied) {
                        $matchesRule = true;
                    }
                }

                // Check Brand ID
                if ($disc->brand_id > 0 && $product->brand_id == $disc->brand_id) {
                    $brandSubtotal = $quantity * $basePrice;
                    $qtyMinSatisfied = ($disc->brand_qty_min == 0 || $quantity >= $disc->brand_qty_min) && ($disc->brand_qty_max == 0 || $quantity <= $disc->brand_qty_max);
                    $subtotalMinSatisfied = ($disc->brand_subtotal_min == 0 || $brandSubtotal >= $disc->brand_subtotal_min) && ($disc->brand_subtotal_max == 0 || $brandSubtotal <= $disc->brand_subtotal_max);
                    
                    if ($qtyMinSatisfied && $subtotalMinSatisfied) {
                        $matchesRule = true;
                    }
                }
                
                if ($matchesRule) {
                    $val = (float) $disc->value;
                    if ($disc->value_type == 2) { // percent
                        $finalPrice = $basePrice * (1 - $val / 100);
                    } else { // specific amount
                        $finalPrice = max(0, $basePrice - $val);
                    }
                }
            }
        }

        // b) Item Specific Discount
        if ($config->item_specific) {
            $itemDiscs = $activeDiscounts->where('discount_type_id', 6); // 6 = Item-Specific
            foreach ($itemDiscs as $disc) {
                if ($disc->product_id == $product->id) {
                    if ($disc->wholesale_only && $userType != 2) {
                        continue;
                    }
                    $itemSubtotal = $quantity * $basePrice;
                    $qtyMinSatisfied = ($disc->item_qty_min == 0 || $quantity >= $disc->item_qty_min) && ($disc->item_qty_max == 0 || $quantity <= $disc->item_qty_max);
                    $subtotalMinSatisfied = ($disc->item_subtotal_min == 0 || $itemSubtotal >= $disc->item_subtotal_min) && ($disc->item_subtotal_max == 0 || $itemSubtotal <= $disc->item_subtotal_max);
                    
                    if ($qtyMinSatisfied && $subtotalMinSatisfied) {
                        $val = (float) $disc->value;
                        if ($disc->value_type == 2) { // percent
                            $finalPrice = $basePrice * (1 - $val / 100);
                        } else { // specific amount
                            $finalPrice = max(0, $basePrice - $val);
                        }
                    }
                }
            }
        }

        // c) Special Price (Sale Price)
        if ($userType != 2 && $variant->on_sale && $variant->sale_price > 0) {
            $finalPrice = (float) $variant->sale_price;
        }

        // d) Item-Based Quantity Discount
        if ($config->quantity_based) {
            $qtyDisc = $variant->quantityDiscounts
                ->where('qty_min', '<=', $quantity)
                ->where('qty_max', '>=', $quantity)
                ->first();
            if ($qtyDisc) {
                $val = (float) $qtyDisc->discount_value;
                if ($qtyDisc->value_type == 2) { // percent
                    $finalPrice = $basePrice * (1 - $val / 100);
                } else { // specific amount
                    $finalPrice = max(0, $basePrice - $val);
                }
            }
        }

        // e) Wholesale Price
        if ($userType == 2 && $variant->wholesale_price > 0) {
            $finalPrice = (float) $variant->wholesale_price;
        }

        return $finalPrice;
    }
    
    private static function getBasePriceForProduct($productId, $userType)
    {
        $variant = ProductVariant::where('product_id', $productId)->first();
        if ($variant) {
            return $userType == 2 ? (float)$variant->wholesale_price : (float)$variant->public_price;
        }
        return 0.00;
    }

    /**
     * Retrieve all active discount promotional texts applicable to a product.
     */
    public static function getPromotionalTextsForProduct(\App\Models\Product $product): array
    {
        $now = now();
        $discounts = Discount::where('is_active', 1)
            ->where('show_get_x_free', 1)
            ->whereNotNull('show_get_x_text')
            ->where('show_get_x_text', '!=', '')
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('expiration_date')->orWhere('expiration_date', '>=', $now);
            })
            ->get();

        $texts = [];
        $catIds = $product->categories->pluck('id')->toArray();

        foreach ($discounts as $disc) {
            $matches = false;

            // 1. Item-Specific (Type 6) or Coupon (Type 1) with product_id
            if (in_array($disc->discount_type_id, [1, 6]) && $disc->product_id == $product->id) {
                $matches = true;
            }

            // 2. BOGO (Type 7) where product is trigger X or target Y
            if ($disc->discount_type_id == 7 && ($disc->buy_x_get_y == $product->id || $disc->product_id_y == $product->id)) {
                $matches = true;
            }

            // 3. Category & Brand (Type 5) matching brand_id or category_id
            if ($disc->discount_type_id == 5) {
                if ($disc->brand_id > 0 && $product->brand_id == $disc->brand_id) {
                    $matches = true;
                }
                if ($disc->category_id > 0 && in_array($disc->category_id, $catIds)) {
                    $matches = true;
                }
            }

            if ($matches) {
                $texts[] = $disc->show_get_x_text;
            }
        }

        return array_unique($texts);
    }
}
