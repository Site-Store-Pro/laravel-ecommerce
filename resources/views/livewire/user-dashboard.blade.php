@section('title', siteLabel('account.my_account', 'My Account'))

<div>
    {{-- ─── Page Header ─────────────────────────────────────────────────────── --}}
    <div class="bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            {{-- Title --}}
            <h1 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                @label('account.my_account', 'My Account')
            </h1>

            {{-- Profile / Logout widget --}}
            <div class="flex items-center gap-3" x-data="{ profileOpen: false }" @click.away="profileOpen = false">



                {{-- Dropdown trigger --}}
                <div class="relative">
                    <button @click="profileOpen = !profileOpen"
                            id="dashboard-profile-btn"
                            class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-indigo-300 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
                            :aria-expanded="profileOpen">
                        {{-- Avatar initials --}}
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-white text-xs font-bold shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="hidden sm:block max-w-[140px] truncate">{{ auth()->user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="{ 'rotate-180': profileOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown panel --}}
                    <div x-show="profileOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl shadow-slate-200/60 z-50 overflow-hidden"
                         style="display: none;">

                        {{-- User info header --}}
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/70">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">@label('account.signed_in_as', 'Signed in as')</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="py-1.5">
                            {{-- Edit Profile --}}
                            <a href="{{ route('profile') }}" wire:navigate
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                @label('account.edit_profile', 'Edit Profile')
                            </a>

                            {{-- Divider --}}
                            <div class="my-1.5 border-t border-slate-100"></div>

                            {{-- Sign out --}}
                            <button wire:click="logout"
                                    class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors font-semibold">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                @label('account.sign_out', 'Sign Out')
                            </button>
                        </div>
                    </div>
                </div>

                {{-- New Ticket quick-action (only visible when tickets tab is enabled) --}}
                @if($ticketsTabEnabled)
                <a href="{{ route('tickets.create') }}" wire:navigate
                   class="hidden sm:inline-flex items-center gap-1.5 justify-center rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-100 hover:from-indigo-600 hover:to-violet-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    @label('account.new_ticket', 'New Ticket')
                </a>
                @endif
            </div>
        </div>

        {{-- ─── Tab Navigation ───────────────────────────────────────────────── --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="-mb-px flex space-x-1" aria-label="Account tabs">
                {{-- Orders --}}
                <button wire:click="$set('tab', 'orders')" id="tab-orders"
                        class="shrink-0 border-b-2 py-3.5 px-4 text-sm font-semibold transition-all focus:outline-none rounded-t-lg
                        {{ $tab === 'orders' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">
                    @label('account.orders_tab', 'Orders')
                </button>
                {{-- Downloads --}}
                @if($downloadsTabEnabled)
                <button wire:click="$set('tab', 'downloads')" id="tab-downloads"
                        class="shrink-0 border-b-2 py-3.5 px-4 text-sm font-semibold transition-all focus:outline-none rounded-t-lg
                        {{ $tab === 'downloads' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">
                    @label('account.downloads_tab', 'Downloads')
                </button>
                @endif
                {{-- Tickets --}}
                @if($ticketsTabEnabled)
                <button wire:click="$set('tab', 'tickets')" id="tab-tickets"
                        class="shrink-0 border-b-2 py-3.5 px-4 text-sm font-semibold transition-all focus:outline-none rounded-t-lg
                        {{ $tab === 'tickets' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">
                    @label('account.support_tab', 'Support Tickets')
                </button>
                @endif
            </nav>
        </div>
    </div>

    {{-- ─── Content ──────────────────────────────────────────────────────────── --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('status'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200/80 p-4 text-sm text-emerald-800 flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- ═══ ORDERS TAB ═══════════════════════════════════════════════════ --}}
            @if($tab === 'orders')
                @if($selectedOrder)
                    {{-- Detailed Order View --}}
                    <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h3 class="font-bold text-lg text-slate-800">@label('account.order_detail', 'Order Detail')</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Invoice #{{ $selectedOrder->order_invoice_no }} · Placed on {{ $selectedOrder->order_date ? $selectedOrder->order_date->format('M d, Y') : $selectedOrder->created_at->format('M d, Y') }}</p>
                            </div>
                            <button wire:click="closeOrderDetails" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all">
                                @label('account.back_to_list', 'Back to List')
                            </button>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Order Items Table --}}
                            <div class="overflow-x-auto border border-slate-150 rounded-xl">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50 dark:bg-slate-700/60">
                                        <tr>
                                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">@label('account.col_product', 'Product')</th>
                                            <th class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">@label('account.col_qty', 'Qty')</th>
                                            <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">@label('account.col_price', 'Price')</th>
                                            <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">@label('account.col_total', 'Total')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach($selectedOrder->details as $detail)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-slate-800 text-sm">{{ $detail->item_name }}</span>
                                                        @if($detail->download_item)
                                                            <span class="inline-flex items-center w-max bg-indigo-50 text-indigo-700 text-[10px] px-1.5 py-0.5 rounded font-bold mt-1">@label('account.digital_delivery', 'Digital Delivery')</span>
                                                        @endif
                                                        @if($detail->active_subscription)
                                                            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[10px] px-2 py-0.5 rounded-full font-bold border border-emerald-200">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                                    @label('account.subscription_active', 'Active Subscription')
                                                                </span>
                                                                <button type="button"
                                                                    onclick="confirm('{{ siteLabel('account.cancel_confirm', 'Are you sure you want to cancel this recurring subscription?') }}') || event.stopImmediatePropagation()"
                                                                    wire:click="cancelSubscription({{ $detail->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-bold transition duration-150 shadow-xs cursor-pointer">
                                                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                    @label('account.cancel_subscription', 'Cancel Subscription')
                                                                </button>
                                                            </div>
                                                        @elseif($detail->subscription && !$detail->active_subscription)
                                                            <div class="mt-1.5">
                                                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 text-[10px] px-2 py-0.5 rounded-full font-bold border border-slate-200">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                                    @label('account.subscription_cancelled', 'Cancelled Subscription')
                                                                </span>
                                                            </div>
                                                        @endif
                                                        @php
                                                            $customs = json_decode($detail->options_list, true) ?: [];
                                                            $customizations = isset($customs['customizations']) ? $customs['customizations'] : (is_array($customs) ? $customs : []);
                                                        @endphp
                                                        @if(!empty($customizations))
                                                            <div class="mt-1.5 space-y-0.5 text-[11px] text-slate-500 bg-slate-50 p-2 rounded-xl border border-slate-100 max-w-md">
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
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center text-sm font-semibold text-slate-600">{{ number_format($detail->item_qty, 0) }}</td>
                                                <td class="px-6 py-4 text-right text-sm">
                                                    <span class="font-semibold text-slate-700 block">${{ number_format($detail->final_price, 2) }}</span>
                                                    @if($detail->discount_price > 0)
                                                        <span class="line-through text-slate-400 text-[10px] block">${{ number_format($detail->final_price + $detail->discount_price, 2) }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right text-sm">
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

                            {{-- Financial Summary --}}
                            <div class="flex justify-end">
                                <div class="w-80 space-y-3 bg-slate-50/50 dark:bg-slate-700/50 p-6 rounded-2xl border border-slate-100 text-sm">
                                    <div class="flex justify-between text-slate-500">
                                        <span>@label('account.subtotal', 'Subtotal:')</span>
                                        <span class="font-semibold text-slate-700">${{ number_format($selectedOrder->order_subtotal, 2) }}</span>
                                    </div>
                                    @if($selectedOrder->order_discounts > 0)
                                        <div class="flex justify-between text-red-500 font-semibold">
                                            <span>@label('account.discounts', 'Discounts:')</span>
                                            <span>-${{ number_format($selectedOrder->order_discounts, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between text-slate-500">
                                        <span>@label('account.shipping', 'Shipping:')</span>
                                        <span class="font-semibold text-slate-700">${{ number_format($selectedOrder->order_shipping, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-slate-500">
                                        <span>@label('account.tax', 'Tax:')</span>
                                        <span class="font-semibold text-slate-700">${{ number_format($selectedOrder->order_tax, 2) }}</span>
                                    </div>
                                    <div class="border-t border-slate-200 pt-3 flex justify-between text-slate-900 font-extrabold text-base">
                                        <span>@label('account.col_total', 'Total'):</span>
                                        <span class="text-indigo-600">${{ number_format($selectedOrder->order_total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-slate-500 text-xs pt-3 border-t border-slate-100 mt-2">
                                        <span>@label('account.status_label', 'Status:')</span>
                                        <span class="font-bold text-indigo-600 uppercase">{{ $selectedOrder->statusList ? $selectedOrder->statusList->customerdisplay : 'Processing' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Orders List --}}
                    <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-lg text-slate-800">@label('account.your_orders', 'Your Orders')</h3>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-50 text-slate-500 border border-slate-200/60">
                                @label('account.showing', 'Showing') {{ $orders->count() }} @label('account.of', 'of') {{ $orders->total() }} @label('account.orders', 'orders')
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/50 dark:bg-slate-700/60">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.col_order', 'Order #')</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.col_date', 'Date')</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.col_total', 'Total')</th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.col_status', 'Status')</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.col_action', 'Action')</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($orders as $order)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-sm text-slate-900">#{{ $order->order_invoice_no }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                                {{ $order->order_date ? $order->order_date->format('M d, Y') : $order->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-sm text-slate-800">
                                                ${{ number_format($order->order_total, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase rounded-lg">
                                                    {{ $order->statusList ? $order->statusList->customerdisplay : 'Processing' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button wire:click="viewOrderDetails({{ $order->id }})"
                                                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all">
                                                    @label('account.view_details', 'View Details')
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                                    <div class="p-3 rounded-2xl bg-indigo-50 text-indigo-500 mb-4">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                    </div>
                                                    <h4 class="font-bold text-slate-800 text-base">@label('account.no_orders', 'No orders found')</h4>
                                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">@label('account.no_orders_message', 'You haven\'t placed any orders yet. Visit our shop to browse products.')</p>
                                                    <a href="{{ route('shop.index') }}"
                                                       class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition-all">
                                                        @label('account.browse_shop', 'Browse Shop')
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($orders->hasPages())
                            <div class="border-t border-slate-100 px-6 py-4">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                @endif

            {{-- ═══ DOWNLOADS TAB ════════════════════════════════════════════════ --}}
            @elseif($tab === 'downloads' && $downloadsTabEnabled)
                <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-lg text-slate-800">@label('account.your_downloads', 'Your Downloads')</h3>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-50 text-slate-500 border border-slate-200/60">
                            @label('account.showing', 'Showing') {{ $downloads->count() }} @label('account.of', 'of') {{ $downloads->total() }} downloads
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.col_file_name', 'File Name')</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.col_order', 'Order #')</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.col_expires', 'Expires')</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.downloads_tab', 'Downloads')</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">@label('account.col_action', 'Action')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($downloads as $item)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-slate-900 text-sm">{{ $item->item_name }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            #{{ $item->order->order_invoice_no }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500">
                                            @if($item->download_expiration)
                                                {{ $item->download_expiration->format('M d, Y') }}
                                            @else
                                                @label('account.never', 'Never')
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500 font-semibold">
                                            {{ $item->downloads_counter }} / {{ $item->downloads_max_allowed }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if(($item->download_expiration && now()->greaterThan($item->download_expiration)) || $item->downloads_counter >= $item->downloads_max_allowed)
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-xl text-xs font-bold bg-red-50 text-red-700 border border-red-100 uppercase">@label('account.expired', 'Expired')</span>
                                            @else
                                                <a href="{{ route('products.download', [$item->id, $item->order->order_external_id]) }}"
                                                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500 transition-all shadow-sm">
                                                    @label('account.download', 'Download')
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                                <div class="p-3 rounded-2xl bg-indigo-50 text-indigo-500 mb-4">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                </div>
                                                <h4 class="font-bold text-slate-800 text-base">@label('account.no_downloads', 'No downloads available')</h4>
                                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">@label('account.no_downloads_message', 'You haven\'t purchased any digital items yet, or access has expired.')</p>
                                                <a href="{{ route('shop.index') }}"
                                                   class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition-all">
                                                    @label('account.browse_shop', 'Browse Shop')
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($downloads->hasPages())
                        <div class="border-t border-slate-100 px-6 py-4">
                            {{ $downloads->links() }}
                        </div>
                    @endif
                </div>

            {{-- ═══ TICKETS TAB ══════════════════════════════════════════════════ --}}
            @elseif($tab === 'tickets' && $ticketsTabEnabled)

                {{-- Stats Grid --}}
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700/60 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 text-slate-200/60 dark:text-slate-600/50 group-hover:scale-110 transition-transform pointer-events-none">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10H7v-2h10v2zm0-4H7V7h10v2zm0 8H7v-2h10v2z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">@label('account.total_tickets', 'Total Tickets')</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-2">{{ array_sum($counts) }}</h3>
                    </div>
                    <div class="bg-white dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700/60 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 text-blue-100/60 dark:text-blue-900/40 group-hover:scale-110 transition-transform pointer-events-none">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">@label('account.open_in_process', 'Open & In Process')</p>
                        <h3 class="text-3xl font-extrabold text-blue-600 dark:text-blue-400 mt-2">
                            {{ ($counts['open'] ?? 0) + ($counts['in_process'] ?? 0) + ($counts['assigned'] ?? 0) }}
                        </h3>
                    </div>
                    <div class="bg-white dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700/60 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 text-emerald-100/60 dark:text-emerald-900/40 group-hover:scale-110 transition-transform pointer-events-none">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">@label('account.completed', 'Completed')</p>
                        <h3 class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-2">{{ $counts['completed'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-white dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700/60 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 text-slate-200/60 dark:text-slate-600/50 group-hover:scale-110 transition-transform pointer-events-none">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">@label('account.closed', 'Closed')</p>
                        <h3 class="text-3xl font-extrabold text-slate-700 dark:text-slate-200 mt-2">{{ $counts['closed'] ?? 0 }}</h3>
                    </div>
                </div>

                {{-- Tickets List --}}
                <div class="bg-white dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700/60 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                        <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">@label('account.support_history', 'Support History')</h3>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700">
                            @label('account.showing', 'Showing') {{ $tickets->count() }} @label('account.of', 'of') {{ $tickets->total() }} tickets
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/60">
                            <thead class="bg-slate-50/50 dark:bg-slate-700/60">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-300">@label('account.col_ticket', 'Ticket')</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-300">@label('account.col_status', 'Status')</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-300">@label('account.col_replies', 'Replies')</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-300">@label('account.col_updated', 'Updated')</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-300">@label('account.col_action', 'Action')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 bg-white dark:bg-slate-800">
                                @forelse ($tickets as $ticket)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-slate-900 text-sm hover:text-indigo-600 transition-colors">
                                                    <a href="{{ route('tickets.show', $ticket) }}" wire:navigate>{{ $ticket->title }}</a>
                                                </span>
                                                <span class="text-xs text-slate-400 mt-0.5">#{{ $ticket->id }} · opened {{ $ticket->created_at->format('M j, Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-ticket-status-badge :status="$ticket->status" />
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($ticket->replies_count > 0)
                                                <span class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 border border-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-600">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                    {{ $ticket->replies_count }}
                                                </span>
                                            @else
                                                <span class="text-xs text-slate-400">@label('account.no_replies', '0 replies')</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $ticket->updated_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('tickets.show', $ticket) }}" wire:navigate
                                               class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all">
                                                @label('account.view', 'View')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                                <div class="p-3 rounded-2xl bg-indigo-50 text-indigo-500 mb-4">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </div>
                                                <h4 class="font-bold text-slate-800 text-base">@label('account.no_tickets', 'No tickets yet')</h4>
                                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">@label('account.no_tickets_message', 'If you need help or have a question, submit your first ticket to contact support.')</p>
                                                <a href="{{ route('tickets.create') }}" wire:navigate
                                                   class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition-all">
                                                    @label('account.submit_ticket', 'Submit Ticket')
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($tickets->hasPages())
                        <div class="border-t border-slate-100 px-6 py-4">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>
