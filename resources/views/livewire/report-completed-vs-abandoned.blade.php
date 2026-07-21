<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col space-y-6">
    <!-- Header with Title and Date Filters -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-sans">Orders vs Abandoned Carts</h4>
            <p class="text-xs text-slate-400 mt-0.5 font-sans">Comparison of completed checkouts vs unfinished carts.</p>
        </div>
        
        <!-- Date Filter Buttons ("Bubbles") -->
        <div class="flex flex-wrap items-center gap-1 bg-slate-50 border border-slate-200/60 p-1 rounded-xl">
            @foreach(['30', '60', '90', '120', 'YTD', 'custom'] as $range)
                <button type="button" 
                        wire:click="setRange('{{ $range }}')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition duration-150 {{ $dateRange === $range ? 'bg-white text-indigo-600 shadow-sm border border-slate-200/40' : 'text-slate-500 hover:text-slate-800' }}">
                    {{ $range === 'custom' ? 'Custom' : ($range === 'YTD' ? 'YTD' : $range . 'd') }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Custom Date Range Pickers -->
    @if($dateRange === 'custom')
        <div class="grid grid-cols-2 gap-3 bg-slate-50/50 border border-slate-100 rounded-2xl p-3">
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Start Date</label>
                <input type="date" wire:model.live="startDate" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">End Date</label>
                <input type="date" wire:model.live="endDate" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
            </div>
        </div>
    @endif

    <!-- Content / Visualization -->
    <div class="space-y-6">
        <!-- Main Stats Row -->
        <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-5">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Completed</span>
                <span class="text-2xl font-extrabold text-indigo-600">{{ number_format($completedCount) }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Abandoned</span>
                <span class="text-2xl font-extrabold text-slate-400">{{ number_format($abandonedCount) }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Conversion</span>
                <span class="text-2xl font-extrabold text-emerald-500">{{ $conversionRate }}%</span>
            </div>
        </div>

        <!-- Split Progress Bar -->
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs font-bold text-slate-500">
                <span>Completed Orders ({{ $totalCarts > 0 ? round(($completedCount / $totalCarts) * 100) : 0 }}%)</span>
                <span>Abandoned ({{ $totalCarts > 0 ? round(($abandonedCount / $totalCarts) * 100) : 0 }}%)</span>
            </div>
            <div class="h-4 w-full bg-slate-100 rounded-full overflow-hidden flex shadow-inner">
                @if($totalCarts > 0)
                    <div style="width: {{ ($completedCount / $totalCarts) * 100 }}%" class="bg-gradient-to-r from-indigo-500 to-indigo-600 transition-all duration-500"></div>
                    <div style="width: {{ ($abandonedCount / $totalCarts) * 100 }}%" class="bg-slate-300 transition-all duration-500"></div>
                @else
                    <div class="w-full bg-slate-200"></div>
                @endif
            </div>
        </div>

        <!-- Extra details list -->
        <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 space-y-2.5">
            <div class="flex items-center justify-between text-xs font-semibold">
                <span class="text-slate-500">Total Cart Sessions Initiated:</span>
                <span class="font-extrabold text-slate-800">{{ number_format($totalCarts) }}</span>
            </div>
            <div class="flex items-center justify-between text-xs font-semibold border-t border-slate-100 pt-2.5">
                <span class="text-slate-500">Successful checkout conversion:</span>
                <span class="font-extrabold text-emerald-500">{{ number_format($completedCount) }} orders</span>
            </div>
        </div>
    </div>
</div>
