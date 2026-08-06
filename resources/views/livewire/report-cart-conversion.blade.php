<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm flex flex-col space-y-6">
    <!-- Header with Title and Date Filters -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider font-sans">Cart Conversion Funnel</h4>
            <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5 font-sans">Track cart creation, abandonment types, and checkouts.</p>
        </div>
        
        <!-- Date Filter Buttons ("Bubbles") -->
        <div class="flex flex-wrap items-center gap-1 bg-slate-50 dark:bg-slate-900/80 border border-slate-200/60 dark:border-slate-700 p-1 rounded-xl">
            @foreach(['30', '60', '90', '120', 'YTD', 'custom'] as $range)
                <button type="button" 
                        wire:click="setRange('{{ $range }}')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition duration-150 {{ $dateRange === $range ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm border border-slate-200/40 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    {{ $range === 'custom' ? 'Custom' : ($range === 'YTD' ? 'YTD' : $range . 'd') }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Custom Date Range Pickers -->
    @if($dateRange === 'custom')
        <div class="grid grid-cols-2 gap-3 bg-slate-50/50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700 rounded-2xl p-3">
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Start Date</label>
                <input type="date" wire:model.live="startDate" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">End Date</label>
                <input type="date" wire:model.live="endDate" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
            </div>
        </div>
    @endif

    <!-- Content / Funnel Visualization -->
    <div class="space-y-5">
        <!-- Funnel Step 1: Sessions -->
        <div class="relative">
            <div class="bg-indigo-50/40 dark:bg-indigo-950/40 border border-indigo-100/50 dark:border-indigo-900/50 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Step 1: Initiated Sessions</span>
                    <span class="text-lg font-extrabold text-slate-900 dark:text-slate-100 block mt-0.5">{{ number_format($overallCartSessions) }} Carts</span>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold text-slate-400 block">Baseline</span>
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">100%</span>
                </div>
            </div>
            <div class="h-4 w-0.5 bg-indigo-200 dark:bg-indigo-800 mx-auto"></div>
        </div>

        <!-- Funnel Step 2: Abandoned -->
        <div class="relative">
            <div class="bg-slate-50 dark:bg-slate-900/80 border border-slate-200/60 dark:border-slate-700 rounded-2xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Step 2: Cart Abandonment</span>
                        <span class="text-lg font-extrabold text-slate-800 dark:text-slate-100 block mt-0.5">{{ number_format($abandonedTotal) }} Carts</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold text-slate-400 block">Drop-off Rate</span>
                        <span class="text-xs font-bold text-rose-500 dark:text-rose-400">{{ $abandonedRate }}%</span>
                    </div>
                </div>
                
                <!-- Abandonment breakdown -->
                <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-200/40 dark:border-slate-700/80 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold block mb-0.5">Guest Abandoned</span>
                        <span class="font-extrabold text-slate-700 dark:text-slate-200">{{ number_format($abandonedGuest) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block mb-0.5">Registered Abandoned</span>
                        <span class="font-extrabold text-slate-700 dark:text-slate-200">{{ number_format($abandonedRegistered) }}</span>
                    </div>
                </div>
            </div>
            <div class="h-4 w-0.5 bg-indigo-200 dark:bg-indigo-800 mx-auto"></div>
        </div>

        <!-- Funnel Step 3: Completed Orders -->
        <div class="relative">
            <div class="bg-emerald-50/40 dark:bg-emerald-950/40 border border-emerald-100/50 dark:border-emerald-900/50 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Step 3: Completed Checkout</span>
                    <span class="text-lg font-extrabold text-emerald-700 dark:text-emerald-300 block mt-0.5">{{ number_format($completedCarts) }} Orders</span>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold text-slate-400 block">Conversion Rate</span>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $conversionRate }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>
