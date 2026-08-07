<div class="py-12">
    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
    <div class="max-w-[1700px] w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-slate-100 dark:to-indigo-200 bg-clip-text text-transparent">
                            Products Manager
                        </h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage, search, edit, and organize all catalog products.</p>
                    </div>
                    <a href="{{ route('admin.ecommerce.product-create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold rounded-2xl shadow-md shadow-indigo-100 transition shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Product
                    </a>
                </div>

                <!-- Status/Success Notifications -->
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Recently Edited Products Quick List -->
                @if(isset($recentlyEdited) && $recentlyEdited->isNotEmpty())
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                            <span class="p-2.5 bg-indigo-50 rounded-2xl text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 leading-snug">Recently Edited Products</h3>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">Quick dashboard access to the last 5 modified products</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                            <table class="w-full text-left text-xs text-slate-700">
                                <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Thumbnail</th>
                                        <th scope="col" class="px-4 py-3">Product Name</th>
                                        <th scope="col" class="px-4 py-3">Price Range</th>
                                        <th scope="col" class="px-4 py-3">Last Modified</th>
                                        <th scope="col" class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach($recentlyEdited as $p)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <!-- Thumbnail -->
                                            <td class="px-4 py-3">
                                                @if($p->primaryThumbnailUrl())
                                                    <img src="{{ $p->primaryThumbnailUrl() }}" class="w-10 h-10 object-cover rounded-xl border border-slate-200 shadow-sm" alt="Thumbnail">
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-[10px] font-bold">N/A</div>
                                                @endif
                                            </td>
                                            <!-- Product Name -->
                                            <td class="px-4 py-3 font-bold text-slate-800 text-sm">
                                                {{ $p->title }}
                                            </td>
                                            <!-- Price Range -->
                                            <td class="px-4 py-3 font-semibold text-indigo-600">
                                                {{ $p->price_range }}
                                            </td>
                                            <!-- Last Modified -->
                                            <td class="px-4 py-3 text-slate-500 font-medium">
                                                {{ $p->updated_at->diffForHumans() }}
                                            </td>
                                            <!-- Actions -->
                                            <td class="px-4 py-3 text-right space-x-2">
                                                <a href="{{ route('admin.ecommerce.product-edit', ['id' => $p->id]) }}" wire:navigate
                                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-[11px] font-extrabold rounded-lg transition duration-150 border border-indigo-100 shadow-sm">
                                                    Edit
                                                </a>
                                                @if($p->seo_slug)
                                                    <a href="{{ route('shop.product', ['seo_link' => $p->seo_slug]) }}" target="_blank"
                                                       class="inline-flex items-center px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 text-[11px] font-extrabold rounded-lg transition duration-150 border border-slate-200 shadow-sm">
                                                        View Site ↗
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Products Table card -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">

                    {{-- ─── Header row ─── --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-4 mb-4 gap-4">
                        <h3 class="text-lg font-bold text-slate-900">Manage Catalog</h3>

                        <div class="flex items-center gap-2 flex-wrap">
                            {{-- Keyword search --}}
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search catalog…"
                                       class="pl-9 pr-4 py-2 w-56 bg-slate-50 border border-slate-200 text-slate-700 placeholder-slate-400 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition">
                            </div>

                            {{-- Advanced Filters toggle --}}
                            <button wire:click="toggleAdvancedFilters"
                                    class="relative inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-xl border transition duration-150
                                           {{ $showAdvancedFilters ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                </svg>
                                Filters
                                @if($activeFilterCount > 0)
                                    <span class="absolute -top-1.5 -right-1.5 inline-flex items-center justify-center w-4 h-4 text-[9px] font-black bg-rose-500 text-white rounded-full">{{ $activeFilterCount }}</span>
                                @endif
                            </button>

                            {{-- Reset (only when filters active) --}}
                            @if($activeFilterCount > 0 || $search)
                                <button wire:click="resetFilters"
                                        class="inline-flex items-center gap-1 px-3 py-2 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 rounded-xl hover:bg-rose-100 transition duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Clear
                                </button>
                            @endif

                            {{-- New Product button --}}
                            <a href="{{ route('admin.ecommerce.product-create') }}" wire:navigate
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-sm hover:opacity-90 transition duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add New Product
                            </a>
                        </div>
                    </div>          </div>

                    {{-- ─── Advanced Filter Panel ─── --}}
                    @if($showAdvancedFilters)
                    <div class="mb-6 p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-5 animate-fade-in">

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                            {{-- Brand --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Brand</label>
                                <select wire:model.live="filterBrandId"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Product Type --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Product Type</label>
                                <select wire:model.live="filterProductType"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">All Types</option>
                                    <option value="shippable">Shippable / Physical</option>
                                    <option value="download">Digital Download</option>
                                    <option value="event">Event</option>
                                    <option value="featured">Featured</option>
                                </select>
                            </div>

                            {{-- Stock Status --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Stock Status</label>
                                <select wire:model.live="filterStockStatus"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">All</option>
                                    <option value="in_stock">In Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                </select>
                            </div>

                            {{-- Price Range --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Price Range</label>
                                <div class="flex items-center gap-2">
                                    <input wire:model.live.debounce.400ms="filterPriceMin" type="number" min="0" step="0.01" placeholder="Min $"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <span class="text-slate-400 text-xs font-bold shrink-0">–</span>
                                    <input wire:model.live.debounce.400ms="filterPriceMax" type="number" min="0" step="0.01" placeholder="Max $"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                </div>
                            </div>

                            {{-- Sort Order --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Sort By</label>
                                <select wire:model.live="filterSortBy"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <option value="last_modified">Recently Modified</option>
                                    <option value="oldest_modified">Oldest Modification</option>
                                    <option value="newest">Newest First (Date Created)</option>
                                    <option value="oldest">Oldest First (Date Created)</option>
                                    <option value="alpha_asc">A → Z</option>
                                    <option value="alpha_desc">Z → A</option>
                                    <option value="price_asc">Price: Low → High</option>
                                    <option value="price_desc">Price: High → Low</option>
                                </select>
                            </div>

                            {{-- Attribute Filter --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Attribute</label>
                                <div class="flex items-center gap-2">
                                    <input wire:model.live.debounce.400ms="filterAttribute" type="text" placeholder="Key (e.g. Color)"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <input wire:model.live.debounce.400ms="filterAttributeValue" type="text" placeholder="Value (opt.)"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                </div>
                            </div>

                        </div>

                        {{-- Category multi-select --}}
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Categories</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-1.5 max-h-40 overflow-y-auto pr-1">
                                @foreach($categoryTree as $cat)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" wire:model.live="filterCategoryIds" value="{{ $cat->id }}"
                                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                                        <span class="text-xs text-slate-700 group-hover:text-indigo-600 font-semibold truncate">{{ $cat->name }}</span>
                                    </label>
                                    @if($cat->children && $cat->children->count())
                                        @foreach($cat->children as $child)
                                            <label class="flex items-center gap-2 cursor-pointer group pl-4">
                                                <input type="checkbox" wire:model.live="filterCategoryIds" value="{{ $child->id }}"
                                                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                                                <span class="text-xs text-slate-500 group-hover:text-indigo-600 truncate">↳ {{ $child->name }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Active filter summary pills --}}
                        @if($activeFilterCount > 0 || $search)
                        <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-200">
                            @if($search)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-indigo-100 text-indigo-700 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Keyword: "{{ $search }}"
                                </span>
                            @endif
                            @if($filterBrandId)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-violet-100 text-violet-700 rounded-full">
                                    Brand: {{ $brands->find($filterBrandId)?->name ?? $filterBrandId }}
                                </span>
                            @endif
                            @if(!empty($filterCategoryIds))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-teal-100 text-teal-700 rounded-full">
                                    {{ count($filterCategoryIds) }} {{ Str::plural('Category', count($filterCategoryIds)) }}
                                </span>
                            @endif
                            @if($filterPriceMin !== '' || $filterPriceMax !== '')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-amber-100 text-amber-700 rounded-full">
                                    Price: ${{ $filterPriceMin ?: '0' }} – ${{ $filterPriceMax ?: '∞' }}
                                </span>
                            @endif
                            @if($filterProductType)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-sky-100 text-sky-700 rounded-full">
                                    Type: {{ ucfirst($filterProductType) }}
                                </span>
                            @endif
                            @if($filterStockStatus)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-emerald-100 text-emerald-700 rounded-full">
                                    Stock: {{ $filterStockStatus === 'in_stock' ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            @endif
                            @if($filterAttribute)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-pink-100 text-pink-700 rounded-full">
                                    Attr: {{ $filterAttribute }}{{ $filterAttributeValue ? ' = '.$filterAttributeValue : '' }}
                                </span>
                            @endif
                        </div>
                        @endif

                    </div>
                    @endif

                    {{-- Results count --}}
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs text-slate-400 font-semibold">
                            {{ $products->total() }} {{ Str::plural('product', $products->total()) }} found
                            @if($activeFilterCount > 0 || $search) <span class="text-indigo-500">(filtered)</span>@endif
                        </p>
                    </div>

                    @if($products->isEmpty())
                        <p class="text-sm text-slate-500 text-center py-6">Your search returned no matches or the catalog is empty.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($products as $product)
                                <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl space-y-4">
                                    <div class="flex items-start gap-4">
                                        <!-- Thumbnail Image or Placeholder -->
                                        <div class="flex-shrink-0">
                                            @if($product->primaryThumbnailUrl())
                                                <img src="{{ $product->primaryThumbnailUrl() }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 shadow-sm" alt="Thumbnail">
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-slate-200 border border-slate-300 flex items-center justify-center text-slate-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Text info & action buttons -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                                <div>
                                                    <button type="button" wire:click="toggleProductExpand({{ $product->id }})" class="flex items-center gap-2 group text-left focus:outline-none">
                                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ in_array($product->id, $expandedProducts) ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                        <span class="font-extrabold text-slate-900 group-hover:text-indigo-600 transition">{{ $product->title }}</span>
                                                    </button>
                                                    <span class="text-xs text-slate-400 block mt-1">Slug: <a href="{{ route('shop.product', $product->seo_slug) }}" target="_blank" class="text-indigo-600 hover:underline">/items/{{ $product->seo_slug }}</a></span>
                                                </div>
                                                <div class="flex gap-2">
                                                    <a href="{{ route('admin.ecommerce.product-edit', $product->id) }}" class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <a href="{{ route('admin.ecommerce.product-edit', $product->id) }}?create_variant=1" class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                        </svg>
                                                        Add Price | Variant
                                                    </a>
                                                    <button type="button" wire:click="openCopyModal({{ $product->id }})" class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                        </svg>
                                                        Copy
                                                    </button>
                                                    <button onclick="confirm('Are you sure you want to delete this product?') || event.stopImmediatePropagation()" wire:click="deleteProduct({{ $product->id }})" class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Variants details (Only shown if expanded) -->
                                    @if(in_array($product->id, $expandedProducts))
                                        <div class="overflow-x-auto pt-4 border-t border-slate-200/50 mt-2">
                                            <table class="w-full text-left text-xs text-slate-500">
                                                <thead>
                                                    <tr class="text-slate-400 uppercase font-semibold">
                                                        <th class="py-2">SKU</th>
                                                        <th class="py-2">Attributes</th>
                                                        <th class="py-2">Public Price</th>
                                                        <th class="py-2">Wholesale Price</th>
                                                        <th class="py-2">Sale Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    @foreach($product->variants as $variant)
                                                        @php
                                                            $attrs = json_decode($variant->attributes, true) ?: [];
                                                            $attrStr = collect($attrs)->map(fn($v, $k) => "$k: $v")->implode(', ');
                                                        @endphp
                                                        <tr>
                                                            <td class="py-2.5 font-bold text-slate-800">{{ $variant->sku }}</td>
                                                            <td class="py-2.5">{{ $attrStr ?: 'None' }}</td>
                                                            <td class="py-2.5">${{ number_format($variant->public_price, 2) }}</td>
                                                            <td class="py-2.5">${{ number_format($variant->wholesale_price, 2) }}</td>
                                                            <td class="py-2.5">
                                                                @if($variant->on_sale)
                                                                    <span class="px-2 py-0.5 bg-red-50 text-red-600 rounded-full font-bold">Sale: ${{ number_format($variant->sale_price, 2) }}</span>
                                                                @else
                                                                    <span class="text-slate-400">Regular</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @if($product->variants->isEmpty())
                                                        <tr>
                                                            <td colspan="5" class="py-4 text-center text-slate-400 italic">No variants added yet.</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if ($products->hasPages())
                            <div class="mt-6 pt-4 border-t border-slate-100">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicate / Copy Product Modal -->
    @if($showCopyModal)
        <div class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-scale-up" @click.outside="$wire.closeCopyModal()">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-amber-50 rounded-2xl text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Duplicate / Copy Product</h3>
                            <p class="text-xs text-slate-400 font-medium">Create a complete copy of this product</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeCopyModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Original Product Title (Info) -->
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Original Product</span>
                        <span class="text-sm font-bold text-slate-800">{{ $copyOriginalTitle }}</span>
                    </div>

                    <!-- New Product Title Input -->
                    <div>
                        <label class="text-xs font-bold text-slate-600 block mb-1 uppercase tracking-wider">New Product Title</label>
                        <input type="text" wire:model.live.debounce.300ms="copyProductTitle" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-medium text-sm">
                        @error('copyProductTitle') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- New SEO Slug Input -->
                    <div>
                        <label class="text-xs font-bold text-slate-600 block mb-1 uppercase tracking-wider">New SEO Slug</label>
                        <input type="text" wire:model.live.debounce.300ms="copyProductSlug" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-medium text-sm font-mono">
                        @error('copyProductSlug') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Duplicate Variants & Images Toggle -->
                    <div class="p-4 bg-amber-50/60 border border-amber-100 rounded-2xl">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="copyVariantsAndImages" class="w-4 h-4 mt-0.5 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="text-xs font-extrabold text-slate-800 block">Duplicate All Variants, Inventory &amp; Images</span>
                                <span class="text-[11px] text-slate-500 font-medium leading-relaxed block mt-0.5">When checked, all pricing variants, stock levels (with unique SKUs), and image sets will be duplicated for the new item.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="closeCopyModal" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition">
                        Cancel
                    </button>
                    <button type="button" wire:click="duplicateProduct" wire:loading.attr="disabled" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-extrabold rounded-2xl shadow-md transition flex items-center gap-2">
                        <svg wire:loading wire:target="duplicateProduct" class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Duplicate Product</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
