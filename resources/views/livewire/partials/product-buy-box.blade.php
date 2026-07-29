<div>
    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">{{ $product->title }}</h1>
    <p class="mt-4 text-slate-500 leading-relaxed">{!! $product->parsed_short_description !!}</p>

    <!-- Active Discounts Custom Promo Info -->
    @php
        $promoTexts = \App\Services\DiscountService::getPromotionalTextsForProduct($product);
    @endphp
    @if(count($promoTexts) > 0)
        <div class="mt-4 space-y-3">
            @foreach($promoTexts as $text)
                <div class="p-4 bg-emerald-50/50 border border-emerald-100 rounded-2xl text-emerald-950 text-sm font-medium leading-relaxed prose prose-emerald max-w-none">
                    {!! $text !!}
                </div>
            @endforeach
        </div>
    @endif

    <!-- Price -->
    <div class="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
        <div>
            <span class="text-xs text-slate-400 font-semibold block uppercase tracking-wider">@label('product.price', 'Price')</span>
            @if($selectedVariant)
                @php
                    $displayCalcPrice = $vatInclusive && $merchantVatRate > 0
                        ? $this->calculatedPrice * (1 + $merchantVatRate / 100)
                        : $this->calculatedPrice;
                    $displayRegPrice  = $vatInclusive && $merchantVatRate > 0
                        ? $this->regularPrice * (1 + $merchantVatRate / 100)
                        : $this->regularPrice;
                @endphp
                <div class="flex items-center gap-2 mt-1 flex-wrap">
                    @if($displayCalcPrice < $displayRegPrice)
                        <span class="text-3xl font-extrabold text-slate-900">{{ $currencySymbol }}{{ number_format($displayCalcPrice, 2) }}</span>
                        @if($this->hasQtyDiscount)
                            <span class="text-sm font-semibold text-slate-500">@label('product.each', '/each')</span>
                        @endif
                        <span class="text-lg text-slate-400 line-through font-medium">{{ $currencySymbol }}{{ number_format($displayRegPrice, 2) }}</span>
                        <span class="text-xs font-bold text-red-500 bg-red-50 border border-red-100 rounded-lg px-2 py-0.5 whitespace-nowrap">
                            @label('product.save', 'Save') {{ $currencySymbol }}{{ number_format($displayRegPrice - $displayCalcPrice, 2) }}!
                        </span>
                    @else
                        <span class="text-3xl font-extrabold text-slate-900">{{ $currencySymbol }}{{ number_format($displayCalcPrice, 2) }}</span>
                        @if($this->hasQtyDiscount)
                            <span class="text-sm font-semibold text-slate-500">@label('product.each', '/each')</span>
                        @endif
                    @endif
                    @php
                        $variantFee = $userType == 2 ? $selectedVariant->wholesale_variant_fee : $selectedVariant->variant_fee;
                        $displayVarFee = $vatInclusive && $merchantVatRate > 0
                            ? $variantFee * (1 + $merchantVatRate / 100)
                            : $variantFee;
                    @endphp
                    @if($displayVarFee > 0)
                        <span class="text-[10px] text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg px-2 py-0.5 font-bold whitespace-nowrap">
                            + {{ $currencySymbol }}{{ number_format($displayVarFee, 2) }} @label('product.selection_fee', 'selection fee included')
                        </span>
                    @endif
                </div>
            @else
                <span class="text-sm text-slate-500 font-bold mt-1 block">N/A</span>
            @endif
        </div>

        @if($userType == 2)
            <span class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 rounded-full border border-emerald-100">@label('product.wholesale_rate', 'Wholesale Rate')</span>
        @endif
    </div>

    <!-- Variant Selection -->
    @if($product->variants->count() > 1)
        @if($product->dependent_variants == 1)
            {{-- Dynamic Drill-Down & Dependent Selectors --}}
            @php
                $groupedAttributes = [];
                foreach ($product->variants as $var) {
                    $attrs = json_decode($var->attributes, true) ?: [];
                    foreach ($attrs as $k => $v) {
                        $groupedAttributes[$k][] = $v;
                    }
                }
                foreach ($groupedAttributes as $k => $vals) {
                    $groupedAttributes[$k] = array_unique($vals);
                }
            @endphp

            @if(!empty($groupedAttributes))
                <div class="mt-8 space-y-6 bg-slate-50/50 border border-slate-100 rounded-3xl p-6">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200/60 pb-2">{{ $product->variant_label ?: 'Select Option:' }}</h3>
                    
                    <div class="space-y-5">
                        @foreach($groupedAttributes as $key => $values)
                            <div>
                                <span class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">{{ $key }}</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($values as $value)
                                        @php
                                            // Check if this option is available under current selections of other keys
                                            $isSelectable = false;
                                            foreach ($product->variants as $var) {
                                                $attrs = json_decode($var->attributes, true) ?: [];
                                                if (!isset($attrs[$key]) || $attrs[$key] !== $value) {
                                                    continue;
                                                }
                                                $matchesOthers = true;
                                                foreach ($selectedAttributes as $otherKey => $otherValue) {
                                                    if ($otherKey === $key || $otherValue === null) {
                                                        continue;
                                                    }
                                                    if (!isset($attrs[$otherKey]) || $attrs[$otherKey] !== $otherValue) {
                                                        $matchesOthers = false;
                                                        break;
                                                    }
                                                }
                                                if ($matchesOthers) {
                                                    $isSelectable = true;
                                                    break;
                                                }
                                            }
                                            $isSelected = isset($selectedAttributes[$key]) && $selectedAttributes[$key] === $value;
                                        @endphp
                                        <button 
                                            type="button"
                                            wire:click="selectAttribute('{{ $key }}', '{{ $value }}')"
                                            @disabled(!$isSelectable)
                                            class="px-4 py-2.5 text-xs font-bold border rounded-2xl transition duration-150 focus:outline-none 
                                                {{ $isSelected ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm' : '' }}
                                                {{ !$isSelected && $isSelectable ? 'bg-white text-slate-800 border-slate-200 hover:border-indigo-300' : '' }}
                                                {{ !$isSelectable ? 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed opacity-50' : '' }}"
                                        >
                                            {{ $value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            {{-- Flat options list showing each price / SKU --}}
            <div class="mt-8">
                <label class="text-sm font-bold text-slate-900 block mb-3">{{ $product->variant_label ?: 'Select Option:' }}</label>
                <div class="space-y-3">
                    @foreach($product->variants as $variant)
                        @php
                            $attrs = json_decode($variant->attributes, true) ?: [];
                            $attrStr = collect($attrs)->map(fn($v, $k) => "$k: $v")->implode(', ');
                        @endphp
                        <label class="flex items-center justify-between p-4 bg-white border {{ $selectedVariantId == $variant->id ? 'border-indigo-500 ring-2 ring-indigo-500/10 bg-indigo-50/10' : 'border-slate-200' }} rounded-2xl cursor-pointer hover:border-indigo-300 transition duration-150">
                            <div class="flex items-center gap-3">
                                <input type="radio" wire:model.live="selectedVariantId" name="variant" value="{{ $variant->id }}" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <div>
                                    <span class="text-sm font-bold text-slate-800">{{ $variant->sku }}</span>
                                    @if($attrStr)
                                        <span class="text-xs text-slate-400 block">{{ $attrStr }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-sm font-extrabold text-slate-900 text-right">
                                @php
                                    $varBasePrice = $userType == 2 ? $variant->wholesale_price : ($variant->on_sale && $variant->sale_price > 0 ? $variant->sale_price : $variant->public_price);
                                    $varTotalPrice = $varBasePrice + $variant->variant_fee;
                                    if ($vatInclusive && $merchantVatRate > 0) {
                                        $varTotalPrice = $varTotalPrice * (1 + $merchantVatRate / 100);
                                    }
                                @endphp
                                {{ $currencySymbol }}{{ number_format($varTotalPrice, 2) }}
                                @if($variant->variant_fee > 0)
                                    <span class="text-[10px] font-bold text-indigo-500 block">+{{ $currencySymbol }}{{ number_format($variant->variant_fee * (1 + ($vatInclusive ? $merchantVatRate / 100 : 0)), 2) }} @label('product.selection_fee', 'selection fee')</span>
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <!-- Stock Levels -->
    @if($selectedVariant && !$selectedVariant->download_item && !$product->hide_inventory_levels)
        <div class="mt-6 flex items-center gap-2">
            @php
                $stock = $selectedVariant->inventory ? ($selectedVariant->inventory->quantity_available - $selectedVariant->inventory->reserved_stock) : 0;
            @endphp
            @if($stock > 0)
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs text-slate-500 font-semibold">{{ $stock }} @label('product.in_stock', 'in stock')</span>
            @else
                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                <span class="text-xs text-red-500 font-bold">@label('product.out_of_stock', 'Out of stock')</span>
            @endif
        </div>
    @elseif($selectedVariant && $selectedVariant->download_item)
        <div class="mt-6 flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
            <span class="text-xs text-indigo-600 font-bold">{{ $selectedVariant->download_label ?: siteLabel('product.digital_item', 'Digital Item (Instant Download)') }}</span>
        </div>
    @endif
</div>

{{-- Storefront Dynamic Customization Fields --}}
@if($product->fields->isNotEmpty())
    <div class="mt-8 pt-8 border-t border-slate-100 space-y-6">
        <div>
            <h3 class="text-sm font-bold text-slate-900">@label('product.customize_heading', 'Customize Product')</h3>
            <p class="text-xs text-slate-500 mt-0.5">@label('product.customize_message', 'Please customize your selection below before adding to cart.')</p>
        </div>

        <div class="space-y-4">
            @foreach($product->fields as $field)
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                        <span>{{ $field->label }}</span>
                        @if($field->is_required)
                            <span class="text-red-500 font-bold text-[10px]">*</span>
                            <span class="text-[9px] text-slate-400 font-normal">@label('product.field_required', '(Required)')</span>
                        @endif
                    </label>

                    @if($field->field_type === 'text')
                        <input type="text"
                               wire:model.live="customizations.{{ $field->id }}"
                               placeholder="@label('product.field_enter_details', 'Enter details...')"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs shadow-sm">

                    @elseif($field->field_type === 'textarea')
                        <textarea wire:model.live="customizations.{{ $field->id }}"
                                  placeholder="@label('product.field_enter_details', 'Enter details...')"
                                  rows="3"
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs shadow-sm"></textarea>

                    @elseif($field->field_type === 'select')
                        <select wire:model.live="customizations.{{ $field->id }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                            <option value="">@label('product.field_select_option', '-- Select option --')</option>
                            @foreach($field->options as $opt)
                                @php
                                    $surcharge = $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                                    $feeText = $surcharge > 0 ? " (+\$" . number_format($surcharge, 2) . ")" : "";
                                @endphp
                                <option value="{{ $opt->id }}">{{ $opt->option_value }}{{ $feeText }}</option>
                            @endforeach
                        </select>

                    @elseif($field->field_type === 'radio')
                        <div class="flex flex-col gap-2">
                            @foreach($field->options as $opt)
                                @php
                                    $surcharge = $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                                    $feeText = $surcharge > 0 ? " (+\$" . number_format($surcharge, 2) . ")" : "";
                                @endphp
                                <label class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100/50 transition duration-150 text-xs text-slate-700">
                                    <input type="radio"
                                           wire:model.live="customizations.{{ $field->id }}"
                                           name="custom_field_{{ $field->id }}"
                                           value="{{ $opt->id }}"
                                           class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <span class="font-medium">{{ $opt->option_value }}</span>
                                    @if($surcharge > 0)
                                        <span class="text-indigo-600 font-bold ml-auto">{{ $feeText }}</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>

                    @elseif($field->field_type === 'checkbox')
                        @php
                            $opt = $field->options->first();
                            $surcharge = $opt ? ($userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier) : 0;
                            $feeText = $surcharge > 0 ? " (+\$" . number_format($surcharge, 2) . ")" : "";
                        @endphp
                        <label class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100/50 transition duration-150 text-xs text-slate-700">
                            <input type="checkbox"
                                   wire:model.live="customizations.{{ $field->id }}"
                                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                            <span class="font-medium">{{ $opt->option_value ?? 'Enable' }}</span>
                            @if($surcharge > 0)
                                <span class="text-indigo-600 font-bold ml-auto">{{ $feeText }}</span>
                            @endif
                        </label>

                    @elseif($field->field_type === 'multiselect_checkbox')
                        <div class="flex flex-col gap-2">
                            @foreach($field->options as $opt)
                                @php
                                    $surcharge = $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                                    $feeText = $surcharge > 0 ? " (+\$" . number_format($surcharge, 2) . ")" : "";
                                @endphp
                                <label class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100/50 transition duration-150 text-xs text-slate-700">
                                    <input type="checkbox"
                                           wire:model.live="customizations.{{ $field->id }}.{{ $opt->id }}"
                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <span class="font-medium">{{ $opt->option_value }}</span>
                                    @if($surcharge > 0)
                                        <span class="text-indigo-600 font-bold ml-auto">{{ $feeText }}</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Variant Personalization / Gift Wrap Option --}}
@if($selectedVariant && $selectedVariant->personalization_active)
    <div class="mt-8 pt-8 border-t border-slate-100 space-y-4">
        <label class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition duration-150 text-sm text-slate-700">
            <input type="checkbox"
                   wire:model.live="personalization_selected"
                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5">
            <div class="flex-1">
                <span class="font-bold text-slate-800">{{ $selectedVariant->personalization_label ?: siteLabel('product.gift_wrapping', 'Add Gift Wrapping / Personalization') }}</span>
                @if($selectedVariant->personalization_fee > 0)
                    <span class="text-indigo-600 font-extrabold ml-1.5">(+{{ $currencySymbol }}{{ number_format($selectedVariant->personalization_fee, 2) }})</span>
                @endif
            </div>
        </label>

        @if($personalization_selected)
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 block uppercase tracking-wider">{{ $selectedVariant->personalization_details_label ?: siteLabel('product.personalization', 'Personalization Details / Gift Message') }}</label>
                <textarea wire:model.live="personalization_text"
                          placeholder="{{ $selectedVariant->personalization_placeholder ?: siteLabel('product.personalization_placeholder', 'Enter names for engraving, personalization details, or a custom gift message here...') }}"
                          rows="3"
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm font-medium"></textarea>
            </div>
        @endif
    </div>
@endif

<!-- Quantity & Add to Cart -->
@if($selectedVariant && ($selectedVariant->download_item || ($selectedVariant->inventory && ($selectedVariant->inventory->quantity_available - $selectedVariant->inventory->reserved_stock) > 0)))
    <div id="add-to-cart" class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-3">
        <div class="flex items-center gap-4">
            @if($product->max_qty != 1)
                <div class="flex flex-col w-28 shrink-0">
                    <label class="sr-only">@label('product.quantity', 'Quantity')</label>
                    <input type="number" min="1" step="1" wire:model.live="quantity" class="w-full text-center py-3 bg-slate-50 border @error('quantity') border-red-500 @else border-slate-200 @enderror rounded-2xl focus:outline-none focus:border-indigo-500 text-slate-800 font-bold">
                    @error('quantity')
                        <span class="text-red-500 text-[10px] mt-1 text-center font-semibold">{{ $message }}</span>
                    @enderror
                </div>
            @endif
            <button wire:click="addToCart" wire:loading.attr="disabled" wire:target="addToCart" class="flex-1 py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-md hover:scale-[1.01] transition duration-150 flex items-center justify-center gap-2">
                <svg wire:loading.remove wire:target="addToCart" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <svg wire:loading wire:target="addToCart" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="addToCart">@label('product.add_to_cart', 'Add to Cart')</span>
                <span wire:loading wire:target="addToCart">@label('product.adding', 'Adding...')</span>
            </button>
        </div>

        {{-- Inline Add to Cart error --}}
        @if(session()->has('error') || !empty($cartError))
            <div class="flex items-start gap-2.5 p-3.5 bg-rose-50 border border-rose-100 rounded-2xl text-rose-700 text-sm font-medium">
                <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1">
                    <span class="font-bold block text-rose-800 mb-0.5">@label('product.cart_error', 'Could not add item to cart:')</span>
                    <span>{{ session('error') ?: $cartError }}</span>
                </div>
            </div>
        @endif

        {{-- Live Item Total --}}
        @if($product->show_item_total && $selectedVariant && $quantity >= 1)
            @php
                $qty = max(1, (int) filter_var($quantity, FILTER_VALIDATE_INT));
                $itemTotalUnit = $vatInclusive && $merchantVatRate > 0
                    ? $this->calculatedPrice * (1 + $merchantVatRate / 100)
                    : $this->calculatedPrice;
                $itemTotal = $itemTotalUnit * $qty;
            @endphp
            <div class="flex items-center justify-between px-4 py-3 bg-indigo-50 border border-indigo-100 rounded-2xl mt-1">
                <span class="text-xs font-bold text-indigo-500 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M12 17h.01M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                    @label('product.item_total', 'Item Total')
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-indigo-400 font-medium">{{ $qty }} × {{ $currencySymbol }}{{ number_format($itemTotalUnit, 2) }}</span>
                    <span class="text-slate-300">=</span>
                    <span class="text-lg font-extrabold text-indigo-700">{{ $currencySymbol }}{{ number_format($itemTotal, 2) }}</span>
                </div>
            </div>
        @endif
    </div>
@else
    {{-- Inline Add to Cart error for disabled/unavailable state --}}
    @if(session()->has('error') || !empty($cartError))
        <div class="mt-4 flex items-start gap-2.5 p-3.5 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/50 rounded-2xl text-rose-700 dark:text-rose-300 text-xs font-semibold shadow-sm">
            <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <span class="font-bold block text-rose-800 dark:text-rose-200 mb-0.5">@label('product.cart_error', 'Could not add item to cart:')</span>
                <span>{{ session('error') ?: $cartError }}</span>
            </div>
        </div>
    @endif
    <div class="mt-8 pt-8 border-t border-slate-100">
        <button disabled class="w-full py-3 px-6 bg-slate-100 text-slate-400 font-bold rounded-2xl cursor-not-allowed text-center">
            @label('product.unavailable', 'Currently Unavailable')
        </button>
    </div>
@endif
