<div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 font-sans">
    <x-toast-alert />

    <!-- Page Header & Tab Navigation -->
    <div class="space-y-4 border-b border-slate-200 dark:border-slate-700 pb-6">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-slate-100 dark:to-indigo-200 bg-clip-text text-transparent">
                Analytics &amp; Standalone Reports
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Select a report below to view full-width interactive performance charts, date-range order exports, sales tax/VAT audits, or product catalog exports.
            </p>
        </div>

        <!-- Navigation Pill Links -->
        <div class="flex items-center gap-1.5 p-1.5 bg-slate-100 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-x-auto flex-wrap">
            <button type="button" wire:click="$set('activeTab', 'report_order_activity')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'report_order_activity' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Order Activity &amp; Revenue
            </button>
            <button type="button" wire:click="$set('activeTab', 'report_subscriptions')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'report_subscriptions' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Subscriptions &amp; Recurring Billing
            </button>
            <button type="button" wire:click="$set('activeTab', 'report_completed_vs_abandoned')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'report_completed_vs_abandoned' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Completed vs. Abandoned
            </button>
            <button type="button" wire:click="$set('activeTab', 'report_cart_conversion')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'report_cart_conversion' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Cart Funnel Conversion
            </button>
            <button type="button" wire:click="$set('activeTab', 'report_customer_spend')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'report_customer_spend' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Customer Spend Distribution
            </button>
            <button type="button" wire:click="$set('activeTab', 'report_product_performance')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'report_product_performance' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Top Products Performance
            </button>
            <button type="button" wire:click="$set('activeTab', 'order_export')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'order_export' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Order Export Report
            </button>
            <button type="button" wire:click="$set('activeTab', 'tax_report')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'tax_report' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Sales Tax / VAT Report
            </button>
            <button type="button" wire:click="$set('activeTab', 'product_export')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'product_export' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Products Export
            </button>
            <button type="button" wire:click="$set('activeTab', 'inventory_export')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'inventory_export' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Multi-Warehouse Inventory Export
            </button>
            <button type="button" wire:click="$set('activeTab', 'customer_export')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'customer_export' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Customers Export (Retail &amp; Wholesale)
            </button>
            <button type="button" wire:click="$set('activeTab', 'optin_export')"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ $activeTab === 'optin_export' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
                Opt-In Subscribers Export
            </button>
        </div>
    </div>

    <!-- STANDALONE REPORT 1: ORDER ACTIVITY & REVENUE -->
    @if($activeTab === 'report_order_activity')
    <div class="w-full">
        @livewire('report-order-activity')
    </div>
    @endif

    <!-- STANDALONE REPORT: SUBSCRIPTIONS & RECURRING BILLING -->
    @if($activeTab === 'report_subscriptions')
    <div class="w-full">
        @livewire('report-subscriptions')
    </div>
    @endif

    <!-- STANDALONE REPORT 2: COMPLETED VS ABANDONED CARTS -->
    @if($activeTab === 'report_completed_vs_abandoned')
    <div class="w-full">
        @livewire('report-completed-vs-abandoned')
    </div>
    @endif

    <!-- STANDALONE REPORT 3: CART FUNNEL CONVERSION -->
    @if($activeTab === 'report_cart_conversion')
    <div class="w-full">
        @livewire('report-cart-conversion')
    </div>
    @endif

    <!-- STANDALONE REPORT 4: CUSTOMER SPEND DISTRIBUTION -->
    @if($activeTab === 'report_customer_spend')
    <div class="w-full">
        @livewire('report-customer-spend')
    </div>
    @endif

    <!-- STANDALONE REPORT 5: TOP PRODUCTS PERFORMANCE -->
    @if($activeTab === 'report_product_performance')
    <div class="w-full">
        @livewire('report-product-performance')
    </div>
    @endif

    <!-- STANDALONE REPORT 6: ORDER EXPORT REPORT -->
    @if($activeTab === 'order_export')
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-8 shadow-sm space-y-6">
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Order Export Report</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Filter orders by date range and export complete order records with line item details, customer info, sales, tax, shipping, and discount line totals.
            </p>
        </div>

        <!-- Filters Form -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 bg-slate-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-700">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Start Date</label>
                <input type="date" wire:model="orderStartDate"
                       class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 shadow-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">End Date</label>
                <input type="date" wire:model="orderEndDate"
                       class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 shadow-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">File Format</label>
                <div class="flex items-center gap-6 pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-700 dark:text-slate-300">
                        <input type="radio" wire:model="orderExportFormat" value="csv" class="text-indigo-600 focus:ring-indigo-500">
                        CSV (.csv)
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-700 dark:text-slate-300">
                        <input type="radio" wire:model="orderExportFormat" value="xlsx" class="text-indigo-600 focus:ring-indigo-500">
                        Excel (.xlsx)
                    </label>
                </div>
            </div>
        </div>

        <!-- Export Details Card -->
        <div class="p-6 bg-indigo-50/60 dark:bg-indigo-950/40 rounded-2xl border border-indigo-100 dark:border-indigo-900/60 space-y-3">
            <h4 class="text-xs font-extrabold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">Included Export Columns</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs text-indigo-900 dark:text-indigo-200 font-medium">
                <div>• Order ID &amp; Invoice No</div>
                <div>• Order Date &amp; Status</div>
                <div>• Customer Name &amp; Email</div>
                <div>• Shipping Address &amp; State</div>
                <div>• Line Item Purchased Name</div>
                <div>• Variant Name &amp; SKU</div>
                <div>• Line Item Price &amp; Qty</div>
                <div>• Subtotal, Shipping, Tax, Total</div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="flex justify-end pt-2">
            <button type="button" wire:click="exportOrders" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-2xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Order Report ({{ strtoupper($orderExportFormat) }})
            </button>
        </div>
    </div>
    @endif

    <!-- STANDALONE REPORT 7: SALES TAX / VAT REPORT -->
    @if($activeTab === 'tax_report')
    <div class="space-y-6">
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Sales Tax / VAT Audit Report</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Filter sales tax and VAT collected by date range, country, and state/province.
                    </p>
                </div>

                <button type="button" wire:click="exportTaxReport" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl shadow-md transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Tax Report ({{ strtoupper($taxExportFormat) }})
                </button>
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 bg-slate-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-700">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Start Date</label>
                    <input type="date" wire:model.live="taxStartDate"
                           class="w-full px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">End Date</label>
                    <input type="date" wire:model.live="taxEndDate"
                           class="w-full px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Country</label>
                    <select wire:model.live="taxCountry" class="w-full px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 shadow-sm">
                        <option value="">-- All Countries --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->code }}">{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">State / Province</label>
                    @if(in_array(strtoupper($taxCountry), ['US', 'CA']))
                        <select wire:model.live="taxState" class="w-full px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 shadow-sm">
                            <option value="">-- All States/Provinces --</option>
                            @foreach($states as $st)
                                <option value="{{ $st->code }}">{{ $st->name }} ({{ $st->code }})</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" wire:model.live.debounce.300ms="taxState" placeholder="Enter State/Region..."
                               class="w-full px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 shadow-sm">
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Export Format</label>
                    <select wire:model="taxExportFormat" class="w-full px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 shadow-sm">
                        <option value="csv">CSV (.csv)</option>
                        <option value="xlsx">Excel (.xlsx)</option>
                    </select>
                </div>
            </div>

            <!-- Tax Totals Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="p-5 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Matching Orders</span>
                    <span class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 mt-1 block">{{ number_format($taxOrders->count()) }}</span>
                </div>

                <div class="p-5 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Taxable Sales Subtotal</span>
                    <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400 mt-1 block">${{ number_format($taxableSalesTotal, 2) }}</span>
                </div>

                <div class="p-5 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Tax / VAT Collected</span>
                    <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1 block">${{ number_format($taxCollectedTotal, 2) }}</span>
                </div>
            </div>

            <!-- Tax Orders Breakdown Table -->
            <div class="overflow-x-auto border border-slate-100 dark:border-slate-700 rounded-2xl">
                <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-4 py-3">Order Invoice</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Country / State</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                            <th class="px-4 py-3 text-right">Tax Charged</th>
                            <th class="px-4 py-3 text-right">Order Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 bg-white dark:bg-slate-800">
                        @forelse($taxOrders as $ord)
                            @php
                                $u = $ord->user;
                                $countryStr = $u ? ($u->shipping_countrycode ?: $u->shipping_country ?: '-') : '-';
                                $stateStr   = $u ? ($u->shipping_state ?: '-') : '-';
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-100">
                                    <a href="{{ route('admin.ecommerce.order-details', $ord->id) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $ord->order_invoice_no }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-slate-500">{{ $ord->order_date ? $ord->order_date->format('Y-m-d H:i') : '-' }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $u ? $u->name : 'Guest' }}</td>
                                <td class="px-4 py-3"><span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700 rounded text-[11px] font-bold">{{ $countryStr }} / {{ $stateStr }}</span></td>
                                <td class="px-4 py-3 text-right font-medium">${{ number_format($ord->order_subtotal, 2) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($ord->order_taxes, 2) }}</td>
                                <td class="px-4 py-3 text-right font-extrabold">${{ number_format($ord->order_total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">No orders found matching selected date range and tax location filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- STANDALONE REPORT 8: PRODUCTS EXPORT REPORT -->
    @if($activeTab === 'product_export')
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-8 shadow-sm space-y-6">
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Products Catalog Export</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Export your full products database into the exact format used by the Product Bulk Import tool.
            </p>
        </div>

        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200/80 dark:border-slate-700 space-y-4">
            <div class="flex items-center gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Export Format</label>
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-700 dark:text-slate-300">
                            <input type="radio" wire:model="productExportFormat" value="csv" class="text-indigo-600 focus:ring-indigo-500">
                            CSV (.csv)
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-700 dark:text-slate-300">
                            <input type="radio" wire:model="productExportFormat" value="xlsx" class="text-indigo-600 focus:ring-indigo-500">
                            Excel (.xlsx)
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-200/60 dark:border-slate-700">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block mb-2">Header Structure (Matches Bulk Product Import Schema)</span>
                <div class="flex flex-wrap gap-1.5 text-[11px] text-slate-600 dark:text-slate-400">
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Title</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Short Description</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Long Description</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Category</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Brand</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Public Price</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Wholesale Price</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Variant SKU</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Variant Name</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Variant Attributes</span>
                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono">Stock Quantity</span>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" wire:click="exportProducts" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-2xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Full Products Catalog ({{ strtoupper($productExportFormat) }})
            </button>
        </div>

        <!-- Amazon Product Export Card -->
        <div class="mt-8 p-6 bg-amber-50/50 dark:bg-amber-950/20 rounded-2xl border border-amber-200/80 dark:border-amber-900/50 space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h4 class="text-base font-bold text-amber-900 dark:text-amber-200 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/></svg>
                        Amazon Marketplace Feed Export
                    </h4>
                    <p class="text-xs text-amber-700 dark:text-amber-300/80 mt-1">
                        Export all product variants enabled for Amazon (<code class="font-mono">amazon_product = true</code>) with Amazon ASIN, price override, item type, condition, and bullet points.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 bg-white dark:bg-slate-900 px-3 py-1.5 rounded-xl border border-amber-200 text-xs font-semibold">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="amazonExportFormat" value="csv" class="text-amber-600 focus:ring-amber-500">
                            CSV
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="amazonExportFormat" value="xlsx" class="text-amber-600 focus:ring-amber-500">
                            Excel
                        </label>
                    </div>

                    <button type="button" wire:click="exportAmazonProducts" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export Amazon Feed ({{ strtoupper($amazonExportFormat) }})
                    </button>
                </div>
            </div>
        </div>

        <!-- eBay Product Export Card -->
        <div class="mt-4 p-6 bg-blue-50/50 dark:bg-blue-950/20 rounded-2xl border border-blue-200/80 dark:border-blue-900/50 space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h4 class="text-base font-bold text-blue-900 dark:text-blue-200 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        eBay Marketplace Feed Export
                    </h4>
                    <p class="text-xs text-blue-700 dark:text-blue-300/80 mt-1">
                        Export all product variants enabled for eBay (<code class="font-mono">ebay_product = true</code>) with eBay category ID, listing type, options/traits, shipping profile, and return policy.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 bg-white dark:bg-slate-900 px-3 py-1.5 rounded-xl border border-blue-200 text-xs font-semibold">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="ebayExportFormat" value="csv" class="text-blue-600 focus:ring-blue-500">
                            CSV
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="ebayExportFormat" value="xlsx" class="text-blue-600 focus:ring-blue-500">
                            Excel
                        </label>
                    </div>

                    <button type="button" wire:click="exportEbayProducts" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export eBay Feed ({{ strtoupper($ebayExportFormat) }})
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- STANDALONE REPORT 9: MULTI-WAREHOUSE INVENTORY EXPORT -->
    @if($activeTab === 'inventory_export')
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-8 shadow-sm space-y-6">
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Multi-Warehouse Inventory Export Report</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Export comprehensive inventory records combining shelf stock, primary warehouse facilities, and child warehouse stock levels per location.
            </p>
        </div>

        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200/80 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Export Multi-Warehouse Inventory File</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Includes SKU, Product Title, Shelf Stock, Primary Facility, Main Warehouse Stock, Reserved Stock, Child Locations Breakdown, and Dynamic Total Available Stock.</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 bg-white dark:bg-slate-900 px-3 py-1.5 rounded-xl border border-emerald-200 text-xs font-semibold">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="inventoryExportFormat" value="csv" class="text-emerald-600 focus:ring-emerald-500">
                            CSV
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="inventoryExportFormat" value="xlsx" class="text-emerald-600 focus:ring-emerald-500">
                            Excel
                        </label>
                    </div>

                    <button type="button" wire:click="exportMultiWarehouseInventory" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export Multi-Warehouse Inventory ({{ strtoupper($inventoryExportFormat) }})
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- STANDALONE REPORT 10: RETAIL & WHOLESALE CUSTOMERS EXPORT -->
    @if($activeTab === 'customer_export')
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-8 shadow-sm space-y-6">
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Retail &amp; Wholesale Customers Export Report</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Export registered Customer (role_id = 1) and Wholesale (role_id = 2) user accounts including their mailing list opt-in status. Administrators and Order Processors are excluded.
            </p>
        </div>

        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200/80 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Export Registered Customers File</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Includes User ID, Full Name, Email, Account Role, Opt-in Status (1 or 0), and Registration Date.</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 bg-white dark:bg-slate-900 px-3 py-1.5 rounded-xl border border-indigo-200 text-xs font-semibold">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="customerExportFormat" value="csv" class="text-indigo-600 focus:ring-indigo-500">
                            CSV
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="customerExportFormat" value="xlsx" class="text-indigo-600 focus:ring-indigo-500">
                            Excel
                        </label>
                    </div>

                    <button type="button" wire:click="exportCustomers" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export Customers ({{ strtoupper($customerExportFormat) }})
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- STANDALONE REPORT 11: OPT-IN SUBSCRIBERS EXPORT -->
    @if($activeTab === 'optin_export')
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-8 shadow-sm space-y-6">
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Opt-In Subscribers Export Report</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Export all users who have explicitly opted in (opt_in = 1). Automatically splits full names into first and last name fields for easy import into email platforms.
            </p>
        </div>

        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200/80 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Export Opt-In Subscribers List</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Includes Full Name, First Name, Last Name, Email Address, and Opt-in Status (1 for all subscribers in this list).</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 bg-white dark:bg-slate-900 px-3 py-1.5 rounded-xl border border-purple-200 text-xs font-semibold">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="optInExportFormat" value="csv" class="text-purple-600 focus:ring-purple-500">
                            CSV
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="optInExportFormat" value="xlsx" class="text-purple-600 focus:ring-purple-500">
                            Excel
                        </label>
                    </div>

                    <button type="button" wire:click="exportOptInSubscribers" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export Opt-In List ({{ strtoupper($optInExportFormat) }})
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
