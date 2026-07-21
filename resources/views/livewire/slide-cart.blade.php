<div 
    x-data="{ isOpen: @entangle('isOpen') }" 
    x-show="isOpen" 
    class="fixed inset-0 z-50 overflow-hidden" 
    style="display: none;"
    role="dialog" 
    aria-modal="true"
>
    <!-- Background backdrop -->
    <div 
        x-show="isOpen"
        x-transition:enter="ease-in-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        wire:click="closeCart"
        class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
    ></div>

    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
        <!-- Slide-over panel -->
        <div 
            x-show="isOpen"
            x-transition:enter="transform transition ease-in-out duration-300 sm:duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300 sm:duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="w-screen max-w-md bg-white shadow-2xl flex flex-col"
        >
            <!-- Drawer Header -->
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </span>
                    <h2 class="text-lg font-bold text-slate-800">Shopping Cart</h2>
                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-indigo-600 rounded-full">{{ $cartCount }}</span>
                </div>
                <button wire:click="closeCart" type="button" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Drawer Body -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                <!-- Status/Error Alerts inside Side Panel -->
                @if(session()->has('status'))
                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center gap-2 text-emerald-800 text-xs font-semibold">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="p-3 bg-rose-50 rounded-xl border border-rose-100 flex items-center gap-2 text-rose-800 text-xs font-semibold">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if(empty($itemsData))
                    <div class="text-center py-20 text-slate-400 space-y-3">
                        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-700">Your cart is empty</p>
                        <p class="text-xs text-slate-500">Add products to start shopping.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($itemsData as $item)
                            @php
                                $attrs = json_decode($item['item_attributes'], true) ?: [];
                                $customizations = $attrs['customizations'] ?? [];
                                $baseAttrs = collect($attrs)->except('customizations')->toArray();
                                $attrStr = collect($baseAttrs)->map(fn($v, $k) => "$k: $v")->implode(', ');
                            @endphp
                            <div class="py-4 flex gap-4 items-start justify-between">
                                <div class="flex-1 space-y-1">
                                    <h4 class="font-bold text-slate-800 text-sm leading-tight">{{ $item['item_name'] }}</h4>
                                    @if($attrStr)
                                        <p class="text-[11px] text-slate-400 font-medium">{{ $attrStr }}</p>
                                    @endif
                                    @if(!empty($customizations))
                                        <div class="mt-1 space-y-0.5 bg-slate-50 p-2 rounded-lg border border-slate-100 text-[10px]">
                                            @foreach($customizations as $cust)
                                                <div class="text-slate-600">
                                                    <span class="font-bold text-slate-700">{{ $cust['label'] }}:</span>
                                                    <span>{{ $cust['value'] }}</span>
                                                    @if(isset($cust['price_modifier']) && $cust['price_modifier'] > 0)
                                                        <span class="text-indigo-600 font-bold ml-0.5">(+{{ $currencySymbol }}{{ number_format($cust['price_modifier'], 2) }})</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-4 mt-2">
                                        <!-- Quantity controls -->
                                        @if(!empty($item['is_bogo_target']))
                                            <div class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 border border-indigo-100 rounded-xl text-[10px] font-bold text-indigo-700 whitespace-nowrap">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Locked (BOGO)
                                            </div>
                                        @elseif(isset($item['max_qty']) && $item['max_qty'] == 1)
                                            <div class="px-2.5 py-1 bg-slate-100 border border-slate-200 rounded-xl text-[10px] font-bold text-slate-500 whitespace-nowrap">
                                                Qty: 1 (Max limit)
                                            </div>
                                        @else
                                            <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 p-1">
                                                <button 
                                                    wire:click="updateQty({{ $item['id'] }}, {{ $item['item_qty'] - 1 }})"
                                                    type="button" 
                                                    class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-200/50 rounded-lg transition"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                                    </svg>
                                                </button>
                                                <span class="w-8 text-center text-xs font-bold text-slate-800">{{ (int)$item['item_qty'] }}</span>
                                                <button 
                                                    wire:click="updateQty({{ $item['id'] }}, {{ $item['item_qty'] + 1 }})"
                                                    type="button" 
                                                    class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-200/50 rounded-lg transition"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif

                                        @if(isset($item['item_discount_price']) && $item['item_discount_price'] > 0)
                                            <span class="text-xs font-semibold text-slate-500">
                                                {{ $currencySymbol }}{{ number_format($item['item_price'], 2) }} <span class="line-through text-slate-400 text-[10px]">{{ $currencySymbol }}{{ number_format($item['item_price'] + $item['item_discount_price'], 2) }}</span> each
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-500">
                                                {{ $currencySymbol }}{{ number_format($item['item_price'], 2) }} each
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right flex flex-col justify-between items-end h-20">
                                    <button 
                                        wire:click="removeItem({{ $item['id'] }})"
                                        type="button" 
                                        class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded-lg transition"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    @if(isset($item['item_discount_price']) && $item['item_discount_price'] > 0)
                                        <span class="font-bold text-slate-800 text-sm block">
                                            {{ $currencySymbol }}{{ number_format($item['item_qty'] * $item['item_price'], 2) }}
                                        </span>
                                        <span class="line-through text-slate-400 text-[10px] block">
                                            {{ $currencySymbol }}{{ number_format($item['item_qty'] * ($item['item_price'] + $item['item_discount_price']), 2) }}
                                        </span>
                                    @else
                                        <span class="font-bold text-slate-800 text-sm">
                                            {{ $currencySymbol }}{{ number_format($item['item_qty'] * $item['item_price'], 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Drawer Footer -->
            @if(!empty($itemsData))
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50 space-y-4">
                    <div class="flex items-center justify-between text-sm font-semibold text-slate-500">
                        <span>Subtotal</span>
                        <span class="text-slate-800 font-bold">{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($subtotal - $total > 0)
                        <div class="flex items-center justify-between text-xs font-semibold text-emerald-600">
                            <span>Promos / Discounts</span>
                            <span>-{{ $currencySymbol }}{{ number_format($subtotal - $total, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between text-base font-bold text-slate-800 pt-2 border-t border-slate-200">
                        <span>Total</span>
                        <span class="text-indigo-600 text-lg">{{ $currencySymbol }}{{ number_format($total, 2) }}</span>
                    </div>

                    <div class="pt-2">
                        <a 
                            href="{{ route('shop.checkout') }}" 
                            wire:navigate
                            class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-100 transition duration-150"
                        >
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
