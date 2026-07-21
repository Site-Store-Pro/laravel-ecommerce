<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col space-y-6">
    <!-- Header with Title and Date Filters -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-sans">Order Activity &amp; Revenue Trends</h4>
            <p class="text-xs text-slate-400 mt-0.5 font-sans">Track daily sales volume and revenue performance.</p>
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

    <!-- Content / Chart -->
    <div class="space-y-6">
        <!-- Stats summary row -->
        <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-5">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Total Revenue</span>
                <span class="text-xl font-extrabold text-indigo-600">${{ number_format($totalRevenue, 2) }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Total Orders</span>
                <span class="text-xl font-extrabold text-slate-800">{{ number_format($totalOrders) }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Avg Order Value</span>
                <span class="text-xl font-extrabold text-emerald-500">${{ number_format($avgOrderValue, 2) }}</span>
            </div>
        </div>

        <!-- SVG Line Chart representing the activity -->
        <div class="relative w-full bg-slate-50/50 border border-slate-100 rounded-2xl p-4 overflow-hidden">
            @php
                $pointsCount = count($chartData);
                $maxRevenue = collect($chartData)->max('revenue') ?: 1;
                $maxOrders = collect($chartData)->max('count') ?: 1;
                
                $width = 500;
                $height = 120;
                $paddingTop = 10;
                
                // Construct points for SVG path
                $revenuePoints = [];
                $ordersPoints = [];
                
                foreach ($chartData as $index => $data) {
                    $x = $pointsCount > 1 ? ($index / ($pointsCount - 1)) * $width : 0;
                    
                    // scale Y for revenue (inverse since 0 is top)
                    $yRev = $height - (($data['revenue'] / $maxRevenue) * ($height - $paddingTop));
                    $revenuePoints[] = "$x,$yRev";
                    
                    // scale Y for order count
                    $yOrd = $height - (($data['count'] / $maxOrders) * ($height - $paddingTop));
                    $ordersPoints[] = "$x,$yOrd";
                }
                
                $revenuePath = count($revenuePoints) > 0 ? "M " . implode(" L ", $revenuePoints) : "";
                $ordersPath = count($ordersPoints) > 0 ? "M " . implode(" L ", $ordersPoints) : "";
                
                // Filled area under revenue path
                $revenueAreaPath = $revenuePath ? $revenuePath . " L $width,$height L 0,$height Z" : "";
            @endphp

            @if($totalRevenue > 0)
                <div class="relative h-32 w-full">
                    <svg viewBox="0 0 {{ $width }} {{ $height }}" class="w-full h-full" preserveAspectRatio="none">
                        <!-- Gradients -->
                        <defs>
                            <linearGradient id="revGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#4f46e5" stop-opacity="0.2"/>
                                <stop offset="100%" stop-color="#4f46e5" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>

                        <!-- Gridlines (Horizontal) -->
                        <line x1="0" y1="{{ $height / 2 }}" x2="{{ $width }}" y2="{{ $height / 2 }}" stroke="#e2e8f0" stroke-width="0.5" stroke-dasharray="4"/>
                        <line x1="0" y1="{{ $height - 1 }}" x2="{{ $width }}" y2="{{ $height - 1 }}" stroke="#cbd5e1" stroke-width="1"/>

                        <!-- Revenue Area and Line -->
                        @if($revenueAreaPath)
                            <path d="{{ $revenueAreaPath }}" fill="url(#revGrad)"/>
                            <path d="{{ $revenuePath }}" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        @endif

                        <!-- Orders Line -->
                        @if($ordersPath)
                            <path d="{{ $ordersPath }}" fill="none" stroke="#10b981" stroke-width="1.5" stroke-dasharray="2" stroke-linecap="round" stroke-linejoin="round"/>
                        @endif
                    </svg>
                </div>
                
                <div class="flex items-center justify-between mt-3 text-[10px] font-bold text-slate-400">
                    <span>{{ $chartData[0]['label'] }}</span>
                    <span class="flex items-center gap-3">
                        <span class="flex items-center gap-1"><span class="h-1.5 w-3 rounded bg-indigo-600 block"></span> Revenue</span>
                        <span class="flex items-center gap-1"><span class="h-1.5 w-3 rounded bg-emerald-500 block"></span> Orders Count</span>
                    </span>
                    <span>{{ end($chartData)['label'] }}</span>
                </div>
            @else
                <div class="h-32 flex flex-col items-center justify-center text-center">
                    <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                    </svg>
                    <p class="text-xs font-semibold text-slate-400">No activity recorded for this period</p>
                </div>
            @endif
        </div>
    </div>
</div>
