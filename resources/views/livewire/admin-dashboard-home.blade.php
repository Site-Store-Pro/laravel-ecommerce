<div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 font-sans">
    <x-toast-alert />

    {{-- DEMO STORE CONTENT BANNER --}}
    @if(auth()->check() && (auth()->user()->role_id == 3 || auth()->user()->isAdmin()) && $this->hasDemoContent)
    <div class="rounded-2xl border border-amber-300 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-600/50 overflow-hidden shadow-sm">
        <div class="px-6 py-4 flex items-start gap-4">
            {{-- Icon --}}
            <div class="shrink-0 mt-0.5">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-800/50 text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.347.347a3.5 3.5 0 00-1.025 2.475V19a2 2 0 11-4 0v-.47a3.5 3.5 0 00-1.024-2.476l-.348-.347z"/>
                    </svg>
                </span>
            </div>
            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-bold text-amber-800 dark:text-amber-300">Demo Store Content is Active</h3>
                <p class="mt-1 text-xs text-amber-700 dark:text-amber-400 leading-relaxed">
                    Your store currently contains <strong>demo products, brands, categories, variants, and images</strong> seeded by the
                    <code class="px-1 py-0.5 bg-amber-100 dark:bg-amber-800/60 rounded text-amber-800 dark:text-amber-300 font-mono text-xs">DemoStoreSeeder</code>.
                    When you're ready to go live, use the button below to permanently remove all demo content in one click.
                </p>
                <p class="mt-2 text-xs text-amber-600 dark:text-amber-500">
                    ⚠️ If you have made edits to any demo products, those edits will also be deleted — the system cannot distinguish your modifications from the original demo data.
                </p>
            </div>
            {{-- Action --}}
            <div class="shrink-0">
                <button type="button"
                        wire:click="$set('confirmingDemoPurge', true)"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:bg-red-800 text-white text-xs font-bold transition-all shadow-sm whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Purge Demo Content
                </button>
            </div>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    @if($confirmingDemoPurge)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-data x-init="$el.focus()"
         @keydown.escape.window="$wire.set('confirmingDemoPurge', false)">
        <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden"
             @click.stop>

            {{-- Modal Header --}}
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Confirm Demo Content Deletion</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">This action cannot be undone</p>
                </div>
            </div>

            {{-- Modal Content --}}
            <div class="p-6 space-y-4 text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                <p>You are about to permanently delete all demo-seeded data from the database, including:</p>
                <ul class="list-disc pl-5 space-y-1 font-medium text-slate-700 dark:text-slate-200">
                    <li>All demo products &amp; product variants</li>
                    <li>All demo images &amp; variant attachments</li>
                    <li>All demo categories &amp; category assignments</li>
                    <li>All demo brands &amp; cross-selling rules</li>
                    <li>All demo inventory records &amp; event logs</li>
                </ul>
                <div class="p-3 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300">
                    <strong>Warning:</strong> Any customized titles, prices, descriptions, or images added to demo products will also be permanently deleted.
                </div>
            </div>

            {{-- Modal Actions --}}
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end gap-3">
                <button type="button"
                        wire:click="$set('confirmingDemoPurge', false)"
                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Cancel
                </button>
                <button type="button"
                        wire:click="purgeDemoContent"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Yes, Permanently Delete Demo Content
                </button>
            </div>
        </div>
    </div>
    @endif
    @endif
    <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-4">
        <!-- Quick Nav actions for admins -->
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ url('/') }}" target="_blank" class="btn-theme-primary !text-xs !py-2 !px-4 inline-flex items-center gap-1.5 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Public Site
            </a>
            <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-2xl shadow-sm transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Manage Orders
            </a>
            <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-2xl shadow-sm transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Manage Products
            </a>
            <a href="{{ route('admin.tickets') }}" wire:navigate class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-2xl shadow-sm transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                Manage Tickets
            </a>
        </div>
    </div>

    <!-- KPI Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Sales Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Sales Revenue</span>
                <span class="text-2xl font-extrabold text-slate-900">${{ number_format($totalSales, 2) }}</span>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Orders Processed</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ number_format($totalOrdersCount) }}</span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
        </div>

        <!-- Pending Orders Card -->
        <a href="{{ route('admin.ecommerce.pending-orders') }}" wire:navigate class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex items-center justify-between hover:border-slate-200 hover:shadow-md transition group">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block group-hover:text-slate-500 transition">Pending Orders</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ number_format($pendingOrdersCount) }}</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 group-hover:scale-105 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </a>

        <!-- Customers Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Active Customers</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ number_format($customersCount) }}</span>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Last 10 Orders Table -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-sans">Recent Orders Activity</h3>
                <p class="text-xs text-slate-400 mt-0.5 font-sans">Overview of the last 10 orders processed on the platform.</p>
            </div>
            <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                View All Orders
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($recentOrders->isEmpty())
            <p class="text-xs text-slate-400 text-center py-6">No orders found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-500 divide-y divide-slate-100">
                    <thead>
                        <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th scope="col" class="py-3 px-3">Order / External ID</th>
                            <th scope="col" class="py-3 px-3">Customer Name</th>
                            <th scope="col" class="py-3 px-3 text-center">Items</th>
                            <th scope="col" class="py-3 px-3 text-right">Amount</th>
                            <th scope="col" class="py-3 px-3 text-center">Status</th>
                            <th scope="col" class="py-3 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-50/40 transition">
                                <td class="py-3.5 px-3">
                                    <span class="font-extrabold text-slate-800 block">{{ $order->order_invoice_no }}</span>
                                    @if($order->order_external_id)
                                        <span class="text-[10px] text-slate-400 font-medium block">Ext ID: {{ $order->order_external_id }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 font-semibold text-slate-700">
                                    {{ $order->user ? $order->user->name : 'Guest' }}
                                </td>
                                <td class="py-3.5 px-3 text-center font-extrabold text-slate-600">
                                    {{ $order->details_count }}
                                </td>
                                <td class="py-3.5 px-3 text-right font-extrabold text-slate-900">
                                    ${{ number_format($order->order_total, 2) }}
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    @if($order->order_status == 7)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-700 rounded-full border border-emerald-100">Completed</span>
                                    @elseif($order->order_status == 1)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-50 text-amber-700 rounded-full border border-amber-100">Pending</span>
                                    @elseif($order->order_status == 2)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 rounded-full border border-blue-100">Shipped</span>
                                    @elseif($order->order_status == 3)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-red-50 text-red-700 rounded-full border border-red-100">Refunded</span>
                                    @elseif($order->order_status == 4)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded-full border border-slate-200">Canceled</span>
                                    @elseif($order->order_status == 5)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-violet-50 text-violet-700 rounded-full border border-violet-100">Partially Shipped</span>
                                    @elseif($order->order_status == 6)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-orange-50 text-orange-700 rounded-full border border-orange-100">Back Ordered</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-50 text-slate-500 rounded-full border border-slate-200">Awaiting Payment</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    <a href="{{ route('admin.ecommerce.order-details', $order->id) }}" wire:navigate class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-bold rounded-lg transition duration-150">Manage</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Reports Grid Layout -->
    <div class="space-y-6">
        <!-- Row 1: Order Activity Chart & Abandoned Comparison -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                @livewire('report-order-activity')
            </div>
            <div class="lg:col-span-1">
                @livewire('report-completed-vs-abandoned')
            </div>
        </div>

        <!-- Row 2: Funnel, Spend & Product Performance -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                @livewire('report-cart-conversion')
            </div>
            <div>
                @livewire('report-customer-spend')
            </div>
            <div>
                @livewire('report-product-performance')
            </div>
        </div>
    </div>
</div>
