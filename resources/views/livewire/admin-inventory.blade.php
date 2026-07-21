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

            <!-- Inventory Content -->
            <div class="lg:col-span-9 space-y-6">
                <!-- Notifications -->
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- CSV Bulk Stock Update Panel -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2 mb-4">Bulk Stock CSV Import</h3>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="max-w-md">
                            <p class="text-xs text-slate-500">Upload a comma-separated or pipe-separated CSV file containing fields: <code class="bg-slate-100 px-1 py-0.5 rounded text-indigo-600 font-mono">SKU|stock_level|warehouse_level|locationid</code>.</p>
                            <p class="text-3xs text-slate-400 mt-1">Updates quantity_available, warehouse_stock_level, and locationid automatically based on the SKU identifier.</p>
                        </div>
                        <form wire:submit.prevent="uploadCsv" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                            <div class="relative">
                                <input type="file" wire:model="csvFile" id="csvFile" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150 shadow-md">
                                Process CSV
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Inventory Management Table Card -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6 font-sans">Stock Control</h3>

                    <!-- Live search bar -->
                    <div class="mb-6 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input wire:model.live="search" type="text" placeholder="Search inventory by product name, SKU, description..." class="pl-11 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 text-slate-700 placeholder-slate-400 rounded-2xl focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition duration-150 text-sm">
                    </div>

                    @if($inventory->isEmpty())
                        <p class="text-sm text-slate-500 text-center py-6">No variant inventory records found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-500">
                                <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-3">Product Name</th>
                                        <th class="px-4 py-3">SKU</th>
                                        <th class="px-4 py-3 text-center">Available Stock</th>
                                        <th class="px-4 py-3 text-center">Warehouse Stock</th>
                                        <th class="px-4 py-3 text-center">Use Warehouse</th>
                                        <th class="px-4 py-3 text-center">Reserved Stock</th>
                                        <th class="px-4 py-3 text-center bg-indigo-50/50">Current Total</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($inventory as $item)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-4 py-3 font-bold text-slate-800">
                                                @if($item->variant && $item->variant->product)
                                                    <a href="{{ route('admin.ecommerce.product-edit', $item->variant->product->id) }}" class="text-indigo-600 hover:underline">
                                                        {{ $item->variant->product->title }}
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 font-mono text-xs">{{ $item->variant ? $item->variant->sku : 'N/A' }}</td>
                                            
                                            <!-- Available Stock -->
                                            <td class="px-4 py-3 text-center">
                                                @if($item->variant && $item->variant->download_item)
                                                    <span class="text-slate-400">-</span>
                                                @else
                                                    <input type="number" wire:model="stockInputs.{{ $item->id }}" class="w-20 px-2 py-1 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg text-center font-bold text-xs">
                                                @endif
                                            </td>

                                            <!-- Warehouse Stock -->
                                            <td class="px-4 py-3 text-center">
                                                @if($item->variant && $item->variant->download_item)
                                                    <span class="text-slate-400">-</span>
                                                @else
                                                    <input type="number" wire:model="warehouseInputs.{{ $item->id }}" class="w-20 px-2 py-1 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg text-center font-bold text-xs">
                                                @endif
                                            </td>

                                            <!-- Use Warehouse Stock Checkbox -->
                                            <td class="px-4 py-3 text-center">
                                                @if($item->variant && $item->variant->download_item)
                                                    <span class="text-slate-400">-</span>
                                                @else
                                                    <input type="checkbox" wire:model.live="useWarehouseInputs.{{ $item->id }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                                                @endif
                                            </td>

                                            <!-- Reserved Stock -->
                                            <td class="px-4 py-3 text-center">
                                                @if($item->variant && $item->variant->download_item)
                                                    <span class="text-slate-400">-</span>
                                                @else
                                                    <input type="number" wire:model="reservedInputs.{{ $item->id }}" class="w-20 px-2 py-1 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg text-center font-bold text-xs">
                                                @endif
                                            </td>

                                            <!-- Dynamic Current Total calculations (real-time before save) -->
                                            <td class="px-4 py-3 text-center bg-indigo-50/20 font-extrabold">
                                                @if($item->variant && $item->variant->download_item)
                                                    <span class="text-xs text-indigo-500 font-bold bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">Unlimited (Digital)</span>
                                                @else
                                                    @php
                                                        $avail = (int)($stockInputs[$item->id] ?? 0);
                                                        $wh = (int)($warehouseInputs[$item->id] ?? 0);
                                                        $res = (int)($reservedInputs[$item->id] ?? 0);
                                                        $useWh = (bool)($useWarehouseInputs[$item->id] ?? false);
                                                        $total = $useWh ? ($avail + $wh - $res) : ($avail - $res);
                                                    @endphp
                                                    <span class="{{ $total < 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $total }}</span>
                                                @endif
                                            </td>

                                            <!-- Save Button -->
                                            <td class="px-4 py-3 text-right">
                                                @if($item->variant && !$item->variant->download_item)
                                                    <button wire:click="saveStock({{ $item->id }})" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-lg transition duration-150">
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
                        <div class="mt-6">
                            {{ $inventory->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
