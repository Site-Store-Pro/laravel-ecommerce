<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wrapper Grid -->
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

                    <a href="{{ route('admin.ecommerce.categories') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Categories
                    </a>

                    <a href="{{ route('admin.ecommerce.brands') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        Brands
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

            <!-- Orders Content -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Notifications -->
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="p-4 bg-red-50 rounded-2xl border border-red-100 flex items-center gap-3 text-red-800 text-sm font-semibold">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Orders List -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6 font-sans">Placed Orders</h3>

                    <!-- Live search bar -->
                    <div class="mb-6 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input wire:model.live="search" type="text" placeholder="Search orders by customer name, order #, email..." class="pl-11 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 text-slate-700 placeholder-slate-400 rounded-2xl focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition duration-150 text-sm">
                    </div>

                    @if($orders->isEmpty())
                        <p class="text-sm text-slate-500 text-center py-6">No orders found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-500">
                                <thead class="text-xs text-slate-400 uppercase bg-slate-50 rounded-xl">
                                    <tr>
                                        <th class="px-4 py-3">Order #</th>
                                        <th class="px-4 py-3">Customer</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Total</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($orders as $order)
                                        <tr>
                                             <td class="px-4 py-3.5 font-bold text-slate-800">
                                                 <a href="{{ route('admin.ecommerce.order-details', $order->id) }}" wire:navigate class="text-indigo-600 hover:underline">
                                                     {{ $order->order_invoice_no }}
                                                 </a>
                                             </td>
                                             <td class="px-4 py-3.5">{{ $order->user ? $order->user->name : 'Guest' }}</td>
                                             <td class="px-4 py-3.5">{{ $order->order_date->format('M d, Y H:i') }}</td>
                                             <td class="px-4 py-3.5 font-bold text-slate-900">${{ number_format($order->order_total, 2) }}</td>
                                             <td class="px-4 py-3.5">
                                                 @if($order->order_status == 1)
                                                     <span class="px-2 py-0.5 text-xs font-bold bg-red-50 text-red-700 rounded-full border border-red-100">
                                                         {{ $order->statusList ? $order->statusList->AdminDisplay : 'Open (PENDING)' }}
                                                     </span>
                                                 @else
                                                     <span class="px-2 py-0.5 text-xs font-bold bg-slate-50 text-slate-700 rounded-full border border-slate-200">
                                                         {{ $order->statusList ? $order->statusList->AdminDisplay : 'Pending (' . $order->order_status . ')' }}
                                                     </span>
                                                 @endif
                                             </td>
                                             <td class="px-4 py-3.5 text-right flex justify-end gap-2">
                                                 <a href="{{ route('admin.ecommerce.order-details', $order->id) }}" wire:navigate class="px-2.5 py-1.5 bg-slate-50 text-slate-700 text-xs font-bold rounded-lg border border-slate-200 hover:bg-slate-100 transition-colors flex items-center justify-center">Details</a>
                                             </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($orders->hasPages())
                            <div class="mt-6 pt-4 border-t border-slate-100">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
