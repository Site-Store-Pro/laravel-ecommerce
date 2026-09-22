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

                    <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.inventory') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 text-indigo-600 transition duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Inventory
                    </a>
                </div>
            </div>

            <!-- Inventory Content Area -->
            <div class="lg:col-span-9 space-y-6">
                <!-- Notifications -->
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold shadow-sm">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 bg-red-50 rounded-2xl border border-red-100 text-red-800 text-sm font-semibold space-y-1 shadow-sm">
                        @foreach($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Navigation Tabs Bar -->
                <div class="bg-white border border-slate-100 rounded-3xl p-2 shadow-sm">
                    <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                        <button type="button" wire:click="setTab('stock')" class="px-4 py-2.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition duration-150 inline-flex items-center gap-2 {{ $activeTab === 'stock' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            Stock Control
                        </button>

                        <button type="button" wire:click="setTab('csv_import')" class="px-4 py-2.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition duration-150 inline-flex items-center gap-2 {{ $activeTab === 'csv_import' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            CSV Stock & Cost Import
                        </button>

                        <button type="button" wire:click="setTab('ftp_import')" class="px-4 py-2.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition duration-150 inline-flex items-center gap-2 {{ $activeTab === 'ftp_import' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                            FTP / SFTP Sync
                        </button>

                        <button type="button" wire:click="setTab('marketplace')" class="px-4 py-2.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition duration-150 inline-flex items-center gap-2 {{ $activeTab === 'marketplace' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Marketplace (Amazon/eBay)
                        </button>

                        <button type="button" wire:click="setTab('reset')" class="px-4 py-2.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition duration-150 inline-flex items-center gap-2 {{ $activeTab === 'reset' ? 'bg-rose-600 text-white shadow-md' : 'text-rose-600 hover:bg-rose-50' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Bulk Tools & Reset
                        </button>
                    </div>
                </div>

                <!-- TAB 1: Stock Control & Quick Edit -->
                @if($activeTab === 'stock')
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-sans">Stock Control & Inventory Overview</h3>
                                <p class="text-xs text-slate-500 mt-1">Manage real-time variant stock levels, warehouse allocations, and cost/pricing visibility.</p>
                            </div>
                            <button type="button" wire:click="exportCsv" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md inline-flex items-center gap-1.5 cursor-pointer shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Export Inventory CSV
                            </button>
                        </div>

                        <!-- Live search bar -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search inventory by product title, SKU, variant..." class="pl-11 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 text-slate-700 placeholder-slate-400 rounded-2xl focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition duration-150 text-sm">
                        </div>

                        @if($inventory->isEmpty())
                            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-sm font-semibold text-slate-500">No variant inventory records found.</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your search criteria or import products via CSV.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                                <table class="w-full text-left text-sm text-slate-500">
                                    <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                                        <tr>
                                            <th class="px-4 py-3">Product Name</th>
                                            <th class="px-4 py-3">SKU</th>
                                            <th class="px-3 py-3 text-right">Cost ($)</th>
                                            <th class="px-3 py-3 text-right">Retail ($)</th>
                                            <th class="px-3 py-3 text-center">Shelf Stock</th>
                                            <th class="px-3 py-3 text-center">Main Whse</th>
                                            <th class="px-3 py-3 text-center">Child Facilities</th>
                                            <th class="px-3 py-3 text-center">Use Whse</th>
                                            <th class="px-3 py-3 text-center">Reserved</th>
                                            <th class="px-3 py-3 text-center bg-indigo-50/50">Total</th>
                                            <th class="px-4 py-3 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($inventory as $item)
                                            <tr class="hover:bg-slate-50/60 transition duration-100">
                                                <td class="px-4 py-3 font-bold text-slate-800 text-xs">
                                                    @if($item->variant && $item->variant->product)
                                                        <a href="{{ route('admin.ecommerce.product-edit', $item->variant->product->id) }}" class="text-indigo-600 hover:underline">
                                                            {{ $item->variant->product->title }}
                                                        </a>
                                                        @if($item->variant->sku !== $item->variant->product->sku)
                                                            <span class="block text-[10px] text-slate-400 font-normal">Variant ID: #{{ $item->variant->id }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-slate-400 italic">No associated product</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 font-mono text-xs text-slate-700 font-semibold">{{ $item->variant ? $item->variant->sku : 'N/A' }}</td>
                                                
                                                <td class="px-3 py-3 text-right font-mono text-xs text-slate-600">
                                                    ${{ $item->variant && $item->variant->item_cost !== null ? number_format((float)$item->variant->item_cost, 2) : '0.00' }}
                                                </td>

                                                <td class="px-3 py-3 text-right font-mono text-xs font-bold text-slate-900">
                                                    ${{ $item->variant ? number_format((float)$item->variant->public_price, 2) : '0.00' }}
                                                </td>

                                                <!-- Available Stock (Shelf) -->
                                                <td class="px-3 py-3 text-center">
                                                    @if($item->variant && $item->variant->download_item)
                                                        <span class="text-slate-400">-</span>
                                                    @else
                                                        <input type="number" wire:model="stockInputs.{{ $item->id }}" class="w-16 px-2 py-1 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg text-center font-bold text-xs">
                                                    @endif
                                                </td>

                                                <!-- Primary Facility & Main Warehouse Stock -->
                                                <td class="px-3 py-3 text-center">
                                                    @if($item->variant && $item->variant->download_item)
                                                        <span class="text-slate-400">-</span>
                                                    @else
                                                        <input type="number" wire:model="warehouseInputs.{{ $item->id }}" class="w-16 px-2 py-1 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg text-center font-bold text-xs">
                                                    @endif
                                                </td>

                                                <!-- Child Warehouse Locations & Stock Levels -->
                                                <td class="px-3 py-3 text-center">
                                                    @if($item->variant && $item->variant->download_item)
                                                        <span class="text-slate-400">-</span>
                                                    @elseif($item->warehouseInventories->isNotEmpty())
                                                        <div class="flex flex-col items-center gap-1">
                                                            @foreach($item->warehouseInventories as $wChild)
                                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100 whitespace-nowrap">
                                                                    {{ $wChild->warehouseLocation ? $wChild->warehouseLocation->name : "Facility #{$wChild->warehouse_location_id}" }}: <strong>{{ $wChild->stock_level }}</strong>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-xs text-slate-400 font-normal">None</span>
                                                    @endif
                                                </td>

                                                <!-- Use Warehouse Stock Checkbox -->
                                                <td class="px-3 py-3 text-center">
                                                    @if($item->variant && $item->variant->download_item)
                                                        <span class="text-slate-400">-</span>
                                                    @else
                                                        <input type="checkbox" wire:model.live="useWarehouseInputs.{{ $item->id }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                                                    @endif
                                                </td>

                                                <!-- Reserved Stock -->
                                                <td class="px-3 py-3 text-center">
                                                    @if($item->variant && $item->variant->download_item)
                                                        <span class="text-slate-400">-</span>
                                                    @else
                                                        <input type="number" wire:model="reservedInputs.{{ $item->id }}" class="w-16 px-2 py-1 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg text-center font-bold text-xs">
                                                    @endif
                                                </td>

                                                <!-- Dynamic Current Total calculations -->
                                                <td class="px-3 py-3 text-center bg-indigo-50/20 font-extrabold text-xs">
                                                    @if($item->variant && $item->variant->download_item)
                                                        <span class="text-[10px] text-indigo-500 font-bold bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">Digital</span>
                                                    @else
                                                        @php
                                                            $avail = (int)($stockInputs[$item->id] ?? 0);
                                                            $wh = (int)($warehouseInputs[$item->id] ?? 0);
                                                            $res = (int)($reservedInputs[$item->id] ?? 0);
                                                            $useWh = (bool)($useWarehouseInputs[$item->id] ?? false);
                                                            $childSum = (int) ($item->relationLoaded('warehouseInventories') ? $item->warehouseInventories->sum('stock_level') : $item->warehouseInventories()->sum('stock_level'));
                                                            $total = $useWh ? ($avail + $wh + $childSum - $res) : ($avail - $res);
                                                        @endphp
                                                        <span class="{{ $total < 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $total }}</span>
                                                    @endif
                                                </td>

                                                <!-- Save Button -->
                                                <td class="px-4 py-3 text-right">
                                                    @if($item->variant && !$item->variant->download_item)
                                                        <button wire:click="saveStock({{ $item->id }})" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-lg transition duration-150 shadow-sm">
                                                            Save
                                                        </button>
                                                    @else
                                                        <span class="text-slate-300">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination Links -->
                            <div class="pt-4">
                                {{ $inventory->links() }}
                            </div>
                        @endif
                    </div>
                @endif

                <!-- TAB 2: CSV Stock & Cost Import Wizard -->
                @if($activeTab === 'csv_import')
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-sans">CSV Stock & Cost Import Wizard</h3>
                                <p class="text-xs text-slate-500 mt-1">Import inventory quantities and unit costs with automated retail percentage markup.</p>
                            </div>
                            <!-- Wizard Progress Pills -->
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $importStep >= 1 ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">1. Upload</span>
                                <span class="text-slate-300">&rarr;</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $importStep >= 2 ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">2. Map Fields</span>
                                <span class="text-slate-300">&rarr;</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $importStep === 3 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400' }}">3. Results</span>
                            </div>
                        </div>

                        <!-- Step 1: Upload & Markup Form -->
                        @if($importStep === 1)
                            <form wire:submit="uploadAndPreviewCsv" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">CSV / Data File</label>
                                        <input type="file" wire:model="csvFile" accept=".csv,.txt,.tsv" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2 bg-slate-50">
                                        <p class="text-[11px] text-slate-400">Supports comma (,), pipe (|), tab (\t), or semicolon (;) delimited CSV files up to 200MB.</p>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Cost / MSRP Markup or Discount (%)</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" min="-99.99" max="1000" wire:model="markupPercentage" placeholder="e.g. 15 for 15% markup or -20 for 20% off MSRP" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm font-semibold">
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-bold text-slate-400 pointer-events-none">%</span>
                                        </div>
                                        <p class="text-[11px] text-slate-400">Retail price = <code class="bg-slate-100 px-1 py-0.5 rounded text-indigo-600 font-mono">Cost &times; (1 + Markup / 100)</code>. Use positive values (e.g. 15% markup on cost) or negative values (e.g. -20% discount off MSRP).</p>
                                    </div>
                                </div>

                                <div class="space-y-3 pt-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Inventory Quantity Update Mode</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <label class="flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition {{ $inventoryMode === 'replace' ? 'border-indigo-500 bg-indigo-50/30' : 'border-slate-200 hover:bg-slate-50' }}">
                                            <input type="radio" wire:model.live="inventoryMode" value="replace" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                            <div>
                                                <span class="block text-xs font-bold text-slate-900">Replace Existing Inventory</span>
                                                <span class="block text-[11px] text-slate-500 mt-0.5">Overwrites the variant's shelf stock with the imported CSV quantity.</span>
                                            </div>
                                        </label>

                                        <label class="flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition {{ $inventoryMode === 'add' ? 'border-indigo-500 bg-indigo-50/30' : 'border-slate-200 hover:bg-slate-50' }}">
                                            <input type="radio" wire:model.live="inventoryMode" value="add" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                            <div>
                                                <span class="block text-xs font-bold text-slate-900">Add to Existing Inventory</span>
                                                <span class="block text-[11px] text-slate-500 mt-0.5">Adds the imported quantity to the existing available stock level.</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <label class="flex items-start gap-3 p-4 rounded-2xl border {{ $autoShowInResults ? 'border-indigo-500 bg-indigo-50/30' : 'border-slate-200 hover:bg-slate-50' }} cursor-pointer transition">
                                        <input type="checkbox" wire:model.live="autoShowInResults" class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-900">Auto-Enable Search Visibility on Matched Items</span>
                                            <span class="block text-[11px] text-slate-500 mt-0.5">When enabled, any catalog product matched in this sync will have its visibility set to show in search results (<code class="font-mono text-indigo-600">show_in_results = 1</code>).</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="flex justify-end pt-4 border-t border-slate-100">
                                    <button type="submit" wire:loading.attr="disabled" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md inline-flex items-center gap-2 cursor-pointer">
                                        <span wire:loading.remove wire:target="uploadAndPreviewCsv, csvFile" class="inline-flex items-center gap-2">
                                            <span>Parse & Preview CSV</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </span>
                                        <span wire:loading wire:target="uploadAndPreviewCsv, csvFile" class="inline-flex items-center gap-2">
                                            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span>Uploading & Parsing CSV...</span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        @endif

                        <!-- Step 2: Column Mapping & Data Preview -->
                        @if($importStep === 2)
                            <div class="space-y-6">
                                <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-xs font-bold text-indigo-900">Parsed {{ number_format($csvTotalRows) }} data rows. Map CSV columns to variant fields below:</span>
                                    </div>
                                    <button type="button" wire:click="resetImportWizard" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                                        &larr; Choose Different File
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            SKU Identifier Column <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="skuColumn" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <option value="">-- Select SKU Column --</option>
                                            @foreach($csvHeaders as $header)
                                                <option value="{{ $header }}">{{ $header }}</option>
                                            @endforeach
                                        </select>
                                        @error('skuColumn') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Unit Cost Column
                                        </label>
                                        <select wire:model="costColumn" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <option value="">-- Do Not Import Cost --</option>
                                            @foreach($csvHeaders as $header)
                                                <option value="{{ $header }}">{{ $header }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Stock Level / Quantity Column
                                        </label>
                                        <select wire:model="stockColumn" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <option value="">-- Do Not Import Stock --</option>
                                            @foreach($csvHeaders as $header)
                                                <option value="{{ $header }}">{{ $header }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Preview Table -->
                                <div class="space-y-2">
                                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sample Data Preview (First 5 Rows)</h4>
                                    <div class="overflow-x-auto rounded-2xl border border-slate-100">
                                        <table class="w-full text-left text-xs text-slate-600">
                                            <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                                                <tr>
                                                    @foreach($csvHeaders as $header)
                                                        <th class="px-4 py-3 {{ in_array($header, [$skuColumn, $costColumn, $stockColumn]) ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                                            {{ $header }}
                                                            @if($header === $skuColumn) <span class="block text-3xs font-bold text-indigo-500">[SKU]</span> @endif
                                                            @if($header === $costColumn) <span class="block text-3xs font-bold text-indigo-500">[Cost]</span> @endif
                                                            @if($header === $stockColumn) <span class="block text-3xs font-bold text-indigo-500">[Stock]</span> @endif
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                @foreach($csvPreviewRows as $pRow)
                                                    <tr class="hover:bg-slate-50">
                                                        @foreach($csvHeaders as $header)
                                                            <td class="px-4 py-2.5 font-mono text-[11px] {{ in_array($header, [$skuColumn, $costColumn, $stockColumn]) ? 'bg-indigo-50/30 font-bold text-indigo-900' : '' }}">
                                                                {{ $pRow[$header] ?? '' }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="resetImportWizard" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                                        Cancel
                                    </button>
                                    <button type="button" wire:click="executeStockAndCostImport" wire:loading.attr="disabled" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md inline-flex items-center gap-2 cursor-pointer">
                                        <span wire:loading.remove wire:target="executeStockAndCostImport" class="inline-flex items-center gap-2">
                                            <span>Execute Stock & Cost Import</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                        <span wire:loading wire:target="executeStockAndCostImport" class="inline-flex items-center gap-2">
                                            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span>Importing Data in Batches...</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Step 3: Results Summary -->
                        @if($importStep === 3)
                            <div class="space-y-6">
                                <!-- Stats Cards Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-slate-800">{{ number_format($importResults['stats']['total_rows'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Rows Processed</span>
                                    </div>
                                    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-emerald-700">{{ number_format($importResults['stats']['updated_count'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Variants Updated</span>
                                    </div>
                                    <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-amber-700">{{ number_format($importResults['stats']['skipped_count'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Rows Skipped</span>
                                    </div>
                                    <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-indigo-700">{{ number_format($importResults['stats']['auto_shown_count'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Products Made Visible</span>
                                    </div>
                                </div>

                                <!-- Detailed itemized results table -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Itemized Process Log</h4>
                                        @if(($importResults['stats']['total_rows'] ?? 0) > count($importResults['log'] ?? []))
                                            <span class="text-[11px] text-slate-400 font-medium">Showing first {{ count($importResults['log'] ?? []) }} rows</span>
                                        @endif
                                    </div>
                                    <div class="max-h-96 overflow-y-auto overflow-x-auto rounded-2xl border border-slate-100">
                                        <table class="w-full text-left text-xs text-slate-600">
                                            <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100 sticky top-0">
                                                <tr>
                                                    <th class="px-3 py-2">Row</th>
                                                    <th class="px-3 py-2">SKU</th>
                                                    <th class="px-3 py-2">Cost ($)</th>
                                                    <th class="px-3 py-2">Retail Price ($)</th>
                                                    <th class="px-3 py-2">Stock Level</th>
                                                    <th class="px-3 py-2">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                @foreach(($importResults['log'] ?? []) as $entry)
                                                    <tr class="{{ ($entry['status'] ?? '') === 'updated' ? 'hover:bg-emerald-50/30' : 'bg-amber-50/20' }}">
                                                        <td class="px-3 py-2 font-mono text-[11px] text-slate-400">#{{ $entry['row'] }}</td>
                                                        <td class="px-3 py-2 font-mono font-bold text-slate-800">{{ $entry['sku'] }}</td>
                                                        <td class="px-3 py-2 font-mono text-[11px]">
                                                             @if(isset($entry['new_cost']) && $entry['new_cost'] !== null)
                                                                @if(isset($entry['old_cost']) && $entry['old_cost'] !== null)
                                                                    <span class="text-slate-400 line-through">${{ number_format((float)$entry['old_cost'], 2) }}</span> &rarr;
                                                                @endif
                                                                <span class="font-bold text-slate-800">${{ number_format((float)$entry['new_cost'], 2) }}</span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 font-mono text-[11px]">
                                                            @if(isset($entry['new_price']) && $entry['new_price'] !== null)
                                                                @if(isset($entry['old_price']) && $entry['old_price'] !== null)
                                                                    <span class="text-slate-400 line-through">${{ number_format((float)$entry['old_price'], 2) }}</span> &rarr;
                                                                @endif
                                                                <span class="font-bold text-emerald-700">${{ number_format((float)$entry['new_price'], 2) }}</span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 font-mono text-[11px]">
                                                            @if(isset($entry['new_stock']) && $entry['new_stock'] !== null)
                                                                @if(isset($entry['old_stock']) && $entry['old_stock'] !== null)
                                                                    <span class="text-slate-400 line-through">{{ $entry['old_stock'] }}</span> &rarr;
                                                                @endif
                                                                <span class="font-bold text-indigo-700">{{ $entry['new_stock'] }}</span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2">
                                                            @if(($entry['status'] ?? '') === 'updated')
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Updated</span>
                                                            @else
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">{{ $entry['message'] ?? 'Skipped' }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="setTab('stock')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition">
                                        View In Stock Control
                                    </button>
                                    <button type="button" wire:click="resetImportWizard" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-md">
                                        Import Another CSV File
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- TAB 3: FTP / SFTP Remote Feed Wizard -->
                @if($activeTab === 'ftp_import')
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-sans">FTP / SFTP Inventory Sync</h3>
                                <p class="text-xs text-slate-500 mt-1">Connect to supplier or vendor remote servers to sync live CSV inventory feeds.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $ftpStep >= 1 ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">1. Connect</span>
                                <span class="text-slate-300">&rarr;</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $ftpStep >= 2 ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">2. Map Fields</span>
                                <span class="text-slate-300">&rarr;</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $ftpStep === 3 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400' }}">3. Results</span>
                            </div>
                        </div>

                        <!-- Step 1: FTP Connection Configuration -->
                        @if($ftpStep === 1)
                            <form wire:submit="downloadAndPreviewFtp" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Protocol</label>
                                        <select wire:model.live="ftpProtocol" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <option value="ftp">FTP (Standard)</option>
                                            <option value="ftps">FTPS (SSL / TLS)</option>
                                            <option value="sftp">SFTP (SSH File Transfer)</option>
                                        </select>
                                    </div>

                                    <div class="space-y-1.5 md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Host / Server Address</label>
                                        <input type="text" wire:model="ftpHost" placeholder="ftp.supplier.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Port</label>
                                        <input type="number" wire:model.live="ftpPort" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Username</label>
                                        <input type="text" wire:model="ftpUsername" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                                        <input type="password" wire:model="ftpPassword" autocomplete="current-password" placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Remote File Path</label>
                                    <input type="text" wire:model="ftpRemotePath" placeholder="/feeds/inventory.csv or inventory.csv" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-mono font-semibold focus:border-indigo-500 focus:outline-none">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Cost / MSRP Markup or Discount (%)</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" min="-99.99" max="1000" wire:model="ftpMarkupPercentage" placeholder="e.g. 10 or -20" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-bold text-slate-400 pointer-events-none">%</span>
                                        </div>
                                        <p class="text-[11px] text-slate-400">Use positive values (e.g. 15%) for markup on cost, or negative values (e.g. -20%) to discount from MSRP.</p>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Quantity Update Mode</label>
                                        <select wire:model="ftpInventoryMode" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <option value="replace">Replace Existing Inventory Level</option>
                                            <option value="add">Add to Existing Inventory Level</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <label class="flex items-start gap-3 p-4 rounded-2xl border {{ $ftpAutoShowInResults ? 'border-indigo-500 bg-indigo-50/30' : 'border-slate-200 hover:bg-slate-50' }} cursor-pointer transition">
                                        <input type="checkbox" wire:model.live="ftpAutoShowInResults" class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-900">Auto-Enable Search Visibility on Matched Items</span>
                                            <span class="block text-[11px] text-slate-500 mt-0.5">When enabled, any catalog product matched during this FTP sync will have its visibility set to show in search results (<code class="font-mono text-indigo-600">show_in_results = 1</code>).</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="saveFtpSettings" wire:loading.attr="disabled" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 disabled:opacity-50 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition inline-flex items-center gap-1.5 cursor-pointer">
                                        <svg wire:loading.remove wire:target="saveFtpSettings" class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                        <svg wire:loading wire:target="saveFtpSettings" class="animate-spin h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span>Save Credentials</span>
                                    </button>
                                    <button type="submit" wire:loading.attr="disabled" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md inline-flex items-center gap-2 cursor-pointer">
                                        <span wire:loading.remove wire:target="downloadAndPreviewFtp" class="inline-flex items-center gap-2">
                                            <span>Connect & Download Remote Feed</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </span>
                                        <span wire:loading wire:target="downloadAndPreviewFtp" class="inline-flex items-center gap-2">
                                            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span>Connecting & Downloading Remote File...</span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        @endif

                        <!-- Step 2: FTP Column Mapping -->
                        @if($ftpStep === 2)
                            <div class="space-y-6">
                                <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="text-xs font-bold text-indigo-900">Downloaded and parsed {{ number_format($ftpTotalRows) }} remote data rows.</span>
                                    </div>
                                    <button type="button" wire:click="resetFtpWizard" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                                        &larr; Reconfigure Connection
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            SKU Identifier Column <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="ftpSkuColumn" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <option value="">-- Select SKU Column --</option>
                                            @foreach($ftpHeaders as $header)
                                                <option value="{{ $header }}">{{ $header }}</option>
                                            @endforeach
                                        </select>
                                        @error('ftpSkuColumn') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Unit Cost Column
                                        </label>
                                        <select wire:model="ftpCostColumn" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <option value="">-- Do Not Import Cost --</option>
                                            @foreach($ftpHeaders as $header)
                                                <option value="{{ $header }}">{{ $header }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Stock Level / Quantity Column
                                        </label>
                                        <select wire:model="ftpStockColumn" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <option value="">-- Do Not Import Stock --</option>
                                            @foreach($ftpHeaders as $header)
                                                <option value="{{ $header }}">{{ $header }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Preview Table -->
                                <div class="space-y-2">
                                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sample Remote Data Preview (First 5 Rows)</h4>
                                    <div class="overflow-x-auto rounded-2xl border border-slate-100">
                                        <table class="w-full text-left text-xs text-slate-600">
                                            <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                                                <tr>
                                                    @foreach($ftpHeaders as $header)
                                                        <th class="px-4 py-3 {{ in_array($header, [$ftpSkuColumn, $ftpCostColumn, $ftpStockColumn]) ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                                            {{ $header }}
                                                            @if($header === $ftpSkuColumn) <span class="block text-3xs font-bold text-indigo-500">[SKU]</span> @endif
                                                            @if($header === $ftpCostColumn) <span class="block text-3xs font-bold text-indigo-500">[Cost]</span> @endif
                                                            @if($header === $ftpStockColumn) <span class="block text-3xs font-bold text-indigo-500">[Stock]</span> @endif
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                @foreach($ftpPreviewRows as $pRow)
                                                    <tr class="hover:bg-slate-50">
                                                        @foreach($ftpHeaders as $header)
                                                            <td class="px-4 py-2.5 font-mono text-[11px] {{ in_array($header, [$ftpSkuColumn, $ftpCostColumn, $ftpStockColumn]) ? 'bg-indigo-50/30 font-bold text-indigo-900' : '' }}">
                                                                {{ $pRow[$header] ?? '' }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="resetFtpWizard" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                                        Cancel
                                    </button>
                                    <button type="button" wire:click="executeFtpStockAndCostImport" wire:loading.attr="disabled" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md inline-flex items-center gap-2 cursor-pointer">
                                        <span wire:loading.remove wire:target="executeFtpStockAndCostImport" class="inline-flex items-center gap-2">
                                            <span>Execute Remote Feed Import</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                        <span wire:loading wire:target="executeFtpStockAndCostImport" class="inline-flex items-center gap-2">
                                            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span>Importing Feed in Batches...</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Step 3: FTP Results -->
                        @if($ftpStep === 3)
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-slate-800">{{ number_format($ftpResults['stats']['total_rows'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Remote Rows Processed</span>
                                    </div>
                                    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-emerald-700">{{ number_format($ftpResults['stats']['updated_count'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Variants Updated</span>
                                    </div>
                                    <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-amber-700">{{ number_format($ftpResults['stats']['skipped_count'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Rows Skipped</span>
                                    </div>
                                    <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-indigo-700">{{ number_format($ftpResults['stats']['auto_shown_count'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Products Made Visible</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="setTab('stock')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition">
                                        View Stock Overview
                                    </button>
                                    <button type="button" wire:click="resetFtpWizard" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-md">
                                        Run New FTP Sync
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- TAB 4: Amazon / eBay Marketplace Pricing Wizard -->
                @if($activeTab === 'marketplace')
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-sans">Amazon & eBay Marketplace Pricing Sync</h3>
                                <p class="text-xs text-slate-500 mt-1">Automatically calculate and push marketplace selling prices based on each variant's current unit cost.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $marketplaceStep >= 1 ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">1. Configure</span>
                                <span class="text-slate-300">&rarr;</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $marketplaceStep >= 2 ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">2. Map SKU</span>
                                <span class="text-slate-300">&rarr;</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $marketplaceStep === 3 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400' }}">3. Results</span>
                            </div>
                        </div>

                        <!-- Step 1: Settings & File Upload -->
                        @if($marketplaceStep === 1)
                            <form wire:submit="uploadAndPreviewMarketplaceCsv" class="space-y-6">
                                <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200 text-xs text-amber-900 space-y-1">
                                    <div class="font-bold flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span>Cost Requirement Notice</span>
                                    </div>
                                    <p>Marketplace pricing formulas strictly rely on the variant's <strong>current stored unit cost</strong> in the database. Any SKU that cannot be found or has a cost of $0.00 will be automatically skipped.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Target Marketplace Channels</label>
                                        <select wire:model="targetMarketplace" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <option value="both">Both Amazon and eBay</option>
                                            <option value="amazon">Amazon Only</option>
                                            <option value="ebay">eBay Only</option>
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Marketplace Markup Percentage (%)</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" min="0" max="1000" wire:model="marketplaceMarkup" placeholder="15" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-bold text-slate-400 pointer-events-none">%</span>
                                        </div>
                                        <p class="text-[11px] text-slate-400">Marketplace Price = <code class="bg-slate-100 px-1 py-0.5 rounded text-indigo-600 font-mono">Current Cost &times; (1 + Markup / 100)</code></p>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">CSV SKU List</label>
                                    <input type="file" wire:model="marketplaceCsvFile" accept=".csv,.txt,.tsv" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2 bg-slate-50">
                                </div>

                                <div class="flex justify-end pt-4 border-t border-slate-100">
                                    <button type="submit" wire:loading.attr="disabled" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md inline-flex items-center gap-2 cursor-pointer">
                                        <span wire:loading.remove wire:target="uploadAndPreviewMarketplaceCsv, marketplaceCsvFile" class="inline-flex items-center gap-2">
                                            <span>Parse SKU File</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </span>
                                        <span wire:loading wire:target="uploadAndPreviewMarketplaceCsv, marketplaceCsvFile" class="inline-flex items-center gap-2">
                                            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span>Parsing SKU File...</span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        @endif

                        <!-- Step 2: SKU Mapping -->
                        @if($marketplaceStep === 2)
                            <div class="space-y-6">
                                <div class="space-y-1.5 max-w-md">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        SKU Identifier Column <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="marketplaceSkuColumn" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-xs font-semibold focus:border-indigo-500 focus:outline-none">
                                        <option value="">-- Select SKU Column --</option>
                                        @foreach($marketplaceHeaders as $header)
                                            <option value="{{ $header }}">{{ $header }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="resetMarketplaceWizard" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition">
                                        Cancel
                                    </button>
                                    <button type="button" wire:click="executeMarketplaceImport" wire:loading.attr="disabled" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md inline-flex items-center gap-2 cursor-pointer">
                                        <span wire:loading.remove wire:target="executeMarketplaceImport" class="inline-flex items-center gap-2">
                                            <span>Sync Marketplace Pricing ({{ number_format($marketplaceTotalRows) }} SKUs)</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                        <span wire:loading wire:target="executeMarketplaceImport" class="inline-flex items-center gap-2">
                                            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span>Applying Marketplace Pricing...</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Step 3: Marketplace Results -->
                        @if($marketplaceStep === 3)
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-slate-800">{{ number_format($marketplaceResults['stats']['total_rows'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total SKUs Processed</span>
                                    </div>
                                    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-emerald-700">{{ number_format($marketplaceResults['stats']['updated_count'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Marketplace Prices Set</span>
                                    </div>
                                    <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl text-center">
                                        <span class="block text-2xl font-black text-amber-700">{{ number_format($marketplaceResults['stats']['skipped_count'] ?? 0) }}</span>
                                        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Skipped (No Cost / Missing)</span>
                                    </div>
                                </div>

                                <div class="max-h-96 overflow-y-auto rounded-2xl border border-slate-100">
                                    <table class="w-full text-left text-xs text-slate-600">
                                        <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100 sticky top-0">
                                            <tr>
                                                <th class="px-3 py-2">Row</th>
                                                <th class="px-3 py-2">SKU</th>
                                                <th class="px-3 py-2">Current Cost ($)</th>
                                                <th class="px-3 py-2">Amazon Price ($)</th>
                                                <th class="px-3 py-2">eBay Price ($)</th>
                                                <th class="px-3 py-2">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach(($marketplaceResults['log'] ?? []) as $entry)
                                                <tr class="{{ ($entry['status'] ?? '') === 'updated' ? 'hover:bg-emerald-50/30' : 'bg-amber-50/20' }}">
                                                    <td class="px-3 py-2 font-mono text-slate-400">#{{ $entry['row'] }}</td>
                                                    <td class="px-3 py-2 font-mono font-bold text-slate-800">{{ $entry['sku'] }}</td>
                                                    <td class="px-3 py-2 font-mono">${{ isset($entry['cost']) ? number_format((float)$entry['cost'], 2) : '0.00' }}</td>
                                                    <td class="px-3 py-2 font-mono">{{ isset($entry['amazon_price']) && $entry['amazon_price'] !== null ? '$' . number_format((float)$entry['amazon_price'], 2) : '-' }}</td>
                                                    <td class="px-3 py-2 font-mono">{{ isset($entry['ebay_price']) && $entry['ebay_price'] !== null ? '$' . number_format((float)$entry['ebay_price'], 2) : '-' }}</td>
                                                    <td class="px-3 py-2">
                                                        @if(($entry['status'] ?? '') === 'updated')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Updated</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">{{ $entry['message'] ?? 'Skipped' }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="flex justify-end pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="resetMarketplaceWizard" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-md">
                                        Sync Another Marketplace Feed
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- TAB 5: Bulk Tools & Reset -->
                @if($activeTab === 'reset')
                    <div class="space-y-6">
                        <!-- Card 1: Bulk Visibility Tool - Hide Zero Price Products -->
                        <div class="bg-white border border-amber-100 rounded-3xl p-6 shadow-sm space-y-6">
                            <div class="border-b border-amber-100 pb-4">
                                <div class="flex items-center gap-2 text-amber-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    <h3 class="text-lg font-black text-amber-800 uppercase tracking-wider font-sans">Bulk Visibility: Hide Zero-Price Products</h3>
                                </div>
                                <p class="text-xs text-amber-600 mt-1">Batch update catalog visibility so products with no priced variants ($0.00 pricing) do not show in search or category listings.</p>
                            </div>

                            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 text-xs text-amber-900 space-y-2">
                                <p class="font-bold">What will happen:</p>
                                <ul class="list-disc list-inside space-y-1 text-amber-800">
                                    <li>All products that do <strong>not</strong> have any variant with a positive price (<code class="font-mono">public_price &gt; 0</code>, <code class="font-mono">sale_price &gt; 0</code> when on sale, or <code class="font-mono">wholesale_price &gt; 0</code>) will be updated with <code class="font-mono font-bold">show_in_results = 0</code>.</li>
                                    <li>Products that have at least one variant with a price greater than $0.00 will remain untouched.</li>
                                    <li>When you later sync stock &amp; pricing via CSV or FTP with the auto-enable toggle checked, matched products will automatically be restored to <code class="font-mono font-bold">show_in_results = 1</code>.</li>
                                </ul>
                            </div>

                            <div class="space-y-4 max-w-lg">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model.live="hideZeroConfirmed" class="mt-1 rounded text-amber-600 focus:ring-amber-500">
                                    <span class="text-xs font-bold text-slate-800">I understand that this will set search results visibility to OFF (<code class="font-mono text-amber-700">show_in_results = 0</code>) for all products with $0.00 priced variants.</span>
                                </label>

                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Type <span class="text-amber-600 font-mono">HIDE</span> to confirm:
                                    </label>
                                    <input type="text" wire:model.live="hideZeroConfirmationText" placeholder="HIDE" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl text-xs font-mono font-bold uppercase tracking-widest focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                                    @error('hideZeroConfirmationText') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <button type="button" wire:click="executeHideZeroPriceProducts" wire:loading.attr="disabled" @disabled(!$hideZeroConfirmed || strtoupper(trim($hideZeroConfirmationText)) !== 'HIDE') class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md inline-flex items-center gap-2 cursor-pointer">
                                    <svg wire:loading.remove wire:target="executeHideZeroPriceProducts" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    <svg wire:loading wire:target="executeHideZeroPriceProducts" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Hide Unpriced / $0.00 Products From Results</span>
                                </button>
                            </div>
                        </div>

                        <!-- Card 2: Danger Zone - Zero Inventory Reset -->
                        <div class="bg-white border border-rose-100 rounded-3xl p-6 shadow-sm space-y-6">
                            <div class="border-b border-rose-100 pb-4">
                                <div class="flex items-center gap-2 text-rose-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <h3 class="text-lg font-black text-rose-700 uppercase tracking-wider font-sans">Danger Zone: Zero Inventory Reset</h3>
                                </div>
                                <p class="text-xs text-rose-500 mt-1">This operation instantly resets all product variant stock levels across shelf and warehouse facilities to zero.</p>
                            </div>

                            <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200 text-xs text-rose-800 space-y-2">
                                <p class="font-bold">What will happen:</p>
                                <ul class="list-disc list-inside space-y-1 text-rose-700">
                                    <li>All records in <code class="font-mono font-bold">products_inventory</code> will have <code class="font-mono">quantity_available</code> set to <strong>0</strong>.</li>
                                    <li>All records in <code class="font-mono font-bold">product_inventory_warehouses</code> will have <code class="font-mono">stock_level</code> set to <strong>0</strong>.</li>
                                    <li>Product catalog records, variant costs, and retail prices will remain untouched.</li>
                                </ul>
                            </div>

                            <div class="space-y-4 max-w-lg">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model.live="resetConfirmed" class="mt-1 rounded text-rose-600 focus:ring-rose-500">
                                    <span class="text-xs font-bold text-slate-800">I understand that this action is irreversible and will zero out all warehouse and shelf stock levels.</span>
                                </label>

                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Type <span class="text-rose-600 font-mono">RESET</span> to confirm:
                                    </label>
                                    <input type="text" wire:model.live="resetConfirmationText" placeholder="RESET" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl text-xs font-mono font-bold uppercase tracking-widest focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500">
                                    @error('resetConfirmationText') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <button type="button" wire:click="executeZeroInventoryReset" wire:loading.attr="disabled" @disabled(!$resetConfirmed || strtoupper(trim($resetConfirmationText)) !== 'RESET') class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md inline-flex items-center gap-2 cursor-pointer">
                                    <svg wire:loading.remove wire:target="executeZeroInventoryReset" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <svg wire:loading wire:target="executeZeroInventoryReset" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Zero Out All Inventory</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
