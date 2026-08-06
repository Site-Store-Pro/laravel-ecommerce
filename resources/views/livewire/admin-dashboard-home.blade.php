<div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 font-sans"
     x-data="{
        widgets: ['kpi_cards', 'recent_orders', 'order_activity', 'completed_abandoned', 'cart_conversion', 'customer_spend', 'product_performance'],
        draggedKey: null,
        overKey: null,
        init() {
            const saved = localStorage.getItem('admin_dashboard_widget_order');
            if (saved) {
                try {
                    const parsed = JSON.parse(saved);
                    if (Array.isArray(parsed) && parsed.length) {
                        const validKeys = ['kpi_cards', 'recent_orders', 'order_activity', 'completed_abandoned', 'cart_conversion', 'customer_spend', 'product_performance'];
                        const filtered = parsed.filter(w => validKeys.includes(w));
                        validKeys.forEach(w => { if (!filtered.includes(w)) filtered.push(w); });
                        this.widgets = filtered;
                    }
                } catch(e) {}
            }
        },
        saveOrder() {
            localStorage.setItem('admin_dashboard_widget_order', JSON.stringify(this.widgets));
        },
        resetOrder() {
            this.widgets = ['kpi_cards', 'recent_orders', 'order_activity', 'completed_abandoned', 'cart_conversion', 'customer_spend', 'product_performance'];
            localStorage.removeItem('admin_dashboard_widget_order');
        },
        dragStart(e, key) {
            this.draggedKey = key;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', key);
        },
        dragOver(e, key) {
            e.preventDefault();
            this.overKey = key;
        },
        dragLeave(e, key) {
            if (this.overKey === key) this.overKey = null;
        },
        drop(e, targetKey) {
            e.preventDefault();
            if (this.draggedKey && this.draggedKey !== targetKey) {
                const fromIdx = this.widgets.indexOf(this.draggedKey);
                const toIdx = this.widgets.indexOf(targetKey);
                if (fromIdx !== -1 && toIdx !== -1) {
                    const item = this.widgets.splice(fromIdx, 1)[0];
                    this.widgets.splice(toIdx, 0, item);
                    this.saveOrder();
                }
            }
            this.draggedKey = null;
            this.overKey = null;
        },
        dragEnd() {
            this.draggedKey = null;
            this.overKey = null;
        }
     }">
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

    <!-- Dashboard Toolbar & Drag Controls -->
    <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-200 dark:border-slate-700 pb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 flex items-center gap-2.5 tracking-tight">
                <span>Admin Dashboard</span>
                <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-bold flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    Multi-Column Draggable Grid Active
                </span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Drag any section using its top-right handle <span class="font-bold text-indigo-600 dark:text-indigo-400 font-mono">:::</span> into any position or column across the grid.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button" @click="resetOrder()" class="px-3.5 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-2xl shadow-sm transition flex items-center gap-1.5 cursor-pointer" title="Reset to default multi-column layout">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset Layout
            </button>

            <a href="{{ url('/') }}" target="_blank" class="btn-theme-primary !text-xs !py-2 !px-4 inline-flex items-center gap-1.5 whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span class="whitespace-nowrap shrink-0">View Public Site</span>
            </a>
            <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-2xl shadow-sm transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Manage Orders
            </a>
        </div>
    </div>

    <!-- MULTI-COLUMN DRAGGABLE GRID CONTAINER -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <!-- 1. KPI SUMMARY GRID (Full Row - 3 Cols) -->
        <div draggable="true"
             @dragstart="dragStart($event, 'kpi_cards')"
             @dragover="dragOver($event, 'kpi_cards')"
             @dragleave="dragLeave($event, 'kpi_cards')"
             @drop="drop($event, 'kpi_cards')"
             @dragend="dragEnd()"
             :style="{ order: widgets.indexOf('kpi_cards') }"
             :class="{
                'opacity-40 border-2 border-dashed border-indigo-400 rounded-3xl p-1': draggedKey === 'kpi_cards',
                'ring-2 ring-indigo-500 ring-offset-2 rounded-3xl': overKey === 'kpi_cards' && draggedKey !== 'kpi_cards'
             }"
             class="lg:col-span-3 transition-all duration-200 relative group/drag cursor-move">
            
            <div class="absolute top-2 right-2 z-20 opacity-40 group-hover/drag:opacity-100 transition">
                <span class="cursor-grab active:cursor-grabbing px-2.5 py-1 rounded-xl bg-slate-800 text-white text-[10px] font-extrabold uppercase font-mono tracking-wider shadow-md flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    Drag Section
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Sales Card -->
                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Sales Revenue</span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-slate-100">${{ number_format($totalSales, 2) }}</span>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/40 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Orders Processed</span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-slate-100">{{ number_format($totalOrdersCount) }}</span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/40 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                </div>

                <!-- Pending Orders Card -->
                <a href="{{ route('admin.ecommerce.pending-orders') }}" wire:navigate class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm flex items-center justify-between hover:border-slate-200 dark:hover:border-slate-600 hover:shadow-md transition group">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block group-hover:text-slate-500 transition">Pending Orders</span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-slate-100">{{ number_format($pendingOrdersCount) }}</span>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/40 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-105 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </a>

                <!-- Customers Card -->
                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Active Customers</span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-slate-100">{{ number_format($customersCount) }}</span>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/40 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. RECENT ORDERS TABLE (Full Row - 3 Cols) -->
        <div draggable="true"
             @dragstart="dragStart($event, 'recent_orders')"
             @dragover="dragOver($event, 'recent_orders')"
             @dragleave="dragLeave($event, 'recent_orders')"
             @drop="drop($event, 'recent_orders')"
             @dragend="dragEnd()"
             :style="{ order: widgets.indexOf('recent_orders') }"
             :class="{
                'opacity-40 border-2 border-dashed border-indigo-400 rounded-3xl p-1': draggedKey === 'recent_orders',
                'ring-2 ring-indigo-500 ring-offset-2 rounded-3xl': overKey === 'recent_orders' && draggedKey !== 'recent_orders'
             }"
             class="lg:col-span-3 transition-all duration-200 relative group/drag cursor-move bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm">
            
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4 mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider font-sans">Recent Orders Activity</h3>
                    <p class="text-xs text-slate-400 mt-0.5 font-sans">Overview of the last 10 orders processed on the platform.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        View All Orders
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <span class="cursor-grab active:cursor-grabbing px-2 py-1 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-indigo-600 hover:text-white text-slate-500 dark:text-slate-300 text-[10px] font-extrabold uppercase font-mono tracking-wider transition" title="Drag Section">
                        <svg class="w-3.5 h-3.5 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                        Drag
                    </span>
                </div>
            </div>

            @if($recentOrders->isEmpty())
                <p class="text-xs text-slate-400 text-center py-6">No orders found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-slate-500 dark:text-slate-400 divide-y divide-slate-100 dark:divide-slate-700">
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
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-sans">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-700/40 transition">
                                    <td class="py-3.5 px-3">
                                        <span class="font-extrabold text-slate-800 dark:text-slate-200 block">{{ $order->order_invoice_no }}</span>
                                        @if($order->order_external_id)
                                            <span class="text-[10px] text-slate-400 font-medium block">Ext ID: {{ $order->order_external_id }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $order->user ? $order->user->name : 'Guest' }}
                                    </td>
                                    <td class="py-3.5 px-3 text-center font-extrabold text-slate-600 dark:text-slate-400">
                                        {{ $order->details_count }}
                                    </td>
                                    <td class="py-3.5 px-3 text-right font-extrabold text-slate-900 dark:text-slate-100">
                                        ${{ number_format($order->order_total, 2) }}
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        @if($order->order_status == 7)
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-700 rounded-full border border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">Completed</span>
                                        @elseif($order->order_status == 1)
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-50 text-amber-700 rounded-full border border-amber-100 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800">Pending</span>
                                        @elseif($order->order_status == 2)
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 rounded-full border border-blue-100 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">Shipped</span>
                                        @elseif($order->order_status == 3)
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-red-50 text-red-700 rounded-full border border-red-100 dark:bg-red-950/40 dark:text-red-300 dark:border-red-800">Refunded</span>
                                        @elseif($order->order_status == 4)
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded-full border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600">Canceled</span>
                                        @elseif($order->order_status == 5)
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-violet-50 text-violet-700 rounded-full border border-violet-100 dark:bg-violet-950/40 dark:text-violet-300 dark:border-violet-800">Partially Shipped</span>
                                        @elseif($order->order_status == 6)
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-orange-50 text-orange-700 rounded-full border border-orange-100 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-800">Back Ordered</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-50 text-slate-500 rounded-full border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700">Awaiting Payment</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 text-right">
                                        <a href="{{ route('admin.ecommerce.order-details', $order->id) }}" wire:navigate class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-900/40 hover:bg-indigo-100 dark:hover:bg-indigo-800/60 text-indigo-600 dark:text-indigo-300 text-xs font-bold rounded-lg transition duration-150">Manage</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- 3. ORDER ACTIVITY CHART (Spans 2 Columns side-by-side with Completed vs Abandoned) -->
        <div draggable="true"
             @dragstart="dragStart($event, 'order_activity')"
             @dragover="dragOver($event, 'order_activity')"
             @dragleave="dragLeave($event, 'order_activity')"
             @drop="drop($event, 'order_activity')"
             @dragend="dragEnd()"
             :style="{ order: widgets.indexOf('order_activity') }"
             :class="{
                'opacity-40 border-2 border-dashed border-indigo-400 rounded-3xl p-1': draggedKey === 'order_activity',
                'ring-2 ring-indigo-500 ring-offset-2 rounded-3xl': overKey === 'order_activity' && draggedKey !== 'order_activity'
             }"
             class="lg:col-span-2 transition-all duration-200 relative group/drag cursor-move">
            
            <div class="absolute top-4 right-4 z-20 opacity-40 group-hover/drag:opacity-100 transition">
                <span class="cursor-grab active:cursor-grabbing px-2 py-1 rounded-lg bg-slate-800 text-white text-[10px] font-extrabold uppercase font-mono tracking-wider shadow-md flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    Drag Section
                </span>
            </div>

            @livewire('report-order-activity')
        </div>

        <!-- 4. COMPLETED VS ABANDONED CARTS (Spans 1 Column) -->
        <div draggable="true"
             @dragstart="dragStart($event, 'completed_abandoned')"
             @dragover="dragOver($event, 'completed_abandoned')"
             @dragleave="dragLeave($event, 'completed_abandoned')"
             @drop="drop($event, 'completed_abandoned')"
             @dragend="dragEnd()"
             :style="{ order: widgets.indexOf('completed_abandoned') }"
             :class="{
                'opacity-40 border-2 border-dashed border-indigo-400 rounded-3xl p-1': draggedKey === 'completed_abandoned',
                'ring-2 ring-indigo-500 ring-offset-2 rounded-3xl': overKey === 'completed_abandoned' && draggedKey !== 'completed_abandoned'
             }"
             class="lg:col-span-1 transition-all duration-200 relative group/drag cursor-move">
            
            <div class="absolute top-4 right-4 z-20 opacity-40 group-hover/drag:opacity-100 transition">
                <span class="cursor-grab active:cursor-grabbing px-2 py-1 rounded-lg bg-slate-800 text-white text-[10px] font-extrabold uppercase font-mono tracking-wider shadow-md flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    Drag Section
                </span>
            </div>

            @livewire('report-completed-vs-abandoned')
        </div>

        <!-- 5. CART FUNNEL CONVERSION (Spans 1 Column in 3-col bottom row) -->
        <div draggable="true"
             @dragstart="dragStart($event, 'cart_conversion')"
             @dragover="dragOver($event, 'cart_conversion')"
             @dragleave="dragLeave($event, 'cart_conversion')"
             @drop="drop($event, 'cart_conversion')"
             @dragend="dragEnd()"
             :style="{ order: widgets.indexOf('cart_conversion') }"
             :class="{
                'opacity-40 border-2 border-dashed border-indigo-400 rounded-3xl p-1': draggedKey === 'cart_conversion',
                'ring-2 ring-indigo-500 ring-offset-2 rounded-3xl': overKey === 'cart_conversion' && draggedKey !== 'cart_conversion'
             }"
             class="lg:col-span-1 transition-all duration-200 relative group/drag cursor-move">
            
            <div class="absolute top-4 right-4 z-20 opacity-40 group-hover/drag:opacity-100 transition">
                <span class="cursor-grab active:cursor-grabbing px-2 py-1 rounded-lg bg-slate-800 text-white text-[10px] font-extrabold uppercase font-mono tracking-wider shadow-md flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    Drag Section
                </span>
            </div>

            @livewire('report-cart-conversion')
        </div>

        <!-- 6. CUSTOMER SPEND DISTRIBUTION (Spans 1 Column in 3-col bottom row) -->
        <div draggable="true"
             @dragstart="dragStart($event, 'customer_spend')"
             @dragover="dragOver($event, 'customer_spend')"
             @dragleave="dragLeave($event, 'customer_spend')"
             @drop="drop($event, 'customer_spend')"
             @dragend="dragEnd()"
             :style="{ order: widgets.indexOf('customer_spend') }"
             :class="{
                'opacity-40 border-2 border-dashed border-indigo-400 rounded-3xl p-1': draggedKey === 'customer_spend',
                'ring-2 ring-indigo-500 ring-offset-2 rounded-3xl': overKey === 'customer_spend' && draggedKey !== 'customer_spend'
             }"
             class="lg:col-span-1 transition-all duration-200 relative group/drag cursor-move">
            
            <div class="absolute top-4 right-4 z-20 opacity-40 group-hover/drag:opacity-100 transition">
                <span class="cursor-grab active:cursor-grabbing px-2 py-1 rounded-lg bg-slate-800 text-white text-[10px] font-extrabold uppercase font-mono tracking-wider shadow-md flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    Drag Section
                </span>
            </div>

            @livewire('report-customer-spend')
        </div>

        <!-- 7. PRODUCT PERFORMANCE (Spans 1 Column in 3-col bottom row) -->
        <div draggable="true"
             @dragstart="dragStart($event, 'product_performance')"
             @dragover="dragOver($event, 'product_performance')"
             @dragleave="dragLeave($event, 'product_performance')"
             @drop="drop($event, 'product_performance')"
             @dragend="dragEnd()"
             :style="{ order: widgets.indexOf('product_performance') }"
             :class="{
                'opacity-40 border-2 border-dashed border-indigo-400 rounded-3xl p-1': draggedKey === 'product_performance',
                'ring-2 ring-indigo-500 ring-offset-2 rounded-3xl': overKey === 'product_performance' && draggedKey !== 'product_performance'
             }"
             class="lg:col-span-1 transition-all duration-200 relative group/drag cursor-move">
            
            <div class="absolute top-4 right-4 z-20 opacity-40 group-hover/drag:opacity-100 transition">
                <span class="cursor-grab active:cursor-grabbing px-2 py-1 rounded-lg bg-slate-800 text-white text-[10px] font-extrabold uppercase font-mono tracking-wider shadow-md flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    Drag Section
                </span>
            </div>

            @livewire('report-product-performance')
        </div>
    </div>
</div>
