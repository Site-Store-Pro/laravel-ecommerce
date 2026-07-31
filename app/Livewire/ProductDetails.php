<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class ProductDetails extends Component
{
    public Product $product;
    public int $selectedVariantId = 0;
    public int $selectedImageSetId = 0;
    public $quantity = 1;
    public array $selectedAttributes = [];
    public array $customizations = []; // field_id => value
    public bool $personalization_selected = false;
    public string $personalization_text = '';
    public string $cartError = ''; // inline error shown next to Add to Cart button
    public string $custom_amount = ''; // Customer entered or selected donation/bill pay amount


    public function getParsedCustomAmountOptionsProperty(): array
    {
        if (!$this->product || !$this->product->custom_amount_options) {
            return [];
        }

        $parts = explode(',', $this->product->custom_amount_options);
        $options = [];
        foreach ($parts as $part) {
            $clean = trim($part);
            if (is_numeric($clean) && floatval($clean) > 0) {
                $options[] = floatval($clean);
            }
        }
        return array_values(array_unique($options));
    }

    public function mount(string $seo_link): void
    {
        $langService = app(\App\Services\LanguageService::class);
        $langIds = array_unique([$langService->currentId(), $langService->defaultId()]);

        $this->product = Product::where('seo_slug', $seo_link)
            ->withCurrentTranslations()
            ->with([
                'variants.inventory',
                'variants.images',
                'variants.translations'              => fn ($q) => $q->whereIn('language_id', $langIds),
                'categories',
                'fields.options',
                'fields.translations'                => fn ($q) => $q->whereIn('language_id', $langIds),
                'fields.options.translations'        => fn ($q) => $q->whereIn('language_id', $langIds),
                'crossSells.crossSellProduct.variants.images',
            ])
            ->firstOrFail();

        if ($this->product->variants->isNotEmpty()) {
            $this->selectedVariantId = $this->product->variants->first()->id;
            $this->initializeSelectedImageSet();
            $this->initializeSelectedAttributes();
        }

        if ($this->product->is_donation_or_bill_pay && !$this->product->allow_custom_amount) {
            $options = $this->parsed_custom_amount_options;
            if (!empty($options)) {
                $this->custom_amount = (string) $options[0];
            }
        }
    }

    public function getSelectedVariantProperty(): ?ProductVariant
    {
        return $this->product->variants->firstWhere('id', $this->selectedVariantId);
    }

    protected function validationRules(): array
    {
        return [
            'quantity' => 'required|integer|min:1|max:10000',
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'quantity.required' => 'Please enter a quantity.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 10,000.',
        ];
    }

    public function updatedQuantity(): void
    {
        $this->validateOnly('quantity', $this->validationRules(), $this->validationMessages());
    }

    /**
     * True when an item-based quantity-discount tier is actively applied at the current qty.
     * Used by the blade to show "/each" next to the unit price.
     */
    public function getHasQtyDiscountProperty(): bool
    {
        $variant = $this->selectedVariant;
        if (!$variant) {
            return false;
        }

        $qty = max(1, (int) filter_var($this->quantity, FILTER_VALIDATE_INT));

        $config = \App\Models\DiscountConfiguration::first();
        if (!$config || !$config->quantity_based) {
            return false;
        }

        return $variant->quantityDiscounts
            ->where('qty_min', '<=', $qty)
            ->where('qty_max', '>=', $qty)
            ->isNotEmpty();
    }

    public function getCalculatedPriceProperty(): float
    {
        $variant = $this->selectedVariant;
        if (!$variant) {
            return 0.00;
        }

        $qty = filter_var($this->quantity, FILTER_VALIDATE_INT);
        if ($qty === false || $qty < 1) {
            $qty = 1;
        }

        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;
        $price = \App\Services\DiscountService::getDiscountedPriceForVariant($variant, auth()->user(), $qty);

        $variantFee = $userType == 2 ? $variant->wholesale_variant_fee : $variant->variant_fee;
        $price += $variantFee;

        if ($this->personalization_selected && $variant->personalization_active) {
            $price += $variant->personalization_fee;
        }

        // Customizations
        foreach ($this->product->fields as $field) {
            $val = $this->customizations[$field->id] ?? null;
            if (!$val) continue;

            if ($field->field_type === 'checkbox') {
                if ($val) {
                    $opt = $field->options->first();
                    if ($opt) {
                        $price += $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                    }
                }
            } elseif ($field->field_type === 'multiselect_checkbox') {
                if (is_array($val)) {
                    foreach ($val as $optId => $checked) {
                        if ($checked) {
                            $opt = $field->options->firstWhere('id', $optId);
                            if ($opt) {
                                $price += $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                            }
                        }
                    }
                }
            } elseif (in_array($field->field_type, ['select', 'radio'])) {
                $opt = $field->options->firstWhere('id', $val);
                if ($opt) {
                    $price += $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                }
            }
        }

        return (float) $price;
    }

    public function getRegularPriceProperty(): float
    {
        $variant = $this->selectedVariant;
        if (!$variant) {
            return 0.00;
        }

        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;
        $variantFee = $userType == 2 ? $variant->wholesale_variant_fee : $variant->variant_fee;

        $price = $userType == 2 ? $variant->wholesale_price : $variant->public_price;
        $price += $variantFee;

        if ($this->personalization_selected && $variant->personalization_active) {
            $price += $variant->personalization_fee;
        }

        // Customizations
        foreach ($this->product->fields as $field) {
            $val = $this->customizations[$field->id] ?? null;
            if (!$val) continue;

            if ($field->field_type === 'checkbox') {
                if ($val) {
                    $opt = $field->options->first();
                    if ($opt) {
                        $price += $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                    }
                }
            } elseif ($field->field_type === 'multiselect_checkbox') {
                if (is_array($val)) {
                    foreach ($val as $optId => $checked) {
                        if ($checked) {
                            $opt = $field->options->firstWhere('id', $optId);
                            if ($opt) {
                                $price += $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                            }
                        }
                    }
                }
            } elseif (in_array($field->field_type, ['select', 'radio'])) {
                $opt = $field->options->firstWhere('id', $val);
                if ($opt) {
                    $price += $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                }
            }
        }

        return (float) $price;
    }

    public function updatedSelectedVariantId(): void
    {
        $this->initializeSelectedImageSet();
        $this->initializeSelectedAttributes();
        
        $this->personalization_selected = false;
        $this->personalization_text = '';

        $variant = $this->product->variants->firstWhere('id', $this->selectedVariantId);
        $color = $variant ? $this->getVariantColor($variant) : null;

        // Lightweight signal — Alpine already holds all variant images
        $this->dispatch('gallery:variant-changed', variantId: (int) $this->selectedVariantId, color: $color);
    }

    private function initializeSelectedAttributes(): void
    {
        $variant = $this->product->variants->firstWhere('id', $this->selectedVariantId);
        if ($variant) {
            $this->selectedAttributes = json_decode($variant->attributes, true) ?: [];
        } else {
            $this->selectedAttributes = [];
        }
    }

    public function selectAttribute(string $key, string $value): void
    {
        $this->selectedAttributes[$key] = $value;

        // Find a variant that matches all selected attributes
        $matched = $this->product->variants->first(function ($variant) {
            $attrs = json_decode($variant->attributes, true) ?: [];
            foreach ($this->selectedAttributes as $k => $v) {
                if ($v !== null && (!isset($attrs[$k]) || $attrs[$k] !== $v)) {
                    return false;
                }
            }
            return true;
        });

        if ($matched) {
            $this->selectedVariantId = $matched->id;
        } else {
            // Find fallback variant that matches at least the clicked attribute value
            $fallback = $this->product->variants->first(function ($variant) use ($key, $value) {
                $attrs = json_decode($variant->attributes, true) ?: [];
                return isset($attrs[$key]) && $attrs[$key] === $value;
            });
            if ($fallback) {
                $this->selectedVariantId = $fallback->id;
                $this->selectedAttributes = json_decode($fallback->attributes, true) ?: [];
            }
        }

        $this->initializeSelectedImageSet();

        $variant = $this->product->variants->firstWhere('id', $this->selectedVariantId);
        $color = $variant ? $this->getVariantColor($variant) : null;

        $this->dispatch('gallery:variant-changed', variantId: (int) $this->selectedVariantId, color: $color);
    }

    private function initializeSelectedImageSet(): void
    {
        $variant = $this->product->variants->firstWhere('id', $this->selectedVariantId);
        if ($variant && $variant->images->isNotEmpty()) {
            $activeImages = $variant->images->where('active', 1);
            $searchImage  = $activeImages->where('search_image', 1)->first();

            if ($searchImage) {
                $this->selectedImageSetId = $searchImage->id;
            } elseif ($activeImages->isNotEmpty()) {
                $this->selectedImageSetId = $activeImages->first()->id;
            } else {
                $this->selectedImageSetId = 0;
            }
        } else {
            $this->selectedImageSetId = 0;
        }
    }

    public function selectImageSet(int $setId): void
    {
        $this->selectedImageSetId = $setId;
    }

    private function getCartSessionId(): string
    {
        $cookieName = 'cart_session_id';
        $sessionId = request()->cookie($cookieName);

        if (!$sessionId) {
            $sessionId = (string) Str::uuid();
            cookie()->queue($cookieName, $sessionId, 60 * 24 * 30); // 30 days
        }

        return $sessionId;
    }

    public function addToCart()
    {
        $this->cartError = ''; // reset any previous inline error
        $this->validate($this->validationRules(), $this->validationMessages());

        if ($this->selectedVariantId === 0) {
            $this->cartError = 'Please select a variant before adding to cart.';
            return;
        }

        $variant = ProductVariant::with(['inventory', 'product'])->findOrFail($this->selectedVariantId);
        $product = $variant->product;
        $sessionId = $this->getCartSessionId();
        $userId = auth()->id() ?? 0;

        // Donation / Bill Pay validation & price overriding
        if ($product && $product->is_donation_or_bill_pay) {
            $rawAmount = trim($this->custom_amount);
            if ($rawAmount === '' || !is_numeric($rawAmount) || floatval($rawAmount) <= 0) {
                $this->cartError = 'Please enter or select a valid positive donation/bill pay amount.';
                return;
            }

            $enteredAmount = round(floatval($rawAmount), 2);

            if ($product->allow_custom_amount) {
                if ($product->custom_amount_min !== null && $enteredAmount < $product->custom_amount_min) {
                    $this->cartError = 'Amount must be at least $' . number_format($product->custom_amount_min, 2) . '.';
                    return;
                }
                if ($product->custom_amount_max !== null && $enteredAmount > $product->custom_amount_max) {
                    $this->cartError = 'Amount cannot exceed $' . number_format($product->custom_amount_max, 2) . '.';
                    return;
                }
            } else {
                $allowedOptions = $this->parsed_custom_amount_options;
                if (!empty($allowedOptions) && !in_array($enteredAmount, $allowedOptions)) {
                    $this->cartError = 'Please select a valid amount option from the menu.';
                    return;
                }
            }

            // Force quantity to 1 for donation/bill pay items
            $qtyToAdd = 1;
        } else {
            // Force quantity to 1 if max_qty = 1
            $qtyToAdd = ($product && $product->max_qty == 1) ? 1 : $this->quantity;
        }

        // Check inventory
        if (!$variant->download_item && $variant->inventory && !$product->is_donation_or_bill_pay) {
            $available = $variant->getStockForFulfillment(
                auth()->user()?->shipping_countrycode,
                auth()->user()?->shipping_state
            );
            if ($qtyToAdd > $available) {
                $this->cartError = "Only {$available} unit" . ($available === 1 ? '' : 's') . " available in stock.";
                return;
            }
        }

        // Fetch user type
        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;

        // 1. Validate Custom Fields
        foreach ($this->product->fields as $field) {
            if ($field->is_required) {
                $val = $this->customizations[$field->id] ?? null;
                $hasVal = false;
                if ($field->field_type === 'checkbox') {
                    $hasVal = (bool) $val;
                } elseif ($field->field_type === 'multiselect_checkbox') {
                    $hasVal = is_array($val) && collect($val)->contains(true);
                } else {
                    $hasVal = !empty($val);
                }

                if (!$hasVal) {
                    $this->cartError = "The '{$field->label}' field is required before adding to cart.";
                    return;
                }
            }
        }

        // 2. Calculate surcharges and collect details
        $selectedCustomizations = [];
        $customizationSurcharges = 0.00;

        foreach ($this->product->fields as $field) {
            $val = $this->customizations[$field->id] ?? null;
            if (!$val) continue;

            if ($field->field_type === 'checkbox') {
                if ($val) {
                    $opt = $field->options->first();
                    if ($opt) {
                        $surcharge = $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                        $customizationSurcharges += $surcharge;
                        $selectedCustomizations[] = [
                            'field_id' => $field->id,
                            'label' => $field->label,
                            'value' => $opt->option_value,
                            'price_modifier' => $surcharge
                        ];
                    }
                }
            } elseif ($field->field_type === 'multiselect_checkbox') {
                if (is_array($val)) {
                    $values = [];
                    foreach ($val as $optId => $checked) {
                        if ($checked) {
                            $opt = $field->options->firstWhere('id', $optId);
                            if ($opt) {
                                $surcharge = $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                                $customizationSurcharges += $surcharge;
                                $values[] = $opt->option_value . ($surcharge > 0 ? " (+\$" . number_format($surcharge, 2) . ")" : "");
                            }
                        }
                    }
                    if (!empty($values)) {
                        $selectedCustomizations[] = [
                            'field_id' => $field->id,
                            'label' => $field->label,
                            'value' => implode(', ', $values),
                            'price_modifier' => 0.00
                        ];
                    }
                }
            } elseif (in_array($field->field_type, ['select', 'radio'])) {
                $opt = $field->options->firstWhere('id', $val);
                if ($opt) {
                    $surcharge = $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                    $customizationSurcharges += $surcharge;
                    $selectedCustomizations[] = [
                        'field_id' => $field->id,
                        'label' => $field->label,
                        'value' => $opt->option_value,
                        'price_modifier' => $surcharge
                    ];
                }
            } else {
                // text, textarea
                $selectedCustomizations[] = [
                    'field_id' => $field->id,
                    'label' => $field->label,
                    'value' => $val,
                    'price_modifier' => 0.00
                ];
            }
        }

        if ($product && $product->is_donation_or_bill_pay) {
            $price = round(floatval($this->custom_amount), 2);
            $discountPrice = 0.00;
        } else {
            // Fetch user type & base prices
            $price = $userType == 2 ? $variant->wholesale_price : $variant->public_price;
            $discountPrice = 0;
            if ($userType != 2 && $variant->on_sale && $variant->sale_price > 0) {
                $discountPrice = $price - $variant->sale_price;
                $price = $variant->sale_price;
            }

            // Add personalization if selected
            if ($this->personalization_selected && $variant->personalization_active) {
                $price += $variant->personalization_fee;
                $selectedCustomizations[] = [
                    'field_id' => 'personalization',
                    'label' => $variant->personalization_label ?: 'Gift Wrapping / Personalization',
                    'value' => $this->personalization_text ?: 'Yes',
                    'price_modifier' => $variant->personalization_fee
                ];
            }

            // Add variant fees
            $variantFee = $userType == 2 ? $variant->wholesale_variant_fee : $variant->variant_fee;
            $price += $variantFee + $customizationSurcharges;
        }

        // Build unique attributes JSON for cart matching
        $attributesData = json_decode($variant->attributes, true) ?: [];
        if (!empty($selectedCustomizations)) {
            $attributesData['customizations'] = $selectedCustomizations;
        }
        if ($product && $product->is_donation_or_bill_pay) {
            $attributesData['is_donation_or_bill_pay'] = true;
            $attributesData['custom_amount'] = round(floatval($this->custom_amount), 2);
        }
        $attributesJson = json_encode($attributesData);

        // Fetch current active cart items
        $cartItems = ShoppingCartLog::where('order_id', 0)
            ->where(function($query) use ($sessionId, $userId) {
                if ($userId > 0) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('cart_log_session', $sessionId)
                          ->where('user_id', 0);
                }
            })->get();

        $skusInCart = [];
        foreach ($cartItems as $ci) {
            if (preg_match('/\(([^)]+)\)$/', $ci->item_name, $matches)) {
                $skusInCart[] = $matches[1];
            }
        }

        // A. Is there a standalone item already in the cart?
        if (!empty($skusInCart)) {
            $hasStandaloneInCart = \App\Models\ProductVariant::whereIn('sku', $skusInCart)
                ->whereHas('product', function ($q) {
                    $q->where('standalone_purchase', 1);
                })
                ->exists();

            if ($hasStandaloneInCart) {
                $msg = "Your cart contains a standalone item which cannot be purchased with other items.";
                $this->cartError = $msg;
                session()->flash('error', $msg);
                return;
            }
        }

        // B. Is this item a standalone purchase, and the cart has OTHER items?
        if ($product && $product->standalone_purchase == 1 && $cartItems->isNotEmpty()) {
            $onlySameSku = true;
            foreach ($skusInCart as $skuInCart) {
                if ($skuInCart !== $variant->sku) {
                    $onlySameSku = false;
                    break;
                }
            }
            if (!$onlySameSku) {
                $msg = "This standalone item cannot be purchased with other items. Please empty your cart first.";
                $this->cartError = $msg;
                session()->flash('error', $msg);
                return;
            }
        }

        // C. Mixed-cart guard — subscription and non-subscription items cannot coexist.
        $newItemIsSubscription = $variant->isSubscriptionVariant();
        if ($cartItems->isNotEmpty()) {
            // Check what's currently in the cart
            $cartHasSubscription = false;
            $cartHasRegular      = false;
            foreach ($cartItems as $ci) {
                if (!empty($ci->variant_id)) {
                    $cv = \App\Models\ProductVariant::find($ci->variant_id);
                    if ($cv) {
                        if ($cv->isSubscriptionVariant()) {
                            $cartHasSubscription = true;
                        } else {
                            $cartHasRegular = true;
                        }
                        continue;
                    }
                }
                // Legacy rows without variant_id: assume regular
                $cartHasRegular = true;
            }

            if ($newItemIsSubscription && $cartHasRegular) {
                $msg = 'Subscription items cannot be combined with regular items. Please remove existing cart items before adding a subscription.';
                $this->cartError = $msg;
                session()->flash('error', $msg);
                return;
            }
            if (!$newItemIsSubscription && $cartHasSubscription) {
                $msg = 'Regular items cannot be combined with subscription items. Please remove the subscription item from your cart first.';
                $this->cartError = $msg;
                session()->flash('error', $msg);
                return;
            }
        }

        // Check if THIS specific item is already in cart.
        // IMPORTANT: must also filter by item_name (which encodes the SKU) so we only
        // match THIS product's cart row — not any other simple product whose item_attributes
        // is also an empty string ''.
        $cartItem = ShoppingCartLog::where(function($query) use ($sessionId, $userId) {
            if ($userId > 0) {
                $query->where('user_id', $userId);
            } else {
                $query->where('cart_log_session', $sessionId)
                      ->where('user_id', 0);
            }
        })
        ->where('item_name', 'like', '%(' . $variant->sku . ')')
        ->where('item_attributes', $attributesJson)
        ->where('order_id', 0)
        ->first();

        // C. If max_qty = 1, prevent adding it again if it exists
        if ($cartItem && $product && $product->max_qty == 1) {
            $msg = "You can only purchase a maximum of 1 unit of this item per order.";
            $this->cartError = $msg;
            session()->flash('error', $msg);
            if ($product->checkout_redirect == 1 || $product->standalone_purchase == 1) {
                return redirect()->route('shop.checkout');
            }
            return;
        }

        if ($cartItem) {
            $cartItem->item_qty += $qtyToAdd;
            $cartItem->save();
        } else {
            ShoppingCartLog::create([
                'cart_log_session' => $sessionId,
                'item_name'        => $this->product->title . ' (' . $variant->sku . ')',
                'item_qty'         => $qtyToAdd,
                'item_price'       => $price,
                'item_discount_price' => $discountPrice,
                'item_attributes'  => $attributesJson,
                'item_shippable'   => $variant->shipping,
                'item_weight'      => $variant->weight ?? 0,
                'item_taxable'     => $this->resolveItemTaxable($variant, $this->product),
                'item_downloadable'=> $variant->download_item,
                'variant_id'       => $variant->id,
                'order_id'         => 0,
                'user_id'          => $userId
            ]);
        }

        $this->addedItemName = $this->product->title . ' (' . $variant->sku . ')';
        $this->addedQty = $qtyToAdd;

        $this->dispatch('cart-updated');
        session()->flash('status', 'Item successfully added to your cart!');

        // Post-cart cross-sell override — takes priority over checkout_redirect and modal
        $postCartCrossSells = $product->crossSells->where('display_on_post_cart', true);
        if ($postCartCrossSells->isNotEmpty()) {
            return redirect()->route('shop.post-cart', ['variantId' => $variant->id]);
        }

        // Redirect logic
        if ($product && ($product->checkout_redirect == 1 || $product->standalone_purchase == 1)) {
            return redirect()->route('shop.checkout');
        }

        // Fire browser event — the global modal in public.blade.php handles display.
        $this->dispatch('show-cart-modal',
            itemName: $this->product->title . ' (' . $variant->sku . ')',
            qty: $qtyToAdd,
        );
    }

    public function closeModal(): void
    {
        // kept for backwards compatibility — the global modal closes itself via Alpine
    }

    public function render(): View
    {
        $selectedVariant = $this->product->variants->firstWhere('id', (int) $this->selectedVariantId);
        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;

        $selectedImageSet = null;
        if ($selectedVariant && $this->selectedImageSetId > 0) {
            $selectedImageSet = $selectedVariant->images->firstWhere('id', $this->selectedImageSetId);
        }

        $category = $this->product->categories->first();
        $breadcrumbs = $category ? $category->ancestorsAndSelf()->withCurrentTranslations()->get()->reverse() : collect();

        // ── Related / recommended products — cross-sells with display_on_item_view ──
        $relatedProducts = collect();
        if ($this->product->relationLoaded('crossSells') || true) {
            $relatedProducts = $this->product->crossSells
                ->where('display_on_item_view', true)
                ->map(fn($cs) => $cs->crossSellProduct->load(['variants' => fn($q) => $q->limit(1), 'variants.images' => fn($q) => $q->where('active', 1)->limit(1)]))
                ->filter()
                ->values();
        }

        $metaTitle = $this->product->meta_title ?: $this->product->title;

        // Load translations for current language (enables automatic attribute translation)
        try {
            $this->product->loadMissing([
                'translations' => fn($q) => $q->where(
                    'language_id',
                    app(\App\Services\LanguageService::class)->currentId()
                )
            ]);
        } catch (\Throwable) {}

        return view('livewire.product-details', [
            'selectedVariant'  => $selectedVariant,
            'selectedImageSet' => $selectedImageSet,
            'userType'         => $userType,
            'breadcrumbs'      => $breadcrumbs,
            'relatedProducts'  => $relatedProducts,
            'currencySymbol'   => \App\Services\CurrencyService::symbol(),
            'vatInclusive'     => \App\Services\CurrencyService::isVatInclusive(),
            'merchantVatRate'  => \App\Services\CurrencyService::merchantVatRate(),
        ])->layout('layouts.public', ['metaTitle' => $metaTitle]);
    }

    /**
     * Determine if a cart item is taxable.
     * Returns 1 if the variant has charge_tax=1 OR if ANY product field
     * on the product has charge_tax=1 (OR logic — most permissive wins).
     */
    private function resolveItemTaxable(\App\Models\ProductVariant $variant, $product): int
    {
        if ((int)($variant->charge_tax ?? 1) === 1) {
            return 1;
        }
        return \App\Models\ProductField::where('product_id', $product->id)
            ->where('charge_tax', 1)
            ->exists() ? 1 : 0;
    }

    /**
     * Helper to extract the color, colour, shade, or tint attribute from the variant's attributes JSON.
     */
    public function getVariantColor(\App\Models\ProductVariant $variant): ?string
    {
        if (!$variant || empty($variant->attributes)) {
            return null;
        }

        $decoded = json_decode($variant->attributes, true);
        if (is_array($decoded)) {
            $colorKeys = ['color', 'colour', 'shade', 'tint'];
            foreach ($decoded as $key => $val) {
                if (in_array(strtolower($key), $colorKeys)) {
                    return trim($val);
                }
            }
        }

        // Fallback for non-JSON string labels (just in case they exist, though we focus on JSON)
        $attributesStr = $variant->attributes;
        if (str_contains($attributesStr, '/')) {
            $parts = array_map('trim', explode('/', $attributesStr));
            $sizeIndicators = ['small', 'medium', 'large', 'xl', 'xxl', 'xxxl', 'xs', 's', 'm', 'l', 'size', 'standard'];
            foreach ($parts as $part) {
                $lowerPart = strtolower($part);
                $isSize = false;
                foreach ($sizeIndicators as $indicator) {
                    if (str_contains($lowerPart, $indicator)) {
                        $isSize = true;
                        break;
                    }
                }
                if (!$isSize) {
                    return $part;
                }
            }
            return $parts[0];
        }

        $lower = strtolower(trim($attributesStr));
        if (!in_array($lower, ['standard', 'none', 'default'])) {
            $sizeIndicators = ['small', 'medium', 'large', 'xl', 'xxl', 'xxxl', 'xs', 'size'];
            $isSize = false;
            foreach ($sizeIndicators as $indicator) {
                if (str_contains($lower, $indicator)) {
                    $isSize = true;
                    break;
                }
            }
            if (!$isSize) {
                return trim($attributesStr);
            }
        }

        return null;
    }
}
