<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class QuickShopModal extends Component
{
    public bool $showModal = false;
    public ?Product $product = null;
    public int $selectedVariantId = 0;
    public int $selectedImageSetId = 0;
    public $quantity = 1;
    public array $selectedAttributes = [];
    public array $customizations = []; // field_id => value
    public bool $personalization_selected = false;
    public string $personalization_text = '';
    public string $cartError = ''; // inline error shown next to Add to Cart button
    public string $custom_amount = ''; // Customer entered or selected donation/bill pay amount

    #[On('open-quick-shop')]
    #[On('openQuickShop')]
    public function openQuickShop($productId = null, $payload = null): void
    {
        if (is_array($productId)) {
            $id = $productId['productId'] ?? $productId['id'] ?? null;
        } elseif ($productId) {
            $id = $productId;
        } elseif (is_array($payload)) {
            $id = $payload['productId'] ?? $payload['id'] ?? null;
        } else {
            $id = $payload;
        }

        if (!$id) {
            return;
        }

        $this->loadProduct((int) $id);
    }

    public function loadProduct(int $productId): void
    {
        $this->resetErrorBag();
        $this->cartError = '';
        $this->quantity = 1;
        $this->customizations = [];
        $this->personalization_selected = false;
        $this->personalization_text = '';
        $this->selectedAttributes = [];
        $this->custom_amount = '';

        $langService = app(\App\Services\LanguageService::class);
        $langIds = array_unique([$langService->currentId(), $langService->defaultId()]);

        $this->product = Product::where('id', $productId)
            ->where('active', 1)
            ->withCurrentTranslations()
            ->with([
                'variants.inventory.warehouseInventories',
                'variants.images',
                'variants.translations'              => fn ($q) => $q->whereIn('language_id', $langIds),
                'categories.translations'            => fn ($q) => $q->whereIn('language_id', $langIds),
                'fields.options',
                'fields.translations'                => fn ($q) => $q->whereIn('language_id', $langIds),
                'fields.options.translations'        => fn ($q) => $q->whereIn('language_id', $langIds),
                'inventoryAlert',
            ])
            ->first();

        if (!$this->product) {
            return;
        }

        $this->product->recalculateRatingIfZero();

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

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->cartError = '';
    }

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

    public function getSelectedVariantProperty(): ?ProductVariant
    {
        if (!$this->product) {
            return null;
        }
        return $this->product->variants->firstWhere('id', $this->selectedVariantId);
    }

    public function getOutOfStockMessageProperty(): ?string
    {
        $variant = $this->selectedVariant;

        if (!$variant) {
            return null;
        }

        if ($variant->download_item) {
            return null;
        }

        if ($variant->inventory) {
            $stock = $variant->inventory->available_stock;
            if ($stock > 0) {
                return null;
            }
        } else {
            return null;
        }

        return $this->product->inventoryAlert?->message ?? null;
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

        if ($this->product) {
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

        if ($this->product) {
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
        }

        return (float) $price;
    }

    public function getUnitSavingsAmountProperty(): float
    {
        $reg = $this->regularPrice;
        $calc = $this->calculatedPrice;
        return max(0.00, round($reg - $calc, 2));
    }

    public function getUnitDiscountPercentProperty(): float
    {
        $reg = $this->regularPrice;
        $calc = $this->calculatedPrice;
        if ($reg <= 0 || $calc >= $reg) {
            return 0.0;
        }
        return round((($reg - $calc) / $reg) * 100, 1);
    }

    public function getFormattedSelectedOptionLabelProperty(): string
    {
        $variant = $this->selectedVariant;
        if (!$variant) {
            return '';
        }

        if ($variant->attributes) {
            $decoded = json_decode($variant->attributes, true);
            if (is_array($decoded) && !empty($decoded)) {
                $langService = app(\App\Services\LanguageService::class);
                $isDefaultLang = $langService->isDefault();
                $currentLangId = $langService->currentId();
                $translationMap = !$isDefaultLang ? $variant->getTranslatedAttributes($currentLangId) : [];

                $parts = [];
                foreach ($decoded as $key => $val) {
                    $displayKey = $translationMap[$key] ?? $key;
                    $displayVal = $translationMap[$val] ?? $val;
                    $parts[] = "{$displayKey}: {$displayVal}";
                }
                return implode(' / ', $parts);
            }
            return (string) $variant->attributes;
        }

        return $variant->sku ?: 'Standard';
    }

    public function getAvailableOptionValuesProperty(): array
    {
        if (!$this->product || $this->product->variants->isEmpty()) {
            return [];
        }

        $allAttributes = [];
        foreach ($this->product->variants as $v) {
            if ($v->attributes) {
                $decoded = json_decode($v->attributes, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $k => $val) {
                        $allAttributes[$k][] = $val;
                    }
                }
            }
        }

        $attributeKeys = array_keys($allAttributes);
        $tree = [];

        foreach ($attributeKeys as $index => $key) {
            $tree[$key] = [];
            $matchingVariants = $this->product->variants;

            for ($i = 0; $i < $index; $i++) {
                $prevKey = $attributeKeys[$i];
                $prevVal = $this->selectedAttributes[$prevKey] ?? null;
                if ($prevVal !== null) {
                    $matchingVariants = $matchingVariants->filter(function ($variant) use ($prevKey, $prevVal) {
                        $attr = json_decode($variant->attributes, true);
                        return is_array($attr) && isset($attr[$prevKey]) && $attr[$prevKey] === $prevVal;
                    });
                }
            }

            $uniqueValues = [];
            foreach ($matchingVariants as $variant) {
                $attr = json_decode($variant->attributes, true);
                if (is_array($attr) && isset($attr[$key])) {
                    $val = $attr[$key];
                    if (!in_array($val, $uniqueValues)) {
                        $uniqueValues[] = $val;
                    }
                }
            }

            $tree[$key] = $uniqueValues;
        }

        return $tree;
    }

    private function initializeSelectedImageSet(): void
    {
        $variant = $this->selectedVariant;
        if ($variant && $variant->images->isNotEmpty()) {
            $this->selectedImageSetId = $variant->images->first()->id;
        } else {
            $this->selectedImageSetId = 0;
        }
    }

    private function initializeSelectedAttributes(): void
    {
        $variant = $this->selectedVariant;
        if ($variant && $variant->attributes) {
            $decoded = json_decode($variant->attributes, true);
            if (is_array($decoded)) {
                $this->selectedAttributes = $decoded;
            } else {
                $this->selectedAttributes = [];
            }
        } else {
            $this->selectedAttributes = [];
        }
    }

    public function selectImageSet(int $imageSetId): void
    {
        $this->selectedImageSetId = $imageSetId;
    }

    public function selectVariant(int $variantId): void
    {
        $variant = $this->product?->variants->firstWhere('id', $variantId);
        if ($variant) {
            $this->selectedVariantId = $variantId;
            $this->initializeSelectedImageSet();
            $this->initializeSelectedAttributes();
            $this->cartError = '';
        }
    }

    public function selectAttribute(string $name, string $value): void
    {
        $this->selectedAttributes[$name] = $value;
        $this->cartError = '';

        if (!$this->product) {
            return;
        }

        $allKeys = [];
        foreach ($this->product->variants as $v) {
            if ($v->attributes) {
                $decoded = json_decode($v->attributes, true);
                if (is_array($decoded)) {
                    $allKeys = array_unique(array_merge($allKeys, array_keys($decoded)));
                }
            }
        }

        $changedIndex = array_search($name, $allKeys);
        if ($changedIndex !== false && (int) $this->product->dependent_variants === 1) {
            foreach ($allKeys as $idx => $key) {
                if ($idx > $changedIndex) {
                    unset($this->selectedAttributes[$key]);
                }
            }
        }

        $availableTree = $this->available_option_values;
        foreach ($allKeys as $idx => $key) {
            if ($changedIndex !== false && $idx > $changedIndex) {
                $validOptions = $availableTree[$key] ?? [];
                if (!empty($validOptions)) {
                    $this->selectedAttributes[$key] = $validOptions[0];
                    $availableTree = $this->available_option_values;
                }
            }
        }

        $match = $this->product->variants->first(function ($variant) {
            $attr = json_decode($variant->attributes, true);
            if (!is_array($attr)) {
                return false;
            }
            foreach ($this->selectedAttributes as $k => $v) {
                if (!isset($attr[$k]) || $attr[$k] !== $v) {
                    return false;
                }
            }
            return true;
        });

        if ($match) {
            $this->selectedVariantId = $match->id;
            $this->initializeSelectedImageSet();
        }
    }

    public function incrementQuantity(): void
    {
        $this->quantity = (int) $this->quantity + 1;
        $this->updatedQuantity();
    }

    public function decrementQuantity(): void
    {
        if ((int) $this->quantity > 1) {
            $this->quantity = (int) $this->quantity - 1;
            $this->updatedQuantity();
        }
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
        $sessionId = \App\Services\CartSessionService::getCartSessionId();
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
            if ($userType != 2 && $variant->isOnSaleActive()) {
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

            // Stripe trial: trial price due today
            if ($variant->hasStripeTrial()) {
                $price = $variant->getTrialPrice() + $variantFee + $customizationSurcharges;
                $discountPrice = max(0, ($variant->public_price + $variantFee + $customizationSurcharges) - $price);
            }
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
        $cartItems = \App\Services\CartSessionService::getCartQuery($sessionId)->get();

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

        $cartItem = \App\Services\CartSessionService::getCartQuery($sessionId)
            ->where('item_name', 'like', '%(' . $variant->sku . ')')
            ->where('item_attributes', $attributesJson)
            ->first();

        // Max qty check
        if ($cartItem && $product && $product->max_qty == 1) {
            $msg = "You can only purchase a maximum of 1 unit of this item per order.";
            $this->cartError = $msg;
            session()->flash('error', $msg);
            if ($product->checkout_redirect == 1 || $product->standalone_purchase == 1) {
                $this->closeModal();
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

        if (\App\Services\GoogleAnalyticsService::isEnabled()) {
            $this->dispatch('ga-ecommerce-event', [
                'event' => 'add_to_cart',
                'data'  => [
                    'currency' => \App\Services\GoogleAnalyticsService::getCurrency(),
                    'value'    => round($price * $qtyToAdd, 2),
                    'items'    => [
                        \App\Services\GoogleAnalyticsService::formatItem($this->product, $variant, $qtyToAdd, $price)
                    ]
                ]
            ]);
        }

        $this->dispatch('cart-updated');
        session()->flash('status', 'Item successfully added to your cart!');

        $this->closeModal();

        // Post-cart cross-sell override
        $postCartCrossSells = $product->crossSells ? $product->crossSells->where('display_on_post_cart', true) : collect();
        if ($postCartCrossSells->isNotEmpty()) {
            return redirect()->route('shop.post-cart', ['variantId' => $variant->id]);
        }

        // Redirect logic
        if ($product && ($product->checkout_redirect == 1 || $product->standalone_purchase == 1)) {
            return redirect()->route('shop.checkout');
        }

        $this->dispatch('show-cart-modal',
            itemName: $this->product->title . ' (' . $variant->sku . ')',
            qty: $qtyToAdd,
        );
    }

    private function resolveItemTaxable(ProductVariant $variant, Product $product): int
    {
        if ((int) ($variant->charge_tax ?? 1) === 1) {
            return 1;
        }
        return \App\Models\ProductField::where('product_id', $product->id)
            ->where('charge_tax', 1)
            ->exists() ? 1 : 0;
    }

    public function getVariantColor(ProductVariant $variant): ?string
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

    private function buildVariantAttributeTranslations(): array
    {
        if (!$this->product) {
            return [];
        }

        $langService = app(\App\Services\LanguageService::class);
        if ($langService->isDefault()) {
            return [];
        }

        $currentLangId = $langService->currentId();
        $result = [];

        foreach ($this->product->variants as $variant) {
            $map = $variant->getTranslatedAttributes($currentLangId);
            if (!empty($map)) {
                $result[$variant->id] = $map;
            }
        }

        return $result;
    }

    public function render(): View
    {
        $selectedVariant = $this->selectedVariant;
        $selectedImageSet = null;

        if ($selectedVariant) {
            if ($this->selectedImageSetId) {
                $selectedImageSet = $selectedVariant->images->firstWhere('id', $this->selectedImageSetId);
            }
            if (!$selectedImageSet) {
                $selectedImageSet = $selectedVariant->images->first();
            }
        }

        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;

        if ($this->product) {
            try {
                $this->product->loadMissing([
                    'translations' => fn ($q) => $q->where(
                        'language_id',
                        app(\App\Services\LanguageService::class)->currentId()
                    )
                ]);
            } catch (\Throwable) {}
        }

        return view('livewire.quick-shop-modal', [
            'selectedVariant'              => $selectedVariant,
            'selectedImageSet'             => $selectedImageSet,
            'userType'                     => $userType,
            'currencySymbol'               => \App\Services\CurrencyService::symbol(),
            'vatInclusive'                 => \App\Services\CurrencyService::isVatInclusive(),
            'merchantVatRate'              => \App\Services\CurrencyService::merchantVatRate(),
            'variantAttributeTranslations' => $this->buildVariantAttributeTranslations(),
            'isDefaultLanguage'            => app(\App\Services\LanguageService::class)->isDefault(),
            'outOfStockMessage'            => $this->outOfStockMessage,
        ]);
    }
}
