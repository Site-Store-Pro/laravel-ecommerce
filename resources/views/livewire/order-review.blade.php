<div class="pt-4 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 mb-12">@label('review.page_heading', 'Order Review & Payment')</h1>

        <!-- Flash Status/Error Messages -->
        @if(session()->has('status'))
            <div class="mb-8 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-8 p-4 bg-red-50 rounded-2xl border border-red-100 flex items-center gap-3 text-red-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
             x-data="paymentHandler('{{ $activeProcessorType }}', '{{ $stripePublishableKey }}', {{ $stripeAddressRequired ? 'true' : 'false' }})">
            <!-- Left Side: Shipping Info & Payment -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Shipping Summary Card -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                        <h2 class="text-lg font-bold text-slate-900">@label('review.delivery_heading', 'Delivery Information')</h2>
                        <a href="{{ route('shop.checkout', ['edit' => 1]) }}" class="text-xs font-bold text-indigo-600 hover:underline">@label('review.edit_details', 'Edit details')</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider mb-1">@label('review.customer_profile', 'Customer Profile')</span>
                            <p class="font-bold text-slate-800">{{ $user->name }}</p>
                            <p class="text-slate-500">{{ $user->email }}</p>
                        </div>
                        @if($requiresShipping)
                            <div>
                                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider mb-1">@label('review.shipping_address', 'Shipping Address')</span>
                                <p class="font-semibold text-slate-700">{{ $user->shipping_address1 }}</p>
                                @if($user->shipping_address2)
                                    <p class="text-slate-500">{{ $user->shipping_address2 }}</p>
                                @endif
                                <p class="text-slate-600">{{ $user->shipping_city }}@if($user->shipping_state), {{ $user->shipping_state }}@endif {{ $user->shopping_postalcode }}</p>
                                <p class="text-slate-500 font-medium">{{ $user->shipping_country }} ({{ $user->shipping_countrycode }})</p>
                            </div>
                        @else
                            <div>
                                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider mb-1">@label('review.fulfillment', 'Fulfillment')</span>
                                <p class="text-slate-500">@label('review.digital_only_message', 'Order contains digital products only. Instant download links will be generated on completion.')</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Shipping Option Selector -->
                @if($requiresShipping && !empty($shippingOptions))
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4">@label('review.shipping_method', 'Shipping Method')</h2>
                        <div class="space-y-3">
                            @foreach($shippingOptions as $opt)
                                <label class="flex items-center justify-between p-4 bg-slate-50 border rounded-2xl cursor-pointer transition duration-150 @if($selectedShippingOption === $opt['id']) border-indigo-500 bg-indigo-50/30 @else border-slate-200 hover:border-indigo-300 @endif">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" wire:model.live="selectedShippingOption" value="{{ $opt['id'] }}" class="text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="text-sm font-bold text-slate-800 block">{{ $opt['name'] }}</span>
                                        </div>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-900">
                                        @if($opt['amount'] == 0)
                                            @label('review.free', 'Free')
                                        @else
                                            ${{ number_format($opt['amount'], 2) }}
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ═══ Custom Fields (position = billing) ═══ --}}
                @if(isset($billingFields) && $billingFields->isNotEmpty())
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">@label('review.additional_info', 'Additional Information')</h2>
                        @foreach($billingFields as $idx => $cf)
                            <div>
                                @if($cf->html_above)
                                    <div class="prose prose-sm text-slate-600 mb-2">{!! $cf->html_above !!}</div>
                                @endif
                                <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">
                                    {{ $cf->label }}
                                    @if($cf->is_required)<span class="text-red-500 ml-0.5">*</span>@endif
                                </label>
                                @if($cf->instructions)
                                    <p class="text-[11px] text-slate-400 mb-1">{{ $cf->instructions }}</p>
                                @endif

                                @if($cf->type === 'textarea')
                                    <textarea wire:model="billingCustomData.{{ $idx }}" rows="3"
                                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm"></textarea>
                                @elseif($cf->type === 'select')
                                    <select wire:model="billingCustomData.{{ $idx }}"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                                        <option value="">— Select —</option>
                                        @foreach($cf->options ?? [] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @elseif($cf->type === 'radio')
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($cf->options ?? [] as $opt)
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" wire:model="billingCustomData.{{ $idx }}" value="{{ $opt }}" class="text-indigo-600">
                                                <span class="text-sm text-slate-700">{{ $opt }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($cf->type === 'checkbox')
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="billingCustomData.{{ $idx }}" class="w-4 h-4 text-indigo-600 rounded">
                                        <span class="text-sm text-slate-700">{{ $cf->label }}</span>
                                    </label>
                                @elseif($cf->type === 'checkbox_group')
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($cf->options ?? [] as $opt)
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" wire:model="billingCustomData.{{ $idx }}" value="{{ $opt }}" class="w-4 h-4 text-indigo-600 rounded">
                                                <span class="text-sm text-slate-700">{{ $opt }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <input type="text" wire:model="billingCustomData.{{ $idx }}"
                                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                                @endif
                                @error("billingCustomData.{$idx}")
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Manual opt-in checkbox at billing step --}}
                @if(isset($checkoutOptinMode) && $checkoutOptinMode === 'manual' && isset($checkoutOptinPosition) && $checkoutOptinPosition === 'billing')
                    <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" wire:model="billingOptIn" id="billing-optin-checkbox"
                                   class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition">
                                {{ $checkoutOptinLabel ?? siteLabel('review.mailing_list', 'Yes, add me to the mailing list') }}
                            </span>
                        </label>
                    </div>
                @endif

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- Payment Method                                          --}}
                {{-- Rendered based on the active processor in the admin.    --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">

                    <div class="flex items-center gap-2 border-b border-slate-100 pb-4 mb-6">
                        <h2 class="text-lg font-bold text-slate-900">@label('review.payment_method', 'Payment Method')</h2>
                        @if($activeProcessorIsSandbox)
                            <span class="text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full border border-amber-200">Sandbox</span>
                        @endif
                    </div>

                    {{-- ─── Error banner ───────────────────────────────── --}}
                    <div x-show="errorMessage" x-cloak
                         class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm font-semibold text-red-700 flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    {{-- ─── Stripe ─────────────────────────────────────── --}}
                    @if($activeProcessorType === 'stripe')
                        {{-- Stripe.js loaded in @push('scripts') below --}}
                        <div class="space-y-4">
                            @if($stripeAddressRequired)
                                <div class="mb-4">
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider font-sans">@label('review.billing_address', 'Billing Address')</label>
                                    <div id="billing-address-element" wire:ignore
                                         class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus-within:border-indigo-500 transition-colors min-h-[50px]"></div>
                                </div>
                            @endif
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider font-sans">
                                    {{ $stripeAddressRequired ? siteLabel('review.payment_details', 'Payment Details') : siteLabel('review.card_details', 'Card Details') }}
                                </label>
                                <div id="stripe-card-element" wire:ignore
                                     class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus-within:border-indigo-500 transition-colors min-h-[44px]"></div>
                                <p class="mt-1.5 text-xs text-slate-400">@label('review.stripe_message', 'Secured by Stripe. Your card details are never stored on our servers.')</p>
                            </div>
                        </div>

                    {{-- ─── Paddle ─────────────────────────────────────── --}}
                    @elseif($activeProcessorType === 'paddle')
                        <div class="space-y-4">
                            {{-- Paddle inline checkout mounts here --}}
                            <div id="paddle-checkout-container" class="paddle-checkout-container w-full bg-white border border-slate-200 rounded-3xl overflow-hidden min-h-[450px]"></div>
                        </div>

                    {{-- ─── PayPal ─────────────────────────────────────── --}}
                    @elseif($activeProcessorType === 'paypal')
                        <div class="space-y-4">
                            <div id="paypal-button-container" class="w-full min-h-[150px] relative z-10" wire:ignore></div>
                        </div>

                    {{-- ─── Test Processor ─────────────────────────────── --}}
                    @else
                        <div class="space-y-4">
                            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 text-xs font-medium text-amber-800 flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span><strong class="block mb-0.5">@label('review.test_mode_heading', 'Test / Simulation Mode')</strong>@label('review.test_mode_message', 'This checkout is using the built-in test processor. No real payment will be processed. Use the options below to simulate different payment outcomes.')</span>
                            </div>

                            {{-- Simulate outcome radios --}}
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-3 p-4 bg-emerald-50 border-2 border-emerald-200 rounded-2xl cursor-pointer hover:border-emerald-400 transition-colors"
                                       :class="{ 'border-emerald-500 bg-emerald-50': $wire.gatewayToken === '' }">
                                    <input type="radio" wire:model="gatewayToken" value="" class="text-emerald-600 focus:ring-emerald-500">
                                    <div>
                                        <span class="text-sm font-bold text-emerald-800 block">@label('review.simulate_success', '✓ Simulate Success')</span>
                                        <span class="text-xs text-emerald-600">@label('review.simulate_success_note', 'Order will be placed and confirmed')</span>
                                    </div>
                                </label>
                                <label class="flex-1 flex items-center gap-3 p-4 bg-red-50 border-2 border-red-200 rounded-2xl cursor-pointer hover:border-red-400 transition-colors"
                                       :class="{ 'border-red-500 bg-red-50': $wire.gatewayToken === 'fail' }">
                                    <input type="radio" wire:model="gatewayToken" value="fail" class="text-red-600 focus:ring-red-500">
                                    <div>
                                        <span class="text-sm font-bold text-red-800 block">@label('review.simulate_failure', '✗ Simulate Failure')</span>
                                        <span class="text-xs text-red-600">@label('review.simulate_failure_note', 'Payment will be declined')</span>
                                    </div>
                                </label>
                            </div>

                            {{-- Dummy credit card form (visual only — no real processing) --}}
                            <div class="p-5 bg-white border border-slate-200 rounded-2xl space-y-4" aria-hidden="true">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Sample Card Form (Test Mode — not processed)</p>
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 block mb-1">@label('review.card_number', 'Card Number')</label>
                                    <div class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <span class="text-sm text-slate-400 font-mono tracking-widest select-none">4242 4242 4242 4242</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="col-span-1">
                                        <label class="text-xs font-semibold text-slate-500 block mb-1">@label('review.expiry', 'Expiry')</label>
                                        <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                            <span class="text-sm text-slate-400 font-mono select-none">12 / 30</span>
                                        </div>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="text-xs font-semibold text-slate-500 block mb-1">@label('review.cvv', 'CVV')</label>
                                        <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                            <span class="text-sm text-slate-400 font-mono select-none">123</span>
                                        </div>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="text-xs font-semibold text-slate-500 block mb-1">@label('review.zip', 'ZIP')</label>
                                        <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                            <span class="text-sm text-slate-400 font-mono select-none">90210</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 block mb-1">@label('review.cardholder_name', 'Cardholder Name')</label>
                                    <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                        <span class="text-sm text-slate-400 select-none">Test Cardholder</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Order Comments -->
                @if($allowComments)
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4">@label('review.order_comments', 'Order Comments')</h2>
                        <div>
                            <textarea wire:model="orderComments" placeholder="@label('review.order_comments_placeholder', 'Add any special instructions or comments about your order here...')" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm"></textarea>
                        </div>
                    </div>
                @endif

            </div>{{-- end lg:col-span-8 --}}

            <!-- Right Side: Order Review & Pricing details -->
            <div class="lg:col-span-4 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">@label('review.review_items', 'Review Items')</h2>

                <!-- Items list -->
                <div class="space-y-4 max-h-60 overflow-y-auto mb-6">
                    @foreach($items as $item)
                        <div class="flex items-center justify-between text-sm gap-4">
                            <div class="flex-1">
                                <span class="font-semibold text-slate-800">{{ $item->item_name }}</span>
                                <span class="text-xs text-slate-400 block">@label('checkout.qty_label', 'Qty:') {{ number_format($item->item_qty, 0) }}</span>
                                @php
                                    $attrs = json_decode($item->item_attributes, true) ?: [];
                                    $customizations = $attrs['customizations'] ?? [];
                                @endphp
                                @if(!empty($customizations))
                                    <div class="mt-1.5 space-y-0.5 text-[11px] text-slate-500 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                        @foreach($customizations as $cust)
                                            <div>
                                                <span class="font-semibold">{{ $cust['label'] }}:</span>
                                                <span>{{ $cust['value'] }}</span>
                                                @if(isset($cust['price_modifier']) && $cust['price_modifier'] > 0)
                                                    <span class="text-indigo-600 font-bold ml-1">(+${{ number_format($cust['price_modifier'], 2) }})</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if($item->item_shippable)
                                    <span class="inline-block bg-indigo-50 text-indigo-700 text-[10px] px-1.5 py-0.5 rounded font-bold mt-1.5">@label('checkout.requires_shipping', 'Requires Shipping')</span>
                                @else
                                    <span class="inline-block bg-teal-50 text-teal-700 text-[10px] px-1.5 py-0.5 rounded font-bold mt-1.5">@label('checkout.digital_delivery', 'Digital Delivery')</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-slate-950 block">${{ number_format($item->item_price * $item->item_qty, 2) }}</span>
                                @if($item->item_discount_price > 0)
                                    <span class="line-through text-slate-400 text-[10px] block">${{ number_format(($item->item_price + $item->item_discount_price) * $item->item_qty, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <div class="flex justify-between text-sm text-slate-500">
                        <span>@label('review.subtotal', 'Subtotal')</span>
                        <span class="font-semibold text-slate-800">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($totalDiscount > 0)
                        @foreach($discounts as $disc)
                            <div class="flex justify-between text-sm font-semibold text-emerald-600">
                                <span>
                                    @if($disc['type_id'] == 1)
                                        @label('checkout.coupon_code', 'Coupon Code') ({{ $disc['code'] }})
                                    @elseif($disc['type_id'] == 2)
                                        @label('review.preferred_discount', 'Preferred Customer Discount')
                                    @elseif($disc['type_id'] == 3)
                                        @label('review.general_discount', 'General Order Discount')
                                    @elseif($disc['type_id'] == 4)
                                        @label('review.new_customer_promo', 'New Customer Promo')
                                    @else
                                        @label('review.promo_discount', 'Promo Discount') ({{ $disc['name'] }})
                                    @endif
                                </span>
                                <span>-${{ number_format($disc['amount'], 2) }}</span>
                            </div>
                        @endforeach
                    @endif

                    @if($requiresShipping)
                        {{-- Tax / VAT display --}}
                        @if($vatInclusive && !$crossBorder)
                            {{-- VAT-inclusive domestic: show embedded VAT as informational sub-line --}}
                            @if($vatEmbed > 0)
                                <div class="flex justify-between text-sm text-slate-400">
                                    <span class="italic">@label('review.includes_tax', 'Includes') {{ $taxLabel }} {{ $currencySymbol }}{{ number_format($vatEmbed, 2) }}</span>
                                    <span></span>
                                </div>
                            @endif
                        @else
                            {{-- US/CA merchant OR cross-border export: show as separate additive line --}}
                            <div class="flex justify-between text-sm text-slate-500">
                                <span>{{ $taxLabel }}</span>
                                <span class="font-semibold text-slate-800">{{ $currencySymbol }}{{ number_format($taxes, 2) }}</span>
                            </div>
                        @endif
                    @endif
                    <div class="flex justify-between text-sm text-slate-500">
                        <span>@label('checkout.shipping', 'Shipping')</span>
                        <span class="font-semibold text-slate-800">{{ $currencySymbol }}{{ number_format($shippingFee, 2) }}</span>
                    </div>
                    @if($handlingFee > 0)
                        <div class="flex justify-between text-sm text-slate-500">
                            <span>@label('review.handling', 'Handling Surcharge')</span>
                            <span class="font-semibold text-slate-800">{{ $currencySymbol }}{{ number_format($handlingFee, 2) }}</span>
                        </div>
                    @endif
                    <div class="border-t border-slate-100 pt-4 flex justify-between text-lg font-extrabold text-slate-900">
                        <span>@label('review.total', 'Total')</span>
                        <span>{{ $currencySymbol }}{{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <div class="mt-8" x-show="processorType !== 'paddle'">
                    {{-- Place Order — inherits the paymentHandler Alpine scope from the payment card above --}}
                    <div x-show="errorMessage" x-cloak
                         class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm font-semibold text-red-700 flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-text="errorMessage"></span>
                    </div>
                    <button x-show="processorType !== 'paypal'" type="button"
                            @click="handlePlaceOrder"
                            :disabled="processing"
                            :class="processing ? 'opacity-70 cursor-not-allowed' : 'hover:scale-[1.01]'"
                            class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-md transition duration-150 flex items-center justify-center gap-2">
                        <svg x-show="!processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <svg x-show="processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!processing">@label('review.place_order', 'Place Order')</span>
                        <span x-show="processing">@label('review.processing', 'Processing...')</span>
                    </button>
                </div>
            </div>{{-- end lg:col-span-4 --}}
        </div>{{-- end grid --}}
    </div>
</div>{{-- end paymentHandler x-data grid --}}

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- Payment Gateway Scripts (conditionally loaded)                         --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
@if($activeProcessorType === 'stripe')
    <script src="https://js.stripe.com/v3/" defer></script>
@elseif($activeProcessorType === 'paddle')
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js" defer></script>
@elseif($activeProcessorType === 'paypal')
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency={{ $currencyCode }}&components=buttons" defer></script>
@endif

<script>
/**
 * paymentHandler(processorType)
 *
 * Alpine.js component that orchestrates the two-step payment flow:
 *   1. preparePayment()  → Livewire method that creates a PaymentIntent (Stripe)
 *                          or a Paddle Transaction, returning gateway data
 *   2. JS SDK processes the payment (Stripe.js / Paddle.js)
 *   3. placeOrder(token) → Livewire method that verifies + records the payment
 *                          and places the order
 *
 * The component is instantiated twice (payment card + button) — Alpine
 * handles both through window-level event coordination.
 */
function paymentHandler(processorType, stripePublishableKey = '', stripeAddressRequired = false) {
    return {
        processorType: processorType,
        stripePublishableKey: stripePublishableKey,
        stripeAddressRequired: stripeAddressRequired,
        processing: false,
        errorMessage: '',

        // Stripe state
        stripe: null,
        cardElement: null,
        addressElement: null,
        elementsInstance: null,

        init() {
            if (this.processorType === 'stripe') {
                this.$nextTick(() => {
                    this.initStripe();
                });
            } else if (this.processorType === 'paddle') {
                this.$nextTick(() => {
                    this.initPaddle();
                });
            } else if (this.processorType === 'paypal') {
                this.$nextTick(() => {
                    this.initPaypal();
                });
            }
        },

        async initStripe() {
            // Wait for Stripe.js to load
            if (typeof Stripe === 'undefined') {
                await new Promise(resolve => {
                    const check = setInterval(() => {
                        if (typeof Stripe !== 'undefined') { clearInterval(check); resolve(); }
                    }, 100);
                });
            }

            if (!this.stripePublishableKey) {
                console.warn('Stripe publishable key is missing.');
                return;
            }

            try {
                this.stripe = Stripe(this.stripePublishableKey);

                const elements = this.stripe.elements({
                    mode: 'payment',
                    amount: Math.round({{ $total }} * 100),
                    currency: '{{ strtolower($currencyCode) }}',
                });
                this.elementsInstance = elements;

                // address:'never'  → toggle ON:  no address in Payment Element; Address Element collects it separately.
                // address:'auto'   → toggle OFF: Stripe shows minimal country+zip inline; nothing to pass manually.
                this.cardElement = elements.create('payment', {
                    fields: {
                        billingDetails: {
                            address: this.stripeAddressRequired ? 'never' : 'auto'
                        }
                    },
                    defaultValues: {
                        billingDetails: {
                            name: '{{ e($user->name) }}',
                            email: '{{ e($user->email) }}',
                        }
                    }
                });
                this.cardElement.mount('#stripe-card-element');

                if (this.stripeAddressRequired) {
                    this.addressElement = elements.create('address', {
                        mode: 'billing',
                        defaultValues: {
                            name: '{{ e($user->name) }}',
                        },
                        autocomplete: { mode: 'automatic' }
                    });
                    this.addressElement.mount('#billing-address-element');
                }

            } catch (err) {
                console.error('Failed to initialize Stripe elements:', err);
                this.errorMessage = '{{ siteLabel('review.stripe_load_error', 'Failed to load Stripe payment form. Please refresh the page.') }}';
            }
        },

        async initPaddle() {
            if (typeof Paddle === 'undefined') {
                await new Promise(resolve => {
                    const check = setInterval(() => {
                        if (typeof Paddle !== 'undefined') { clearInterval(check); resolve(); }
                    }, 100);
                });
            }

            this.processing = true;
            this.errorMessage = '';

            try {
                const data = await this.$wire.preparePayment();

                if (data.error) {
                    this.errorMessage = data.error;
                    this.processing = false;
                    return;
                }

                Paddle.Environment.set(data.environment);
                Paddle.Initialize({
                    token: data.clientToken,
                    eventCallback: async (event) => {
                        if (event.name === 'checkout.completed') {
                            this.processing = true;
                            const transactionId = event.data.transaction_id || event.data.id || data.transactionId;
                            try {
                                await this.$wire.placeOrder(transactionId);
                            } catch (e) {
                                this.errorMessage = e.message || 'Failed to complete order registration.';
                                this.processing = false;
                            }
                        }
                    }
                });

                Paddle.Checkout.open({
                    transactionId: data.transactionId,
                    settings: {
                        displayMode: 'inline',
                        frameTarget: 'paddle-checkout-container',
                        frameInitialHeight: 450,
                        frameStyle: 'width: 100%; border: none;',
                    },
                });

            } catch (err) {
                console.error('Failed to initialize Paddle checkout:', err);
                this.errorMessage = err.message || '{{ siteLabel('review.paddle_load_error', 'Failed to load Paddle payment form.') }}';
            } finally {
                this.processing = false;
            }
        },

        async initPaypal() {
            if (typeof paypal === 'undefined') {
                await new Promise(resolve => {
                    const check = setInterval(() => {
                        if (typeof paypal !== 'undefined') { clearInterval(check); resolve(); }
                    }, 100);
                });
            }

            this.processing = true;
            this.errorMessage = '';

            try {
                paypal.Buttons({
                    createOrder: async (data, actions) => {
                        this.processing = true;
                        this.errorMessage = '';
                        try {
                            const res = await this.$wire.preparePayment();
                            if (res.error) {
                                this.errorMessage = res.error;
                                this.processing = false;
                                return;
                            }
                            return res.orderId;
                        } catch (err) {
                            this.errorMessage = err.message || '{{ siteLabel('review.paypal_order_error', 'Failed to create PayPal order.') }}';
                            this.processing = false;
                        }
                    },
                    onApprove: async (data, actions) => {
                        try {
                            await this.$wire.placeOrder(data.orderID);
                        } catch (err) {
                            this.errorMessage = err.message || '{{ siteLabel('review.paypal_capture_error', 'Payment capture failed.') }}';
                            this.processing = false;
                        }
                    },
                    onCancel: (data) => {
                        this.processing = false;
                        this.errorMessage = '{{ siteLabel('review.paypal_cancelled', 'Payment cancelled.') }}';
                    },
                    onError: (err) => {
                        this.processing = false;
                        this.errorMessage = err.message || '{{ siteLabel('review.paypal_error', 'An error occurred with PayPal.') }}';
                    }
                }).render('#paypal-button-container');
            } catch (err) {
                console.error('Failed to initialize PayPal buttons:', err);
                this.errorMessage = '{{ siteLabel('review.paypal_load_error', 'Failed to load PayPal checkout. Please refresh the page.') }}';
            } finally {
                this.processing = false;
            }
        },

        async handlePlaceOrder() {
            this.errorMessage = '';
            this.processing = true;

            try {
                // For Stripe Payment Element (Deferred Intent Flow), submit fields before async work
                if (this.processorType === 'stripe' && this.elementsInstance) {
                    const { error: submitError } = await this.elementsInstance.submit();
                    if (submitError) {
                        this.errorMessage = submitError.message;
                        this.processing = false;
                        return;
                    }
                }

                // ─── STEP 1: Server prepares the gateway ─────────────────
                const data = await this.$wire.preparePayment();

                if (data.error) {
                    this.errorMessage = data.error;
                    this.processing = false;
                    return;
                }

                // ─── STEP 2: Handle gateway-specific flow ────────────────
                if (data.processor === 'stripe') {
                    await this.handleStripe(data);

                } else if (data.processor === 'paddle') {
                    await this.handlePaddle(data);

                } else {
                    // Test processor — gatewayToken is already synced via wire:model on the radio
                    await this.$wire.placeOrder('');
                }

            } catch (err) {
                this.errorMessage = err.message || 'An unexpected error occurred.';
                this.processing = false;
            }
        },

        // ─── Stripe ────────────────────────────────────────────────────────
        async handleStripe(data) {
            if (!this.stripe) {
                this.stripe = Stripe(data.publishableKey || this.stripePublishableKey);
                const elements = this.stripe.elements({
                    mode: 'payment',
                    amount: Math.round({{ $total }} * 100),
                    currency: '{{ strtolower($currencyCode) }}',
                });
                this.elementsInstance = elements;

                // Determine whether the admin has enabled the "Require full billing address" toggle.
                // This must be resolved BEFORE creating the Payment Element so we can set the
                // correct address field mode:
                //
                //   address: 'never'  → Payment Element shows NO address fields.
                //                       A separate Address Element is mounted below to collect
                //                       the full billing address (toggle ON).
                //
                //   address: 'auto'   → Payment Element shows its own minimal billing fields
                //                       (country + zip) inline in the card widget (toggle OFF).
                //                       No manual address data needs to be passed to confirmPayment.
                const requireFullAddress = data.stripeAddressRequired || this.stripeAddressRequired;

                this.cardElement = elements.create('payment', {
                    fields: {
                        billingDetails: {
                            address: requireFullAddress ? 'never' : 'auto'
                        }
                    },
                    defaultValues: {
                        billingDetails: {
                            name:  '{{ e($user->name) }}',
                            email: '{{ e($user->email) }}',
                        }
                    }
                });
                this.cardElement.mount('#stripe-card-element');

                if (requireFullAddress) {
                    // Mount the dedicated Address Element for full billing address collection.
                    // Its data flows automatically through the shared elements instance
                    // into confirmPayment — no manual passing required.
                    this.addressElement = elements.create('address', {
                        mode: 'billing',
                        defaultValues: {
                            name: '{{ e($user->name) }}',
                        },
                        autocomplete: { mode: 'automatic' }
                    });
                    this.addressElement.mount('#billing-address-element');
                }
                await new Promise(r => setTimeout(r, 300));
            }

            const { paymentIntent, error } = await this.stripe.confirmPayment({
                elements: this.elementsInstance,
                clientSecret: data.clientSecret,
                confirmParams: {
                    return_url: window.location.href,
                    payment_method_data: {
                        billing_details: {
                            name:  '{{ e($user->name) }}',
                            email: '{{ e($user->email) }}',
                        },
                    },
                },
                redirect: 'if_required'
            });

            if (error) {
                this.errorMessage = error.message;
                this.processing = false;
                return;
            }

            await this.$wire.placeOrder(paymentIntent.id);
        },


        // ─── Paddle ────────────────────────────────────────────────────────
        async handlePaddle(data) {
            if (typeof Paddle === 'undefined') {
                this.errorMessage = 'Paddle.js did not load. Please refresh the page.';
                this.processing = false;
                return;
            }

            // Initialize Paddle with the environment + client token
            Paddle.Initialize({
                token: data.clientToken,
                environment: data.environment,
            });

            // Open inline checkout
            await new Promise((resolve, reject) => {
                Paddle.Checkout.open({
                    transactionId: data.transactionId,
                    settings: {
                        displayMode: 'overlay',
                        theme: 'light',
                    },
                    events: {
                        onComplete: async (eventData) => {
                            try {
                                await this.$wire.placeOrder(eventData.data.transaction_id || data.transactionId);
                                resolve();
                            } catch (e) {
                                reject(e);
                            }
                        },
                        onClose: () => {
                            this.errorMessage = 'Payment was cancelled. Please try again.';
                            this.processing = false;
                            resolve(); // don't reject — user deliberately closed
                        },
                    },
                });
            });
        },
    };
}
</script>
