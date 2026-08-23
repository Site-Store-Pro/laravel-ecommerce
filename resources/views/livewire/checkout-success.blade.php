<div x-data="{}" 
     x-init="@if(!empty($gaEcommerceData)) if(typeof window.trackGaEvent === 'function') { window.trackGaEvent('purchase', {{ json_encode($gaEcommerceData) }}); } @endif"
     class="pt-4 pb-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Confirmation Banner Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm text-center space-y-6">
            <div class="inline-flex items-center justify-center p-4 rounded-full bg-emerald-50 text-emerald-500 shadow-inner">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <div class="space-y-2">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">@label('success.order_confirmed', 'Order Confirmed!')</h1>
                <p class="text-slate-500 text-sm max-w-md mx-auto mb-4">
                    @label('success.thank_you_message', 'Thank you for your purchase. Your payment was processed successfully. A confirmation email has been dispatched.')
                </p>
            </div>

            <!-- Order Status Display -->
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-center">
                <span class="text-xs font-bold text-indigo-400 block uppercase tracking-wider mb-1">@label('success.order_status', 'Order Status')</span>
                <p class="text-sm font-bold text-indigo-800">
                    {{ $order->statusList ? $order->statusList->customerdisplay : siteLabel('success.payment_received', 'Payment Received - Order Being Processed.') }}
                </p>
            </div>

            <div class="border-t border-b border-slate-100 py-6 grid grid-cols-2 gap-4 text-sm text-left">
                <div>
                    <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">@label('success.order_number', 'Order #')</span>
                    <span class="font-extrabold text-slate-800">{{ $order->order_invoice_no }}</span>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">@label('success.order_date', 'Order Date')</span>
                    <span class="font-bold text-slate-700">{{ $order->order_date->format('F d, Y h:i A') }}</span>
                </div>
                <div class="pt-2">
                    <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">@label('success.customer_name', 'Customer Name')</span>
                    <span class="font-bold text-slate-700">{{ $order->user ? $order->user->name : siteLabel('success.guest_user', 'Guest User') }}</span>
                </div>
                <div class="pt-2">
                    <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">@label('success.email_address', 'Email Address')</span>
                    <span class="font-semibold text-slate-600">{{ $order->user ? $order->user->email : '-' }}</span>
                </div>
            </div>

            <!-- Ordered Items Details -->
            <div class="text-left space-y-4 pt-2">
                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">@label('success.items_ordered', 'Items Ordered')</h3>
                <div class="space-y-3 bg-slate-50 border border-slate-100 rounded-2xl p-6">
                    @foreach($order->details as $item)
                        <div class="flex items-center justify-between text-sm gap-4">
                            <div class="flex-1">
                                <span class="font-bold text-slate-800">{{ $item->item_name }}</span>
                                <span class="text-xs text-slate-500 block mt-0.5">@label('success.quantity', 'Quantity:') {{ number_format($item->item_qty, 0) }}</span>
                                @if($item->download_item)
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        <span class="inline-block bg-teal-50 text-teal-700 text-[9px] px-1.5 py-0.5 rounded font-bold border border-teal-150">@label('success.ready_for_download', 'Ready for download')</span>
                                        <a href="{{ route('products.download', [$item->id, $order->order_external_id]) }}" class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded transition duration-150">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            @label('success.download_file', 'Download File')
                                        </a>
                                    </div>
                                @elseif($item->item_shippable)
                                    <span class="inline-block bg-indigo-50 text-indigo-700 text-[9px] px-1.5 py-0.5 rounded font-bold border border-indigo-150 mt-1">@label('success.will_be_shipped', 'Will be shipped')</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-slate-900 block">{{ $currencySymbol }}{{ number_format($item->final_price * $item->item_qty, 2) }}</span>
                                @if($item->discount_price > 0)
                                    <span class="line-through text-slate-400 text-[10px] block">{{ $currencySymbol }}{{ number_format(($item->final_price + $item->discount_price) * $item->item_qty, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Financial Summary -->
                    <div class="border-t border-slate-200/60 mt-4 pt-4 space-y-2">
                        @if(\App\Models\CmsSetting::isEnabled('checkout_show_subtotal', true))
                            <div class="flex justify-between text-xs text-slate-500">
                                <span>@label('success.subtotal', 'Subtotal')</span>
                                <span class="font-semibold text-slate-800">{{ $currencySymbol }}{{ number_format($order->order_subtotal, 2) }}</span>
                            </div>
                        @endif
                        @if($order->order_discounts > 0)
                            <div class="flex justify-between text-xs text-emerald-600 font-semibold">
                                <span>@label('success.promotional_discount', 'Promotional Discount')</span>
                                <span>-{{ $currencySymbol }}{{ number_format($order->order_discounts, 2) }}</span>
                            </div>
                        @endif
                        @if(\App\Models\CmsSetting::isEnabled('checkout_show_tax', true))
                            @if($vatInclusive && !$crossBorder)
                                @if($vatEmbed > 0)
                                    <div class="flex justify-between text-xs text-slate-400">
                                        <span class="italic">@label('review.includes_tax', 'Includes') {{ $taxLabel }} {{ $currencySymbol }}{{ number_format($vatEmbed, 2) }}</span>
                                        <span></span>
                                    </div>
                                @endif
                            @else
                                <div class="flex justify-between text-xs text-slate-500">
                                    <span>{{ $taxLabel }}</span>
                                    <span class="font-semibold text-slate-800">{{ $currencySymbol }}{{ number_format($order->order_taxes, 2) }}</span>
                                </div>
                            @endif
                        @endif
                        @if(\App\Models\CmsSetting::isEnabled('checkout_show_shipping', true))
                            <div class="flex justify-between text-xs text-slate-500">
                                <span>@label('cart.shipping', 'Shipping') ({{ $order->order_shipping_method_name ?? siteLabel('success.flat_rate', 'Flat Rate') }})</span>
                                <span class="font-semibold text-slate-800">{{ $currencySymbol }}{{ number_format($order->order_shipping, 2) }}</span>
                            </div>
                        @endif
                        @if($order->order_handling > 0)
                            <div class="flex justify-between text-xs text-slate-500">
                                <span>@label('success.handling', 'Handling Surcharge')</span>
                                <span class="font-semibold text-slate-800">{{ $currencySymbol }}{{ number_format($order->order_handling, 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t border-slate-200/60 pt-3 flex justify-between text-base font-extrabold text-slate-900">
                            <span>@label('success.total_charged', 'Total Charged')</span>
                            <span>{{ $currencySymbol }}{{ number_format($order->order_total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping information card if required -->
            @if($order->order_shipping_method == 1 && $order->user)
                <div class="text-left space-y-3 pt-2">
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">@label('success.shipping_address', 'Shipping Address')</h3>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 text-sm text-slate-700 space-y-1">
                        <p class="font-bold text-slate-900">{{ $order->user->name }}</p>
                        @if($order->user->company)
                            <p class="text-slate-500">{{ $order->user->company }}</p>
                        @endif
                        <p>{{ $order->user->shipping_address1 }}</p>
                        @if($order->user->shipping_address2)
                            <p>{{ $order->user->shipping_address2 }}</p>
                        @endif
                        <p>{{ $order->user->shipping_city }}@if($order->user->shipping_state), {{ $order->user->shipping_state }}@endif {{ $order->user->shopping_postalcode }}</p>
                        <p class="font-semibold">{{ $order->user->shipping_country }}</p>
                    </div>
                </div>
            @endif

            @if(!empty($order->order_comments))
                <div class="text-left space-y-3 pt-2">
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">@label('success.order_comments', 'Order Comments')</h3>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 text-sm text-slate-700 whitespace-pre-wrap">
                        {{ $order->order_comments }}
                    </div>
                </div>
            @endif


        </div>
    </div>
</div>
