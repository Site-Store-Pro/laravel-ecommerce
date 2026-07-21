<div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 font-sans">
    <!-- Alert Message Area (Hidden)
    <!--
    <div class="bg-indigo-50 border border-indigo-100 rounded-3xl p-4 text-sm text-indigo-700">
        Admin Home Dashboard
        Hello, {{ auth()->user()->name }}. Here is a real-time overview of your e-commerce platform performance.
    </div>
    -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-4">
        <!-- Quick Nav actions for admins -->
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-tr from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-xs font-bold rounded-2xl shadow-md shadow-indigo-100 transition">
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
