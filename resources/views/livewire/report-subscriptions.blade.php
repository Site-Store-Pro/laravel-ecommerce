<div class="space-y-6">
    <!-- Header with KPI summary cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Subscriptions</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalCount) }}</span>
                <span class="text-xs font-semibold text-slate-500">In Selected Period</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">Active Subscriptions</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ number_format($activeCount) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                    {{ $totalCount > 0 ? round(($activeCount / $totalCount) * 100) : 0 }}% active
                </span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-rose-500 dark:text-rose-400 uppercase tracking-wider block">Cancelled Subscriptions</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-rose-500 dark:text-rose-400">{{ number_format($cancelledCount) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                    {{ $totalCount > 0 ? round(($cancelledCount / $totalCount) * 100) : 0 }}% churn
                </span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block">Active Recurring Value</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">${{ number_format($activeMonthlyValue, 2) }}</span>
                <span class="text-xs font-semibold text-slate-500">Per Cycle</span>
            </div>
        </div>
    </div>

    <!-- Controls, Filters & Export Card -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
        <!-- Date Range Filter Selector -->
        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-700/60 p-1 rounded-xl">
                @foreach(['30' => 'Last 30 Days', '60' => 'Last 60 Days', '90' => 'Last 90 Days', '120' => 'Last 120 Days', 'YTD' => 'Year to Date', 'custom' => 'Custom'] as $key => $label)
                    <button type="button" wire:click="setRange('{{ $key }}')"
                            class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $dateRange === $key ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="flex items-center gap-2">
                <button type="button" wire:click="exportCsv"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition shadow-xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export CSV
                </button>
                <button type="button" wire:click="exportXlsx"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export Excel
                </button>
            </div>
        </div>

        @if($dateRange === 'custom')
            <div class="flex flex-wrap items-center gap-3 p-3 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-500">From:</label>
                    <input type="date" wire:model.live="startDate" class="px-3 py-1.5 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 font-mono">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-500">To:</label>
                    <input type="date" wire:model.live="endDate" class="px-3 py-1.5 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 font-mono">
                </div>
            </div>
        @endif

        <!-- Secondary Filters & Search Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Search Subscriptions</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search customer, email, order #, sub ID..."
                           class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-indigo-500 font-medium">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Status Filter</label>
                <select wire:model.live="statusFilter"
                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-indigo-500 font-medium">
                    <option value="all">All Statuses (Active &amp; Cancelled)</option>
                    <option value="active">Active Only</option>
                    <option value="cancelled">Cancelled Only</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Payment Provider</label>
                <select wire:model.live="providerFilter"
                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-indigo-500 font-medium">
                    <option value="all">All Providers (Stripe, Paddle, PayPal)</option>
                    <option value="stripe">Stripe Billing</option>
                    <option value="paddle">Paddle Billing</option>
                    <option value="paypal">PayPal Subscriptions</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base">Subscription Accounts &amp; Associated Payments</h3>
            <span class="text-xs text-slate-400 font-medium">{{ $subscriptions->total() }} matching records</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-400 uppercase font-bold text-[11px] tracking-wider border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th class="px-5 py-3.5">Order / Date</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Product / Plan</th>
                        <th class="px-5 py-3.5">Provider &amp; Sub ID</th>
                        <th class="px-5 py-3.5 text-right">Recurring Price</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Payment History</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($subscriptions as $sub)
                        @php
                            $payments = $this->getPaymentsForItem($sub);
                            $isExpanded = $expandedDetailId === $sub->id;
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition {{ $isExpanded ? 'bg-indigo-50/20 dark:bg-indigo-950/20' : '' }}">
                            <td class="px-5 py-4">
                                @if($sub->order)
                                    <a href="{{ route('admin.ecommerce.order-details', $sub->order->id) }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline block font-mono">
                                        #{{ $sub->order->order_invoice_no }}
                                    </a>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">
                                        {{ $sub->order->order_date ? $sub->order->order_date->format('M d, Y h:i A') : 'N/A' }}
                                    </span>
                                @else
                                    <span class="font-mono text-slate-400">Order #{{ $sub->order_id }}</span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800 dark:text-slate-100 block">{{ $sub->order?->user?->name ?? 'Guest User' }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $sub->order?->user?->email ?? '-' }}</span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $sub->item_name }}</span>
                                @if($sub->variant && $sub->variant->product)
                                    <span class="text-[11px] text-slate-400 block">SKU: {{ $sub->variant->sku ?: 'N/A' }}</span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ match(strtolower($sub->subscription_provider ?? '')) { 'stripe' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300', 'paypal' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300', 'paddle' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300', default => 'bg-slate-100 text-slate-600' } }}">
                                    {{ $sub->subscription_provider ?: 'Processor' }}
                                </span>
                                @if($sub->subscription_plan_id)
                                    <code class="block font-mono text-[10px] text-slate-500 dark:text-slate-400 mt-1 truncate max-w-xs" title="{{ $sub->subscription_plan_id }}">
                                        {{ $sub->subscription_plan_id }}
                                    </code>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right">
                                <span class="font-extrabold text-slate-900 dark:text-white block">${{ number_format($sub->final_price, 2) }}</span>
                                <span class="text-[10px] text-slate-400 block">per cycle</span>
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if($sub->active_subscription)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Cancelled
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                <button type="button" wire:click="togglePayments({{ $sub->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold transition {{ $isExpanded ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <span>{{ $payments->count() }} Payment{{ $payments->count() === 1 ? '' : 's' }}</span>
                                    <svg class="w-3 h-3 transition-transform {{ $isExpanded ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($sub->order)
                                        <a href="{{ route('admin.ecommerce.order-details', $sub->order->id) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition" title="View Order">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    @endif

                                    @if($sub->active_subscription)
                                        <button type="button"
                                                onclick="confirm('Are you sure you want to cancel this recurring subscription agreement with the payment processor?') || event.stopImmediatePropagation()"
                                                wire:click="cancelSubscription({{ $sub->id }})"
                                                wire:loading.attr="disabled"
                                                class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                                            Cancel Sub
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Expandable Associated Payments History Section -->
                        @if($isExpanded)
                            <tr class="bg-slate-50/80 dark:bg-slate-900/60 border-y border-indigo-100 dark:border-indigo-900/50">
                                <td colspan="8" class="p-5">
                                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-xs space-y-3">
                                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                                            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700 dark:text-slate-200 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                Associated Payment Transactions for Subscription #{{ $sub->id }}
                                            </h4>
                                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Total Billed: ${{ number_format((float)$payments->sum('payment_amount'), 2) }}</span>
                                        </div>

                                        @if($payments->isNotEmpty())
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-left text-xs">
                                                    <thead class="bg-slate-50 dark:bg-slate-700 text-slate-400 uppercase font-bold text-[10px]">
                                                        <tr>
                                                            <th class="px-3 py-2">Payment ID</th>
                                                            <th class="px-3 py-2">Payment Date</th>
                                                            <th class="px-3 py-2">Amount</th>
                                                            <th class="px-3 py-2">Method</th>
                                                            <th class="px-3 py-2">Auth Code / Transaction ID</th>
                                                            <th class="px-3 py-2">Status</th>
                                                            <th class="px-3 py-2">Notes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 font-mono">
                                                        @foreach($payments as $pmt)
                                                            <tr>
                                                                <td class="px-3 py-2 font-bold text-slate-700 dark:text-slate-300">#{{ $pmt->id }}</td>
                                                                <td class="px-3 py-2 text-slate-600 dark:text-slate-400">{{ $pmt->payment_date ? \Carbon\Carbon::parse($pmt->payment_date)->format('Y-m-d H:i') : 'N/A' }}</td>
                                                                <td class="px-3 py-2 font-bold text-emerald-600 dark:text-emerald-400 font-sans">${{ number_format((float)$pmt->payment_amount, 2) }}</td>
                                                                <td class="px-3 py-2 font-sans">{{ $pmt->payment_method ?: 'Online Gateway' }}</td>
                                                                <td class="px-3 py-2 text-slate-500 dark:text-slate-400 text-[11px] truncate max-w-xs">{{ $pmt->authorization_code ?: '-' }}</td>
                                                                <td class="px-3 py-2 font-sans">
                                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">Success</span>
                                                                </td>
                                                                <td class="px-3 py-2 font-sans text-slate-400 text-[11px]">{{ $pmt->payment_notes ?: '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-400 italic py-2">No individual recurring transaction records registered in the payments table yet.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <p class="text-sm font-bold text-slate-600 dark:text-slate-300">No subscriptions found</p>
                                <p class="text-xs text-slate-400 mt-1">Try expanding your date range or adjusting the search and status filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
</div>
