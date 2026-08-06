<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm flex flex-col space-y-6">
    <!-- Header with Title and Date Filters -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider font-sans">Customer Spend Analysis</h4>
            <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5 font-sans">Analyze the highest and lowest spending customers.</p>
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

    <!-- Spend Selector Tabs -->
    <div class="flex border-b border-slate-100 dark:border-slate-700 p-0.5 bg-slate-50/80 dark:bg-slate-900/80 rounded-2xl">
        <button type="button" 
                wire:click="setViewMode('highest')"
                class="flex-1 py-2 text-xs font-bold rounded-xl transition duration-150 flex items-center justify-center gap-1.5 {{ $viewMode === 'highest' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm border border-slate-200/40 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            Highest Spenders
        </button>
        <button type="button" 
                wire:click="setViewMode('lowest')"
                class="flex-1 py-2 text-xs font-bold rounded-xl transition duration-150 flex items-center justify-center gap-1.5 {{ $viewMode === 'lowest' ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-sm border border-slate-200/40 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg>
            Lowest Spenders
        </button>
    </div>

    <!-- Content / Grid List -->
    <div class="space-y-4">
        @if($customers->isNotEmpty())
            @php
                $maxVal = collect($customers)->max('total_spend') ?: 1;
            @endphp
            <div class="space-y-3.5">
                @foreach($customers as $customer)
                    @php
                        $widthPercent = round(($customer->total_spend / $maxVal) * 100);
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <div>
                                <a href="{{ route('admin.users.show', $customer->id) }}" wire:navigate class="font-extrabold text-slate-800 dark:text-slate-200 hover:underline">{{ $customer->name }}</a>
                                <span class="text-[10px] text-slate-400 block">{{ $customer->email }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-slate-900 dark:text-slate-100">${{ number_format($customer->total_spend, 2) }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $customer->orders_count }} orders</span>
                            </div>
                        </div>
                        <div class="h-2 w-full bg-slate-50 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-100 dark:border-slate-700 flex">
                            <div style="width: {{ $widthPercent }}%" 
                                 class="h-full rounded-full transition-all duration-500 {{ $viewMode === 'highest' ? 'bg-indigo-500' : 'bg-rose-400' }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-xs font-semibold text-slate-400">No customer spend records found for this period</p>
            </div>
        @endif
    </div>
</div>
