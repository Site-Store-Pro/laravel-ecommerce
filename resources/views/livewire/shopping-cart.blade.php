<div class="pt-4 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 mb-12">@label('cart.page_heading', 'Your Shopping Cart')</h1>

        @if(session()->has('status'))
            <div class="mb-8 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-8 p-4 bg-rose-50 rounded-2xl border border-rose-100 flex items-center gap-3 text-rose-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($items->isEmpty())
            <div class="text-center py-16 bg-white border border-slate-100 rounded-3xl shadow-sm">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-slate-900">@label('cart.empty_heading', 'Your cart is empty')</h3>
                <p class="mt-1 text-sm text-slate-500">@label('cart.empty_message', 'Add products to your cart to see them here.')</p>
                <div class="mt-6">
                    <a href="{{ route('shop.index') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:opacity-90 shadow-md shadow-indigo-100 transition duration-150">
                        @label('cart.continue_shopping', 'Continue Shopping')
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Cart Items List -->
                <div class="lg:col-span-8 space-y-4">
                    @foreach($items as $item)
                        @php
                            $attrs = json_decode($item->item_attributes, true) ?: [];
                            $customizations = $attrs['customizations'] ?? [];
                            $baseAttrs = collect($attrs)->except('customizations')->toArray();
                            $attrStr = collect($baseAttrs)->map(fn($v, $k) => "$k: $v")->implode(', ');
                        @endphp
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                            <div class="flex items-center gap-4 flex-1">
                                <!-- Visual Indicator -->
                                <span class="p-3 rounded-2xl bg-indigo-50 text-indigo-600 flex-shrink-0">
                                    @if($item->item_downloadable)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    @endif
                                </span>
                                <div class="flex-1">
                                    <h3 class="font-bold text-slate-900">{{ $item->item_name }}</h3>
                                    @if($attrStr)
                                        <span class="text-xs text-slate-400 block mt-1">{{ $attrStr }}</span>
                                    @endif
                                    @if(!empty($customizations))
                                        <div class="mt-2 space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs">
                                            @foreach($customizations as $cust)
                                                <div class="text-slate-600">
                                                    <span class="font-bold text-slate-700">{{ $cust['label'] }}:</span>
                                                    <span>{{ $cust['value'] }}</span>
                                                    @if(isset($cust['price_modifier']) && $cust['price_modifier'] > 0)
                                                <span class="text-indigo-600 font-bold ml-1">(+{{ $currencySymbol }}{{ number_format($cust['price_modifier'], 2) }})</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($item->item_discount_price > 0)
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-sm font-extrabold text-indigo-600">{{ $currencySymbol }}{{ number_format($item->item_price, 2) }}</span>
                                            <span class="text-xs text-slate-400 line-through">{{ $currencySymbol }}{{ number_format($item->item_price + $item->item_discount_price, 2) }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm font-extrabold text-indigo-600 mt-2 block">{{ $currencySymbol }}{{ number_format($item->item_price, 2) }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions & Qty -->
                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                                @if($item->is_bogo_target)
                                    <div class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 border border-indigo-100 rounded-xl text-xs font-bold text-indigo-700 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        @label('cart.locked_bogo', 'Locked (BOGO)')
                                    </div>
                                @elseif($item->max_qty == 1)
                                    <div class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-500 whitespace-nowrap">
                                        @label('cart.qty_max_limit', 'Qty: 1 (Max limit)')
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <button wire:click="updateQty({{ $item->id }}, {{ $item->item_qty - 1 }})" class="p-2 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <span class="w-8 text-center text-sm font-bold text-slate-800">{{ number_format($item->item_qty, 0) }}</span>
                                        <button wire:click="updateQty({{ $item->id }}, {{ $item->item_qty + 1 }})" class="p-2 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                                <button wire:click="removeItem({{ $item->id }})" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-4 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">@label('cart.order_summary', 'Order Summary')</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between text-sm text-slate-500">
                            <span>@label('cart.subtotal', 'Subtotal')</span>
                            <span class="font-semibold text-slate-800">{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if($total_discount > 0)
                            <div class="border-t border-slate-100 pt-3 space-y-2">
                                @foreach($discounts as $disc)
                                    <div class="flex justify-between text-xs text-emerald-600 font-semibold">
                                        <span>@label('cart.discount', 'Discount:') {{ $disc['name'] }}</span>
                                        <span>-{{ $currencySymbol }}{{ number_format($disc['amount'], 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex justify-between text-sm text-slate-500 border-t border-slate-100 pt-3">
                            <span>@label('cart.shipping', 'Shipping')</span>
                            <span class="font-semibold text-emerald-600">@label('cart.shipping_calculated', 'Calculated at checkout')</span>
                        </div>
                        <div class="border-t border-slate-100 pt-4 flex justify-between text-lg font-extrabold text-slate-900">
                            <span>@label('cart.total', 'Total')</span>
                            <span>{{ $currencySymbol }}{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('shop.checkout') }}" wire:navigate class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-md hover:scale-[1.01] transition duration-150 flex items-center justify-center gap-2">
                            @label('cart.proceed_to_checkout', 'Proceed to Checkout')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
