<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users') }}" wire:navigate
               class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">Customer Profile: {{ $user->name }}</h2>
                <p class="text-sm text-slate-500 mt-1">Review customer tickets history and e-commerce transactions overview</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Summary Card -->
            <div class="bg-white border border-slate-200/70 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl flex items-center justify-center font-extrabold text-xl bg-indigo-100 text-indigo-700 shrink-0 shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="font-bold text-lg text-slate-800">{{ $user->name }}</h3>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-500">
                                <span>{{ $user->email }}</span>
                                @if($user->company)
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-800">{{ $user->company }}</span>
                                @endif
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $user->role_id->badgeClasses() }}">
                                    {{ $user->role_id->label() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-sm text-slate-500 md:text-right space-y-1">
                        <div><span class="text-slate-400">Joined:</span> <span class="font-semibold text-slate-700">{{ $user->created_at->format('F d, Y') }}</span></div>
                        <div><span class="text-slate-400">Status:</span> 
                            @if ($user->email_verified_at)
                                <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-bold">Verified</span>
                            @else
                                <span class="text-xs text-amber-500 font-bold">Unverified</span>
                            @endif
                        </div>
                        @if (!empty($user->provider))
                            <div class="text-xs text-slate-600 font-medium capitalize">{{ $user->provider }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Support Tickets History Panel -->
                <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800 text-base">Support Tickets History</h3>
                        <span class="bg-indigo-100 text-indigo-700 text-xs px-2.5 py-1 rounded-full font-bold">{{ $tickets->count() }} Total</span>
                    </div>

                    <div class="divide-y divide-slate-100 overflow-y-auto max-h-[500px] flex-1">
                        @forelse($tickets as $ticket)
                            <div class="p-6 hover:bg-slate-50/40 transition-colors space-y-3">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-slate-800 text-sm leading-snug">
                                            {{ $ticket->subject }}
                                        </h4>
                                        <p class="text-xs text-slate-400">Ticket ID: #{{ $ticket->id }} • Opened {{ $ticket->created_at->diffForHumans() }}</p>
                                    </div>
                                    
                                    <x-ticket-status-badge :status="$ticket->status" />
                                </div>

                                <div class="flex justify-between items-center pt-1">
                                    <span class="text-xs text-slate-500">Category: <span class="font-semibold text-slate-700">{{ $ticket->category ? $ticket->category->name : '-' }}</span></span>
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" wire:navigate
                                       class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                                        Reply & View
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                <p class="text-sm font-semibold">No tickets found</p>
                                <p class="text-xs text-slate-400 mt-1">This user has not submitted any support tickets yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- E-Commerce Orders History Panel -->
                <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800 text-base">E-Commerce Orders History</h3>
                        <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-bold">{{ $orders->count() }} Total</span>
                    </div>

                    <div class="divide-y divide-slate-100 overflow-y-auto max-h-[500px] flex-1">
                        @forelse($orders as $order)
                            <div class="p-6 hover:bg-slate-50/40 transition-colors space-y-3">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-slate-800 text-sm">
                                            Order #{{ $order->order_invoice_no }}
                                        </h4>
                                        <p class="text-xs text-slate-400">Placed on {{ $order->order_date->format('M d, Y h:i A') }}</p>
                                    </div>
                                    
                                    @php
                                        $orderStatusStyles = [
                                            1 => 'bg-indigo-50 text-indigo-700 border-indigo-150', // Paid
                                            2 => 'bg-emerald-50 text-emerald-700 border-emerald-150', // Shipped
                                            3 => 'bg-red-50 text-red-700 border-red-150', // Refunded
                                        ];
                                        $orderStatusLabels = [1 => 'Paid', 2 => 'Shipped', 3 => 'Refunded'];
                                    @endphp
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-semibold border rounded-full {{ $orderStatusStyles[$order->order_status] ?? 'bg-slate-50 text-slate-500 border-slate-100' }}">
                                        {{ $orderStatusLabels[$order->order_status] ?? 'Unknown' }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center pt-1">
                                    <span class="text-sm font-bold text-slate-900">Total: ${{ number_format($order->order_total, 2) }}</span>
                                    <a href="{{ route('admin.ecommerce.order-details', $order->id) }}" wire:navigate
                                       class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                                        View Details
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <p class="text-sm font-semibold">No orders found</p>
                                <p class="text-xs text-slate-400 mt-1">This user has not placed any orders yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
