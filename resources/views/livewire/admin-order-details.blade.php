<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between gap-3 mb-8 flex-wrap">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate
                   class="p-2.5 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">Order Details: {{ $order->order_invoice_no }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Manage customer transaction history, shipping statuses, and partial refunding parameters</p>
                </div>
            </div>

            <!-- Delete Order Button -->
            @if(!$showDeleteConfirm)
                <button wire:click="confirmDelete" class="flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 text-red-700 text-xs font-bold rounded-xl hover:bg-red-100 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete Order
                </button>
            @else
                <div class="flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-2xl">
                    <span class="text-xs font-bold text-red-700">⚠ Delete this order and restore inventory?</span>
                    <button wire:click="deleteOrder" wire:loading.attr="disabled" wire:target="deleteOrder" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-xl hover:bg-red-500 transition-all">
                        Yes, Delete
                    </button>
                    <button wire:click="cancelDelete" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-50 transition-all">
                        Cancel
                    </button>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 space-y-2">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-1">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-3">Shop Administration</h2>
                    
                    <a href="{{ route('admin.ecommerce.pending-orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pending Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Products
                    </a>

                    <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 text-indigo-600 transition duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.inventory') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Inventory
                    </a>
                </div>
            </div>

            <!-- Main Order Panels -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Status Notifications -->
                <x-toast-alert />

                <!-- Overview Card -->
                <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-4">
                        <div>
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Order Date</span>
                            <span class="font-bold text-slate-700 text-sm">{{ $order->order_date->format('F d, Y h:i A') }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Status</span>
                            @if($order->order_status == 1)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 rounded-full text-xs font-bold border border-red-150">
                                    {{ $order->statusList ? $order->statusList->AdminDisplay : 'Open (PENDING)' }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 text-slate-700 rounded-full text-xs font-bold border border-slate-200">
                                    {{ $order->statusList ? $order->statusList->AdminDisplay : 'Pending (' . $order->order_status . ')' }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Order #</span>
                            <span class="font-bold text-slate-800 text-sm">{{ $order->order_invoice_no }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-2">
                        <!-- Customer details -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Customer Details</h4>
                            <div class="space-y-1.5 text-sm">
                                <div><span class="text-slate-500">Name:</span> <span class="font-bold text-slate-800">{{ $order->user ? $order->user->name : 'Guest User' }}</span></div>
                                <div><span class="text-slate-500">Email:</span> <span class="font-semibold text-slate-800">{{ $order->user ? $order->user->email : '-' }}</span></div>
                            </div>
                        </div>

                        <!-- Shipping details if shippable -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Shipping Details</h4>
                            <div class="space-y-1.5 text-sm">
                                @if($order->order_shipping_method == 1 && $order->user)
                                    <div><span class="text-slate-500">Address:</span> <span class="font-semibold text-slate-800">{{ $order->user->shipping_address1 }} {{ $order->user->shipping_address2 }}</span></div>
                                    <div><span class="text-slate-500">City/ZIP:</span> <span class="font-semibold text-slate-800">{{ $order->user->shipping_city }}@if($order->user->shipping_state), {{ $order->user->shipping_state }}@endif {{ $order->user->shopping_postalcode }}</span></div>
                                    <div><span class="text-slate-500">Country:</span> <span class="font-semibold text-slate-800">{{ $order->user->shipping_country }} ({{ $order->user->shipping_countrycode }})</span></div>
                                @else
                                    <span class="text-xs text-slate-400 italic">No physical shipping required for this order (Digital Download).</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Comments Card -->
                <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Order Comments
                    </h3>
                    @if(!empty($order->order_comments))
                        <div class="text-sm text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-200 whitespace-pre-wrap">
                            {{ $order->order_comments }}
                        </div>
                    @else
                        <p class="text-sm text-slate-400 italic font-medium">no customer comments for this order</p>
                    @endif
                </div>

                <!-- Purchased Items Card -->
                <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">Items Purchased</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-500">
                            <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3">Item Name</th>
                                    <th class="px-4 py-3 text-center">Quantity</th>
                                    <th class="px-4 py-3">Delivery Option</th>
                                    <th class="px-4 py-3 text-right">Price</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($order->details as $detail)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3.5 font-bold text-slate-800">
                                            @if($detail->variant && $detail->variant->product)
                                                <a href="{{ route('admin.ecommerce.product-edit', $detail->variant->product->id) }}" class="text-indigo-600 hover:underline">
                                                    {{ $detail->item_name }}
                                                </a>
                                            @else
                                                {{ $detail->item_name }}
                                            @endif

                                            @php
                                                $customs = json_decode($detail->options_list, true) ?: [];
                                                $customizations = isset($customs['customizations']) ? $customs['customizations'] : (is_array($customs) ? $customs : []);
                                            @endphp
                                            @if(!empty($customizations))
                                                <div class="mt-1.5 space-y-0.5 text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-xl border border-slate-100 max-w-md">
                                                    @foreach($customizations as $cust)
                                                        @if(is_array($cust) && isset($cust['label']))
                                                            <div>
                                                                <span class="font-bold text-slate-600">{{ $cust['label'] }}:</span>
                                                                <span>{{ $cust['value'] }}</span>
                                                                @if(isset($cust['price_modifier']) && $cust['price_modifier'] > 0)
                                                                    <span class="text-indigo-600 font-bold ml-0.5">(+${{ number_format($cust['price_modifier'], 2) }})</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-bold text-slate-700">{{ number_format($detail->item_qty, 0) }}</td>
                                        <td class="px-4 py-3.5">
                                            <div class="space-y-1">
                                                @if($detail->download_item)
                                                    <div><span class="inline-block bg-teal-50 text-teal-700 text-[10px] px-2 py-0.5 rounded font-bold border border-teal-150">Instant Download</span></div>
                                                @endif
                                                @if($detail->active_subscription)
                                                    <div>
                                                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[10px] px-2 py-0.5 rounded font-bold border border-emerald-200">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active Subscription
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <button type="button"
                                                                onclick="confirm('Are you sure you want to cancel this recurring subscription agreement with the payment processor?') || event.stopImmediatePropagation()"
                                                                wire:click="cancelSubscription({{ $detail->id }})"
                                                                wire:loading.attr="disabled"
                                                                class="inline-flex items-center gap-1 px-2 py-0.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded text-[10px] font-bold transition shadow-xs cursor-pointer">
                                                            <svg class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            Cancel Sub
                                                        </button>
                                                    </div>
                                                @elseif($detail->subscription && !$detail->active_subscription)
                                                    <div>
                                                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 text-[10px] px-2 py-0.5 rounded font-bold border border-slate-200">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Cancelled Sub
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5 text-right">
                                            <span class="font-semibold text-slate-700 block">${{ number_format($detail->final_price, 2) }}</span>
                                            @if($detail->discount_price > 0)
                                                <span class="line-through text-slate-400 text-[10px] block">${{ number_format($detail->final_price + $detail->discount_price, 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-right">
                                            <span class="font-bold text-slate-900 block">${{ number_format($detail->final_price * $detail->item_qty, 2) }}</span>
                                            @if($detail->discount_price > 0)
                                                <span class="line-through text-slate-400 text-[10px] block">${{ number_format(($detail->final_price + $detail->discount_price) * $detail->item_qty, 2) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Financial Statement summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <!-- Accounting Calculations -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-4">
                        <h3 class="text-sm font-extrabold text-slate-400 uppercase tracking-wider pb-2 border-b border-slate-100">Order Totals</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-slate-600">
                                <span>Subtotal</span>
                                <span class="font-bold text-slate-800">${{ number_format($order->order_subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>Tax</span>
                                <span class="font-bold text-slate-800">${{ number_format($order->order_taxes, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>Shipping Fee</span>
                                <span class="font-bold text-slate-800">${{ number_format($order->order_shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-800 pt-3 border-t border-slate-100 text-base font-extrabold">
                                <span>Total Paid</span>
                                <span>${{ number_format($order->order_total, 2) }}</span>
                            </div>

                            @php
                                $totalRefunded = $order->refunds->sum('amount');
                                $remainingRefundable = max(0.00, $order->order_total - $totalRefunded);
                            @endphp

                            <div class="flex justify-between text-red-600 pt-3 border-t border-slate-100 text-sm font-bold">
                                <span>Total Refunded</span>
                                <span>-${{ number_format($totalRefunded, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-emerald-600 pt-1 text-sm font-bold">
                                <span>Remaining Refundable</span>
                                <span>${{ number_format($remainingRefundable, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Form Card -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-sm font-extrabold text-slate-400 uppercase tracking-wider pb-2 border-b border-slate-100">Order Actions</h3>

                        <!-- Update Status Dropdown with Confirm Button -->
                        <div class="space-y-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                            <h4 class="text-xs font-bold text-slate-700">Update Order Status</h4>
                            <select wire:change="setPendingStatus($event.target.value)"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-medium text-xs">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->orderstatuscode }}" @if($order->order_status == $status->orderstatuscode) selected @endif>
                                        {{ $status->orderstatus }}
                                    </option>
                                @endforeach
                            </select>

                            @if($showStatusConfirm)
                                <div class="flex items-center gap-2 mt-2">
                                    <button wire:click="applyStatusChange"
                                            wire:loading.attr="disabled"
                                            wire:target="applyStatusChange"
                                            class="flex-1 px-3 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-500 transition-all flex items-center justify-center gap-1.5">
                                        <svg wire:loading wire:target="applyStatusChange" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>✓ Apply Status</span>
                                    </button>
                                    <button wire:click="cancelStatusChange"
                                            class="px-3 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-all">
                                        Cancel
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Send Duplicate Order Confirmation Email -->
                        <div class="space-y-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                            <h4 class="text-xs font-bold text-slate-700">Resend Confirmation Email</h4>
                            <p class="text-xs text-slate-500">Sends a duplicate order confirmation email to the customer (does not change order status).</p>
                            
                            @if(!$showEmailConfirm)
                                <button wire:click="triggerEmailConfirm"
                                        class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-md hover:opacity-90 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Send Email
                                </button>
                            @else
                                <div class="flex items-center gap-2 mt-2">
                                    <button wire:click="sendDuplicateOrderConfirmation"
                                            wire:loading.attr="disabled"
                                            wire:target="sendDuplicateOrderConfirmation"
                                            class="flex-1 px-3 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-500 transition-all flex items-center justify-center gap-1.5">
                                        <svg wire:loading wire:target="sendDuplicateOrderConfirmation" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Confirm Send</span>
                                    </button>
                                    <button wire:click="cancelEmailSend"
                                            class="px-3 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-all">
                                        Cancel
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Mark Shipped Action -->
                        @if($order->order_status == 1 && $order->order_shipping_method == 1)
                            <div class="space-y-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                                <h4 class="text-xs font-bold text-slate-700">Fulfillment</h4>
                                <p class="text-xs text-slate-500">This order requires physical shipping. Enter the ship date and optional tracking number, then confirm.</p>

                                @if(!$showShipForm)
                                    <button wire:click="toggleShipForm" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-md hover:opacity-90 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1.293 9.293A1 1 0 007.28 18h9.44a1 1 0 00.987-.836L19 8M10 12h4"/></svg>
                                        Mark Shipped
                                    </button>
                                @else
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Ship Date <span class="text-red-500">*</span></label>
                                            <input type="date" wire:model="shipDate"
                                                   class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-medium text-xs">
                                            @error('shipDate') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Tracking Number <span class="text-slate-400 font-normal">(optional — auto-generated if blank)</span></label>
                                            <input type="text" wire:model="trackingNumber" placeholder="e.g. 1Z999AA10123456784"
                                                   class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-mono text-xs">
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button wire:click="markShipped"
                                                    wire:loading.attr="disabled"
                                                    wire:target="markShipped"
                                                    class="flex-1 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md hover:opacity-90 flex items-center justify-center gap-1.5">
                                                <svg wire:loading wire:target="markShipped" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span>✓ Confirm Shipment</span>
                                            </button>
                                            <button wire:click="toggleShipForm"
                                                    class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-all">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @elseif($order->order_status == 2)
                            <div class="p-4 bg-emerald-50 border border-emerald-100 text-xs font-semibold text-emerald-800 rounded-2xl">
                                ✓ Shipped on {{ $order->order_shipping_date?->format('Y-m-d') }}<br>
                                Tracking: <code class="font-bold text-indigo-600">{{ $order->order_shipping_tracking }}</code>
                            </div>
                        @endif

                        <!-- Send Download Reminder Action -->
                        @if($order->details->where('download_item', true)->isNotEmpty())
                            <div class="space-y-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                                <h4 class="text-xs font-bold text-slate-700">Digital Downloads</h4>
                                <p class="text-xs text-slate-500">Send an email reminder with customer download links and order totals summary.</p>
                                
                                @if(!$showDownloadConfirm)
                                    <button type="button" wire:click="triggerDownloadReminderConfirm" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-md hover:opacity-90 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Send Download Reminder</span>
                                    </button>
                                @else
                                    <div class="flex items-center gap-2 mt-2">
                                        <button type="button" 
                                                wire:click="sendDownloadReminder" 
                                                wire:loading.attr="disabled" 
                                                wire:target="sendDownloadReminder" 
                                                class="flex-1 px-3 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-500 transition-all flex items-center justify-center gap-1.5">
                                            <svg wire:loading wire:target="sendDownloadReminder" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Confirm Send</span>
                                        </button>
                                        <button type="button" 
                                                wire:click="cancelDownloadReminderSend" 
                                                class="px-3 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-all">
                                            Cancel
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Refund History Log Card -->
                @if($order->refunds->isNotEmpty())
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-4">
                        <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">Refunding History Logs</h3>
                        <div class="space-y-4">
                            @foreach($order->refunds as $refund)
                                <div class="p-4 bg-red-50/50 border border-red-100 rounded-2xl flex items-center justify-between text-sm flex-wrap gap-4">
                                    <div>
                                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Refunded On</span>
                                        <span class="font-semibold text-slate-700">{{ $refund->refund_date->format('Y-m-d h:i A') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Auth Code</span>
                                        <span class="font-bold text-indigo-700 font-mono">{{ $refund->authorization_code }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Amount Refunded</span>
                                        <span class="font-extrabold text-red-600">${{ number_format($refund->amount, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ── Payment History Card (with CRUD) ──────────────────────────────── --}}
                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-8 shadow-sm space-y-4 mt-8">
                    <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Payment History
                        </h3>
                        <button
                            wire:click="openAddPayment"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Add Payment
                        </button>
                    </div>

                    @if($order->payments->isNotEmpty())
                        <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-700">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Date</th>
                                        <th class="px-4 py-3 text-left">Method</th>
                                        <th class="px-4 py-3 text-left">Auth / Ref Code</th>
                                        <th class="px-4 py-3 text-left">Status</th>
                                        <th class="px-4 py-3 text-right">Amount</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach($order->payments as $payment)
                                        @php
                                            $refundedAmt = (float) $payment->refunded_amount;
                                            $remRefundable = (float) $payment->remaining_refundable;
                                        @endphp
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-900/30 transition">
                                            <td class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                                {{ $payment->payment_date ? $payment->payment_date->format('M j, Y g:i A') : '—' }}
                                            </td>
                                            <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">
                                                {{ $payment->payment_method ?: '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-mono text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $payment->authorization_code ?: '—' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($payment->payment_status == 2 || ($refundedAmt > 0 && $remRefundable <= 0.005))
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                                                        Refunded
                                                    </span>
                                                @elseif($payment->payment_status == 3 || $refundedAmt > 0)
                                                    <div class="flex flex-col">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 w-fit">
                                                            Partially Refunded
                                                        </span>
                                                        <span class="text-[10px] font-semibold text-rose-500 mt-0.5">-${{ number_format($refundedAmt, 2) }} refunded</span>
                                                    </div>
                                                @elseif($payment->payment_status == 1)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Paid</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right font-extrabold text-slate-900 dark:text-white whitespace-nowrap">
                                                ${{ number_format($payment->payment_amount, 2) }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-center gap-2">
                                                    @if($remRefundable > 0)
                                                        <button
                                                            type="button"
                                                            wire:click="openRefundModal({{ $payment->id }})"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-red-700 bg-red-50 hover:bg-red-100 active:bg-red-200 dark:bg-red-950/60 dark:text-red-300 dark:hover:bg-red-900/60 border border-red-200 dark:border-red-800 rounded-xl transition shadow-xs hover:shadow-sm"
                                                            title="Refund this payment"
                                                        >
                                                            <svg class="w-3.5 h-3.5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-4-4m4 4l4-4M3 10l4-4m-4 4l4 4"/></svg>
                                                            <span>Refund</span>
                                                        </button>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold text-slate-400 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl cursor-default" title="Payment fully refunded">
                                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            <span>Refunded</span>
                                                        </span>
                                                    @endif
                                                    <button
                                                        wire:click="openEditPayment({{ $payment->id }})"
                                                        class="p-1.5 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/40"
                                                        title="Edit payment"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </button>
                                                    <button
                                                        x-on:click="if(confirm('Delete this payment of ${{ number_format($payment->payment_amount, 2) }}?')) $wire.deletePayment({{ $payment->id }})"
                                                        class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40"
                                                        title="Delete payment"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Balance Summary --}}
                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700 space-y-1.5">
                            <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                                <span class="font-medium">Order Total</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300">${{ number_format($order->order_total, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                                <span class="font-medium">Total Paid</span>
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">${{ number_format($order->payments->sum('payment_amount'), 2) }}</span>
                            </div>
                            @php $totalRefunded = (float) $order->refunds->sum('amount'); @endphp
                            @if($totalRefunded > 0)
                                <div class="flex items-center justify-between text-sm font-semibold text-rose-600 dark:text-rose-400">
                                    <span>Total Refunded</span>
                                    <span>-${{ number_format($totalRefunded, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between text-sm font-bold border-t border-slate-200 dark:border-slate-700 pt-2 mt-2">
                                <span class="text-slate-700 dark:text-slate-300">Balance Due</span>
                                @php $balanceDue = max(0, (float)$order->order_total - (float)$order->payments->sum('payment_amount') + $totalRefunded); @endphp
                                <span class="{{ $balanceDue > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }} text-base">
                                    ${{ number_format($balanceDue, 2) }}
                                    @if($balanceDue <= 0)
                                        <span class="ml-1 text-xs font-bold px-1.5 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-full">Paid in Full</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <svg class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <p class="text-sm text-slate-400 dark:text-slate-500 font-medium">No payments recorded for this order.</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Balance Due: <span class="font-bold text-rose-500">${{ number_format($order->order_total, 2) }}</span></p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- ── Add / Edit Payment Modal ─────────────────────────────────────── --}}
    <div
        x-show="$wire.showPaymentModal"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-cloak
    >
        <div
            x-show="$wire.showPaymentModal"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg border border-slate-200 dark:border-slate-700"
            @click.away="$wire.set('showPaymentModal', false)"
        >
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900 dark:text-white">
                    {{ $editingPaymentId ? 'Edit Payment' : 'Add Payment' }}
                </h2>
                <button
                    wire:click="$set('showPaymentModal', false)"
                    class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                {{-- Row 1: Date + Amount --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">Payment Date <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="pmtDate"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                        @error('pmtDate') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">Amount <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">$</span>
                            <input type="number" wire:model="pmtAmount" step="0.01" min="0.01" placeholder="0.00"
                                class="w-full pl-7 pr-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                        </div>
                        @error('pmtAmount') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Row 2: Method + Status --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">Method <span class="text-rose-500">*</span></label>
                        <select wire:model="pmtMethod"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                            <option value="Manual">Manual</option>
                            <option value="Cash">Cash</option>
                            <option value="Check">Check</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('pmtMethod') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">Status</label>
                        <select wire:model="pmtStatus"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                            <option value="1">Paid</option>
                            <option value="0">Pending</option>
                        </select>
                    </div>
                </div>

                {{-- Auth Code --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">Auth / Reference Code <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="text" wire:model="pmtAuthCode" placeholder="e.g. ch_3xyz, TXN123, CHK-0042..."
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition font-mono" />
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">Notes / Response <span class="text-slate-400 font-normal">(optional)</span></label>
                    <textarea wire:model="pmtNotes" rows="2" placeholder="Additional notes, gateway response message..."
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition resize-none"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end gap-3">
                <button
                    wire:click="$set('showPaymentModal', false)"
                    class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition"
                >
                    Cancel
                </button>
                <button
                    wire:click="savePayment"
                    wire:loading.attr="disabled"
                    wire:target="savePayment"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-bold rounded-xl transition flex items-center gap-2"
                >
                    <span wire:loading.remove wire:target="savePayment">{{ $editingPaymentId ? 'Update Payment' : 'Add Payment' }}</span>
                    <span wire:loading wire:target="savePayment">Saving...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Process Payment Refund Modal ─────────────────────────────────────── --}}
    <div
        x-show="$wire.showRefundModal"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-cloak
    >
        <div
            x-show="$wire.showRefundModal"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg border border-slate-200 dark:border-slate-700 overflow-hidden"
            @click.away="$wire.closeRefundModal()"
        >
            @php
                $selectedPayment = $refundingPaymentId ? $order->payments->firstWhere('id', $refundingPaymentId) : null;
            @endphp

            <div class="px-6 py-5 bg-gradient-to-r from-amber-500/10 via-rose-500/5 to-transparent border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
               <div class="flex items-center gap-3">
                   <div class="w-9 h-9 rounded-2xl bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-4-4m4 4l4-4M3 10l4-4m-4 4l4 4"/></svg>
                   </div>
                   <div>
                       <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                           Refund Payment
                       </h2>
                       <p class="text-xs text-slate-500 dark:text-slate-400">
                           Payment #{{ $refundingPaymentId }} &bull; {{ $selectedPayment?->payment_method ?? 'Payment' }}
                       </p>
                   </div>
               </div>
               <button
                   wire:click="closeRefundModal"
                   class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition"
               >
                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
               </button>
            </div>

            @if($selectedPayment)
               <div class="p-6 space-y-5">
                   {{-- Payment Summary Box --}}
                   <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/60 rounded-2xl p-4 space-y-2 text-xs">
                       <div class="flex items-center justify-between">
                           <span class="text-slate-500 dark:text-slate-400 font-medium">Original Payment</span>
                           <span class="font-bold text-slate-800 dark:text-slate-200">${{ number_format($selectedPayment->payment_amount, 2) }}</span>
                       </div>
                       @if($selectedPayment->refunded_amount > 0)
                           <div class="flex items-center justify-between text-rose-500 font-semibold">
                               <span>Already Refunded</span>
                               <span>-${{ number_format($selectedPayment->refunded_amount, 2) }}</span>
                           </div>
                       @endif
                       <div class="flex items-center justify-between pt-1 border-t border-slate-200 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 font-bold">
                           <span>Max Remaining Refundable</span>
                           <span class="text-sm">${{ number_format($selectedPayment->remaining_refundable, 2) }}</span>
                       </div>
                       @if($selectedPayment->authorization_code)
                           <div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-200 dark:border-slate-700">
                               <span class="text-slate-400">Gateway Auth/Ref Code:</span>
                               <span class="font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $selectedPayment->authorization_code }}</span>
                           </div>
                       @endif
                   </div>

                   {{-- Refund Amount Field --}}
                   <div>
                       <div class="flex items-center justify-between mb-1.5">
                           <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                               Refund Amount ($) <span class="text-rose-500">*</span>
                           </label>
                           <button
                               type="button"
                               wire:click="$set('refundPaymentAmount', '{{ number_format($selectedPayment->remaining_refundable, 2, '.', '') }}')"
                               class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline"
                           >
                               Set Full Amount (${{ number_format($selectedPayment->remaining_refundable, 2) }})
                           </button>
                       </div>
                       <div class="relative">
                           <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">$</span>
                           <input
                               type="number"
                               wire:model.blur="refundPaymentAmount"
                               step="0.01"
                               min="0.01"
                               max="{{ $selectedPayment->remaining_refundable }}"
                               class="w-full pl-7 pr-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-extrabold focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                           />
                       </div>
                       @error('refundPaymentAmount') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                       <p class="text-[11px] text-slate-400 mt-1">
                           Defaulted to full remaining balance (${{ number_format($selectedPayment->remaining_refundable, 2) }}). You can edit this for partial refunds.
                       </p>
                   </div>

                   {{-- Refund Reason --}}
                   <div>
                       <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                           Reason / Note <span class="text-slate-400 font-normal">(optional)</span>
                       </label>
                       <textarea
                           wire:model.blur="refundReason"
                           rows="2"
                           placeholder="e.g. Customer requested refund, returned items, billing dispute..."
                           class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition resize-none"
                       ></textarea>
                   </div>

                   {{-- Gateway API Toggle --}}
                   <div class="p-3 bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40 rounded-xl">
                       <label class="flex items-start gap-2.5 cursor-pointer">
                           <input
                               type="checkbox"
                               wire:model="refundPostToGateway"
                               class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                           />
                           <div>
                               <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                                   Post refund via Payment Processor API
                               </span>
                               <span class="text-[11px] text-slate-500 dark:text-slate-400 block leading-tight">
                                   Automatically submits the refund request to {{ $selectedPayment->payment_method ?: 'the payment gateway' }} API. Uncheck only if you want an offline ledger entry or already refunded directly.
                               </span>
                           </div>
                       </label>
                   </div>
               </div>

               <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end gap-3 bg-slate-50/50 dark:bg-slate-900/30">
                   <button
                       wire:click="closeRefundModal"
                       class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition"
                   >
                       Cancel
                   </button>
                   <button
                       wire:click="processPaymentRefund"
                       wire:loading.attr="disabled"
                       wire:target="processPaymentRefund"
                       onclick="return confirm('Are you sure you want to process this refund?')"
                       class="px-5 py-2 bg-red-600 hover:bg-red-700 disabled:opacity-60 text-white text-xs font-bold rounded-xl transition flex items-center gap-2 shadow-md shadow-red-600/20"
                   >
                       <svg wire:loading wire:target="processPaymentRefund" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                           <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                           <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                       </svg>
                       <span wire:loading.remove wire:target="processPaymentRefund">Confirm &amp; Process Refund</span>
                       <span wire:loading wire:target="processPaymentRefund">Processing Refund...</span>
                   </button>
               </div>
            @endif
        </div>
    </div>
</div>
