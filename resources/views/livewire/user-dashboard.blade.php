<div>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            @if($tab === 'tickets')
                <div>
                    <h2 class="font-bold text-2xl text-slate-900 dark:text-slate-100 leading-tight">
                        {{ __('Support Center') }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Manage and track your support requests</p>
                </div>
                <a href="{{ route('tickets.create') }}" wire:navigate
                   class="inline-flex items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-100 hover:from-indigo-600 hover:to-violet-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Ticket') }}
                </a>
            @elseif($tab === 'orders')
                <div>
                    <h2 class="font-bold text-2xl text-slate-900 dark:text-slate-100 leading-tight">
                        {{ __('Order History') }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">View and track your previous purchases</p>
                </div>
            @elseif($tab === 'downloads')
                <div>
                    <h2 class="font-bold text-2xl text-slate-900 dark:text-slate-100 leading-tight">
                        {{ __('Digital Downloads') }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Access your purchased digital files and templates</p>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('status'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200/80 p-4 text-sm text-emerald-800 flex items-center gap-2 shadow-sm animate-fade-in">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="border-b border-slate-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button wire:click="$set('tab', 'tickets')" 
                            class="shrink-0 border-b-2 py-4 px-1 text-sm font-semibold transition-all focus:outline-none 
                            {{ $tab === 'tickets' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:border-slate-350 hover:text-slate-700' }}">
                        {{ __('Support Tickets') }}
                    </button>
                    <button wire:click="$set('tab', 'orders')" 
                            class="shrink-0 border-b-2 py-4 px-1 text-sm font-semibold transition-all focus:outline-none 
                            {{ $tab === 'orders' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:border-slate-350 hover:text-slate-700' }}">
                        {{ __('Order History') }}
                    </button>
                    <button wire:click="$set('tab', 'downloads')" 
                            class="shrink-0 border-b-2 py-4 px-1 text-sm font-semibold transition-all focus:outline-none 
                            {{ $tab === 'downloads' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:border-slate-350 hover:text-slate-700' }}">
                        {{ __('Downloads') }}
                    </button>
                </nav>
            </div>

            @if($tab === 'tickets')
                <!-- Stats Grid -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Stat 1 -->
                    <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 text-slate-100 group-hover:scale-110 transition-transform pointer-events-none">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10H7v-2h10v2zm0-4H7V7h10v2zm0 8H7v-2h10v2z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Tickets</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ array_sum($counts) }}</h3>
                    </div>
                    <!-- Stat 2 -->
                    <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 text-blue-50 group-hover:scale-110 transition-transform pointer-events-none">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Open & In Process</p>
                        <h3 class="text-3xl font-extrabold text-blue-600 mt-2">
                            {{ ($counts['open'] ?? 0) + ($counts['in_process'] ?? 0) + ($counts['assigned'] ?? 0) }}
                        </h3>
                    </div>
                    <!-- Stat 3 -->
                    <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 text-emerald-50 group-hover:scale-110 transition-transform pointer-events-none">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Completed</p>
                        <h3 class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $counts['completed'] ?? 0 }}</h3>
                    </div>
                    <!-- Stat 4 -->
                    <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 text-slate-50 group-hover:scale-110 transition-transform pointer-events-none">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Closed</p>
                        <h3 class="text-3xl font-extrabold text-slate-700 mt-2">{{ $counts['closed'] ?? 0 }}</h3>
                    </div>
                </div>

                <!-- Tickets List -->
                <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-lg text-slate-800">Support History</h3>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-50 text-slate-500 border border-slate-200/60">
                            Showing {{ $tickets->count() }} of {{ $tickets->total() }} tickets
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Ticket</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Replies</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Updated</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
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
                                                <span class="text-xs text-slate-400">0 replies</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $ticket->updated_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('tickets.show', $ticket) }}" wire:navigate 
                                               class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all">
                                                View
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
                                                <h4 class="font-bold text-slate-800 text-base">No tickets yet</h4>
                                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">If you need help or have a question, submit your first ticket to contact support.</p>
                                                <a href="{{ route('tickets.create') }}" wire:navigate
                                                   class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition-all">
                                                    Submit Ticket
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
            @elseif($tab === 'orders')
                @if($selectedOrder)
                    <!-- Detailed Order View -->
                    <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h3 class="font-bold text-lg text-slate-800">Order Detail</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Invoice #{{ $selectedOrder->order_invoice_no }} · Placed on {{ $selectedOrder->order_date ? $selectedOrder->order_date->format('M d, Y') : $selectedOrder->created_at->format('M d, Y') }}</p>
                            </div>
                            <button wire:click="closeOrderDetails" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all">
                                Back to List
                            </button>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Order Items Table -->
                            <div class="overflow-x-auto border border-slate-150 rounded-xl">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Product</th>
                                            <th class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">Qty</th>
                                            <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Price</th>
                                            <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach($selectedOrder->details as $detail)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-slate-800 text-sm">
                                                            {{ $detail->item_name }}
                                                        </span>
                                                        @if($detail->download_item)
                                                            <span class="inline-flex items-center w-max bg-indigo-50 text-indigo-700 text-[10px] px-1.5 py-0.5 rounded font-bold mt-1">Digital Delivery</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center text-sm font-semibold text-slate-600">
                                                    {{ number_format($detail->item_qty, 0) }}
                                                </td>
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

                            <!-- Financial Summary -->
                            <div class="flex justify-end">
                                <div class="w-80 space-y-3 bg-slate-50/50 p-6 rounded-2xl border border-slate-100 text-sm">
                                    <div class="flex justify-between text-slate-500">
                                        <span>Subtotal:</span>
                                        <span class="font-semibold text-slate-700">${{ number_format($selectedOrder->order_subtotal, 2) }}</span>
                                    </div>
                                    @if($selectedOrder->order_discounts > 0)
                                        <div class="flex justify-between text-red-500 font-semibold">
                                            <span>Discounts:</span>
                                            <span>-${{ number_format($selectedOrder->order_discounts, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between text-slate-500">
                                        <span>Shipping:</span>
                                        <span class="font-semibold text-slate-700">${{ number_format($selectedOrder->order_shipping, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-slate-500">
                                        <span>Tax:</span>
                                        <span class="font-semibold text-slate-700">${{ number_format($selectedOrder->order_tax, 2) }}</span>
                                    </div>
                                    <div class="border-t border-slate-200 pt-3 flex justify-between text-slate-900 font-extrabold text-base">
                                        <span>Total:</span>
                                        <span class="text-indigo-650">${{ number_format($selectedOrder->order_total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-slate-500 text-xs pt-3 border-t border-slate-100 mt-2">
                                        <span>Status:</span>
                                        <span class="font-bold text-indigo-600 uppercase">{{ $selectedOrder->statusList ? $selectedOrder->statusList->customerdisplay : 'Processing' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Orders List -->
                    <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-lg text-slate-800">Your Orders</h3>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-50 text-slate-500 border border-slate-200/60">
                                Showing {{ $orders->count() }} of {{ $orders->total() }} orders
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Order #</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Total</th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($orders as $order)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-sm text-slate-900">
                                                #{{ $order->order_invoice_no }}
                                            </td>
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
                                                    View Details
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
                                                    <h4 class="font-bold text-slate-800 text-base">No orders found</h4>
                                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">You haven't placed any orders yet. Visit our shop to browse products.</p>
                                                    <a href="{{ route('shop.index') }}" 
                                                       class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition-all">
                                                        Browse Shop
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
            @elseif($tab === 'downloads')
                <!-- Downloads Content -->
                <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-lg text-slate-800">Your Downloads</h3>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-50 text-slate-500 border border-slate-200/60">
                            Showing {{ $downloads->count() }} of {{ $downloads->total() }} downloads
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">File Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Order #</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Expires</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Downloads</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($downloads as $item)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-slate-900 text-sm">
                                                {{ $item->item_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            #{{ $item->order->order_invoice_no }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500">
                                            {{ $item->download_expiration ? $item->download_expiration->format('M d, Y') : 'Never' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500 font-semibold">
                                            {{ $item->downloads_counter }} / {{ $item->downloads_max_allowed }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if(($item->download_expiration && now()->greaterThan($item->download_expiration)) || $item->downloads_counter >= $item->downloads_max_allowed)
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-xl text-xs font-bold bg-red-50 text-red-700 border border-red-100 uppercase">
                                                    Expired
                                                </span>
                                            @else
                                                <a href="{{ route('products.download', [$item->id, $item->order->order_external_id]) }}" 
                                                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500 transition-all shadow-sm">
                                                    Download
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
                                                <h4 class="font-bold text-slate-800 text-base">No downloads available</h4>
                                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">You haven't purchased any digital items yet, or access has expired.</p>
                                                <a href="{{ route('shop.index') }}" 
                                                   class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition-all">
                                                    Browse Shop
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
            @endif
        </div>
    </div>
</div>
