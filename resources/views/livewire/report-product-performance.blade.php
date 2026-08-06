<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm flex flex-col space-y-6">
    <!-- Header with Title and Date Filters -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider font-sans">Product Sales Performance</h4>
            <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5 font-sans">View your top-performing and lowest-selling items.</p>
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

    <!-- Performance Selector Tabs -->
    <div class="flex border-b border-slate-100 dark:border-slate-700 p-0.5 bg-slate-50/80 dark:bg-slate-900/80 rounded-2xl">
        <button type="button" 
                wire:click="setPerformanceMode('highest')"
                class="flex-1 py-2 text-xs font-bold rounded-xl transition duration-150 flex items-center justify-center gap-1.5 {{ $performanceMode === 'highest' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm border border-slate-200/40 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            Top Sellers
        </button>
        <button type="button" 
                wire:click="setPerformanceMode('lowest')"
                class="flex-1 py-2 text-xs font-bold rounded-xl transition duration-150 flex items-center justify-center gap-1.5 {{ $performanceMode === 'lowest' ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-sm border border-slate-200/40 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Bottom Sellers
        </button>
    </div>

    <!-- Grid Table -->
    <div class="overflow-x-auto">
        @if($products->isNotEmpty())
            <table class="w-full text-xs text-left text-slate-500 dark:text-slate-400 divide-y divide-slate-100 dark:divide-slate-700/60">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th scope="col" class="py-3">Product Name</th>
                        <th scope="col" class="py-3 text-center">Units Sold</th>
                        <th scope="col" class="py-3 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-sans">
                    @foreach($products as $prod)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-700/40 transition duration-100">
                            <td class="py-3.5 font-bold text-slate-800 dark:text-slate-100 pr-4 max-w-[200px] truncate" title="{{ $prod->item_name }}">
                                @if($prod->product_id)
                                    <a href="{{ route('admin.ecommerce.product-edit', $prod->product_id) }}" class="hover:underline text-slate-800 dark:text-slate-100">{{ $prod->item_name }}</a>
                                @else
                                    {{ $prod->item_name }}
                                @endif
                            </td>
                            <td class="py-3.5 text-center font-extrabold text-slate-700 dark:text-slate-300">{{ number_format($prod->total_qty) }}</td>
                            <td class="py-3.5 text-right font-extrabold text-indigo-600 dark:text-indigo-400">${{ number_format($prod->total_revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-xs font-semibold text-slate-400">No product sales performance records found for this period</p>
            </div>
        @endif
    </div>
</div>
