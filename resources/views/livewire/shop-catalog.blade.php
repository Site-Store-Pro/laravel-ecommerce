@php
    $imgOrientation = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $aspectClass    = $imgOrientation === '1:1' ? 'aspect-square' : 'aspect-video';
    $objectClass    = $imgOrientation === '1:1' ? 'object-contain' : 'object-cover';
    $listSizeClass  = $imgOrientation === '1:1' ? 'w-24 h-24' : 'w-28 h-24';
@endphp
<div x-data="{ slideoutOpen: @entangle('slideoutOpen') }" class="pt-4 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session()->has('status'))
            <div class="mb-6 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif
        {{-- FLOATING CATALOG ERROR MODAL --}}
        <div x-data="{
                showErrorModal: {{ \Illuminate\Support\Js::from((bool)(session()->has('error') || !empty($catalogError))) }},
                errorMessage: {{ \Illuminate\Support\Js::from((string)(session('error') ?? $catalogError ?? '')) }},
                open(msg) {
                    this.errorMessage = msg || this.errorMessage;
                    this.showErrorModal = true;
                    document.body.style.overflow = 'hidden';
                },
                close() {
                    this.showErrorModal = false;
                    document.body.style.overflow = '';
                }
             }"
             x-init="if (showErrorModal) document.body.style.overflow = 'hidden'"
             x-on:show-catalog-error.window="open($event.detail.message)"
             x-show="showErrorModal"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-[99999] flex items-center justify-center p-4"
             @keydown.escape.window="close()">
            
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                 @click="close()"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"></div>

            {{-- Modal Card --}}
            <div class="relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-2xl max-w-md w-full text-center space-y-6 z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2">
                
                {{-- Close button (top right) --}}
                <button type="button" @click="close()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700/60 transition-all focus:outline-none" aria-label="Close error message">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                {{-- Icon --}}
                <div class="inline-flex items-center justify-center p-3.5 rounded-full bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/40">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                {{-- Heading & Message --}}
                <div class="space-y-3">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">@label('catalog.notice', 'Notice')</h3>
                    <p class="text-sm font-semibold text-rose-700 dark:text-rose-300 p-4 bg-rose-50 dark:bg-rose-950/40 rounded-2xl border border-rose-100 dark:border-rose-900/60 leading-relaxed text-center" x-text="errorMessage"></p>
                </div>

                {{-- Action Button --}}
                <div class="pt-2">
                    <button type="button" @click="close()" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-bold rounded-xl shadow-md transition duration-150">
                        @label('catalog.dismiss', 'Dismiss')
                    </button>
                </div>
            </div>
        </div>
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-12">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $pageTitle }}</h1>
                @if($pageDescription)
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $pageDescription }}</p>
                @endif
            </div>

            <!-- Filter Controls (Top Right) -->
            <div class="flex items-center justify-end gap-3 w-full md:w-auto">
                @if($advancedSearchEnabled)
                    <button @click="slideoutOpen = true" type="button" class="relative inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-500/20 hover:scale-105 transition-all shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>@label('catalog.advanced_filters', 'Advanced Filters')</span>
                        @if($activeFilterCount > 0)
                            <span class="ml-1 px-2 py-0.5 rounded-full bg-white text-indigo-700 text-[10px] font-black shadow-sm">
                                {{ $activeFilterCount }}
                            </span>
                        @endif
                    </button>
                @endif
            </div>
        </div>

        @if($userType == 2)
            <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl border border-emerald-100 dark:border-emerald-900/60 flex items-center gap-3">
                <span class="p-2 rounded-xl bg-emerald-500 text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <span class="text-sm font-bold text-emerald-800 dark:text-emerald-300">@label('catalog.wholesale_active', 'Wholesale Account Active')</span>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400">@label('catalog.wholesale_message', 'You are seeing wholesale pricing on eligible items.')</p>
                </div>
            </div>
        @endif

        {{-- Active Filters Bar --}}
        @if($this->hasActiveFilters)
            <div class="mb-8 p-4 bg-slate-50 dark:bg-slate-800/80 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 flex items-center justify-between flex-wrap gap-3 animate-fade-in shadow-xs">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">@label('catalog.active_filters', 'Active Filters:')</span>
                    
                    @if($activeCategory && !in_array((string)$activeCategory->id, array_map('strval', $selectedCategories), true))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-950/80 border border-indigo-200 dark:border-indigo-800 text-xs font-bold text-indigo-700 dark:text-indigo-300">
                            @label('catalog.filter_category', 'Category:') {{ $activeCategory->name }}
                            <button wire:click="clearCategory" type="button" class="hover:text-rose-600 dark:hover:text-rose-400 font-black text-sm ml-1 focus:outline-none" title="Remove filter">&times;</button>
                        </span>
                    @endif

                    @if($activeBrand && !in_array((string)$activeBrand->id, array_map('strval', $selectedBrands), true))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-100 dark:bg-violet-950/80 border border-violet-200 dark:border-violet-800 text-xs font-bold text-violet-700 dark:text-violet-300">
                            @label('catalog.filter_brand', 'Brand:') {{ $activeBrand->name }}
                            <button wire:click="clearBrand" type="button" class="hover:text-rose-600 dark:hover:text-rose-400 font-black text-sm ml-1 focus:outline-none" title="Remove filter">&times;</button>
                        </span>
                    @endif

                    @if(!empty(trim($search)))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950/80 border border-amber-200 dark:border-amber-800 text-xs font-bold text-amber-800 dark:text-amber-200">
                            @label('catalog.filter_keyword', 'Keyword:') &ldquo;{{ $search }}&rdquo;
                            <button wire:click="clearSearch" type="button" class="hover:text-rose-600 dark:hover:text-rose-400 font-black text-sm ml-1 focus:outline-none" title="Remove filter">&times;</button>
                        </span>
                    @endif

                    @if($minPriceFilter !== null || $maxPriceFilter !== null)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/80 border border-emerald-200 dark:border-emerald-800 text-xs font-bold text-emerald-800 dark:text-emerald-200">
                            @label('catalog.filter_price', 'Price:') {{ $currencySymbol }}{{ number_format($minPriceFilter ?? 0, 2) }} – {{ $currencySymbol }}{{ number_format($maxPriceFilter ?? $catalogMaxPrice, 2) }}
                            <button wire:click="clearPriceFilter" type="button" class="hover:text-rose-600 dark:hover:text-rose-400 font-black text-sm ml-1 focus:outline-none" title="Remove filter">&times;</button>
                        </span>
                    @endif

                    @if(!empty($selectedCategories))
                        @foreach($selectedCategories as $scId)
                            @php $sc = $selectedCategoryModels->get((int)$scId) ?? $selectedCategoryModels->get((string)$scId); @endphp
                            @if($sc)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-950/80 border border-indigo-200 dark:border-indigo-800 text-xs font-bold text-indigo-800 dark:text-indigo-200">
                                    @label('catalog.filter_category', 'Category:') {{ $sc->name }}
                                    <button wire:click="removeSelectedCategory({{ $sc->id }})" type="button" class="hover:text-rose-600 dark:hover:text-rose-400 font-black text-sm ml-1 focus:outline-none" title="Remove filter">&times;</button>
                                </span>
                            @endif
                        @endforeach
                    @endif

                    @if(!empty($selectedBrands))
                        @foreach($selectedBrands as $sbId)
                            @php $sb = $allAvailableBrands->firstWhere('id', (int)$sbId); @endphp
                            @if($sb)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-100 dark:bg-sky-950/80 border border-sky-200 dark:border-sky-800 text-xs font-bold text-sky-800 dark:text-sky-200">
                                    @label('catalog.filter_brand', 'Brand:') {{ $sb->name }}
                                    <button wire:click="removeSelectedBrand({{ $sb->id }})" type="button" class="hover:text-rose-600 dark:hover:text-rose-400 font-black text-sm ml-1 focus:outline-none" title="Remove filter">&times;</button>
                                </span>
                            @endif
                        @endforeach
                    @endif

                    @if(!empty($selectedAttributes))
                        @foreach($selectedAttributes as $attrKey => $attrVals)
                            @if(is_array($attrVals))
                                @foreach($attrVals as $attrVal)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-950/80 border border-purple-200 dark:border-purple-800 text-xs font-bold text-purple-800 dark:text-purple-200">
                                        {{ $attrKey }}: {{ $attrVal }}
                                        <button wire:click="removeSelectedAttribute('{{ addslashes($attrKey) }}', '{{ addslashes($attrVal) }}')" type="button" class="hover:text-rose-600 dark:hover:text-rose-400 font-black text-sm ml-1 focus:outline-none" title="Remove filter">&times;</button>
                                    </span>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </div>

                <button wire:click="resetAllAdvancedFilters" type="button" class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:underline">
                    @label('catalog.clear_all', 'Clear All')
                </button>
            </div>
        @endif

        {{-- Category Drill-Down --}}
        @if($filterCategories->isNotEmpty())
            <div class="mb-6 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-50 dark:border-slate-700/60 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        @if($category) @label('catalog.subcategories', 'Subcategories') @else @label('catalog.browse_by_category', 'Browse by Category') @endif
                    </span>
                </div>
                <div class="p-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach($filterCategories as $cat)
                            <div class="flex flex-col gap-1.5">
                                <a href="{{ route('shop.index', array_filter(['category' => $cat->slug, 'brand' => $brand])) }}"
                                   wire:navigate
                                   class="shop-category-pill inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl transition duration-150 shadow-sm group">
                                    <svg class="w-3 h-3 opacity-60 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    {{ $cat->name }}
                                </a>
                                @if($cat->children->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5 pl-4 border-l-2 border-indigo-100 dark:border-indigo-900">
                                        @foreach($cat->children as $child)
                                            <div class="flex flex-col gap-1">
                                                <a href="{{ route('shop.index', array_filter(['category' => $child->slug, 'brand' => $brand])) }}"
                                                   wire:navigate
                                                   class="shop-subcat-pill inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold rounded-lg transition duration-150">
                                                    <svg class="w-2.5 h-2.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                    {{ $child->name }}
                                                </a>
                                                @if($child->children->isNotEmpty())
                                                    <div class="flex flex-wrap gap-1 pl-3 border-l border-slate-200 dark:border-slate-700">
                                                        @foreach($child->children as $grandchild)
                                                            <a href="{{ route('shop.index', array_filter(['category' => $grandchild->slug, 'brand' => $brand])) }}"
                                                               wire:navigate
                                                               class="shop-subcat-pill inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-medium rounded-md transition duration-150">
                                                                <span class="text-slate-300 dark:text-slate-600">↳</span>
                                                                {{ $grandchild->name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Brand Filter Strip --}}
        @if($filterBrands->count() > 1)
            <div class="mb-6 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-50 dark:border-slate-700/60 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">@label('catalog.filter_by_brand', 'Filter by Brand')</span>
                </div>
                <div class="px-4 py-3 overflow-x-auto">
                    <div class="flex items-center gap-2 flex-wrap">
                        @foreach($filterBrands as $fb)
                            @php
                                $fbLogoUrl = $fb->brand_icon
                                    ? ($fb->brand_logo_s3
                                        ? \Illuminate\Support\Facades\Storage::disk('s3')->url($fb->brand_icon)
                                        : \Illuminate\Support\Facades\Storage::disk('public')->url($fb->brand_icon))
                                    : null;
                            @endphp
                            <a href="{{ route('shop.index', array_filter(['category' => $category, 'brand' => $fb->slug])) }}"
                               wire:navigate
                               class="shop-brand-pill inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold rounded-xl transition duration-150 shadow-sm group shrink-0">
                                @if($fbLogoUrl)
                                    <img src="{{ $fbLogoUrl }}" alt="{{ $fb->name }}" class="w-5 h-5 object-contain rounded">
                                @endif
                                {{ $fb->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- RESULTS TOOLBAR --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                @if($products->total() > 0)
                    @label('catalog.showing', 'Showing')
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $products->firstItem() }}</span>–<span class="font-semibold text-slate-800 dark:text-slate-200">{{ $products->lastItem() }}</span>
                    @label('catalog.of', 'of') <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $products->total() }}</span> @label('catalog.products', 'products')
                @else
                    @label('catalog.no_products_found', 'No products found')
                @endif
            </p>

            <div class="flex items-center gap-3 shrink-0">
                <div class="flex items-center gap-1.5">
                    <label class="text-xs font-semibold text-slate-400 whitespace-nowrap">@label('catalog.sort', 'Sort')</label>
                    <select wire:model.live="sort"
                            class="py-1 px-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm transition duration-150">
                        <option value="price_asc">@label('catalog.sort_price_low', 'Price Low-High')</option>
                        <option value="price_desc">@label('catalog.sort_price_high', 'Price High-Low')</option>
                        <option value="title_asc">@label('catalog.sort_title_asc', 'Title A-Z (ASC)')</option>
                        <option value="title_desc">@label('catalog.sort_title_desc', 'Title Z-A (DESC)')</option>
                        <option value="rating_desc">@label('catalog.sort_rating_high', 'Highest To Lowest Rated')</option>
                        <option value="rating_asc">@label('catalog.sort_rating_low', 'Lowest To Highest Rated')</option>
                    </select>
                </div>

                @if($products->total() > 5)
                    <div class="flex items-center gap-1.5">
                        <label class="text-xs font-semibold text-slate-400 whitespace-nowrap">@label('catalog.show', 'Show')</label>
                        <select wire:model.live="perPage"
                                class="py-1 px-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm transition duration-150">
                            @foreach([5, 10, 15, 25, 50, 75, 100] as $num)
                                <option value="{{ $num }}">{{ $num }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex items-center gap-1.5">
                    <button wire:click="$set('viewMode', 'grid')"
                            class="btn-view-mode !p-2 !rounded-xl text-xs font-bold transition {{ $viewMode === 'grid' ? 'active' : '' }}"
                            title="Grid View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </button>
                    <button wire:click="$set('viewMode', 'list')"
                            class="btn-view-mode !p-2 !rounded-xl text-xs font-bold transition {{ $viewMode === 'list' ? 'active' : '' }}"
                            title="List View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- PRODUCTS CATALOG DISPLAY --}}
        @if($products->isEmpty())
            <div class="text-center py-24 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm px-4">
                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-950/50 rounded-2xl flex items-center justify-center mx-auto text-indigo-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">@label('catalog.no_match_heading', 'No products match your filter criteria')</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 max-w-sm mx-auto">@label('catalog.no_match_message', 'Try clearing your active category, brand, search filters, or price slider.')</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    @if($activeCategory)
                        <button wire:click="clearCategory" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold hover:bg-indigo-100 transition">@label('catalog.clear_category', 'Clear Category')</button>
                    @endif
                    @if($activeBrand)
                        <button wire:click="clearBrand" class="px-4 py-2 bg-violet-50 text-violet-700 rounded-xl text-xs font-bold hover:bg-violet-100 transition">@label('catalog.clear_brand', 'Clear Brand')</button>
                    @endif
                    @if($activeFilterCount > 0)
                        <button wire:click="resetAllAdvancedFilters" class="px-4 py-2 bg-rose-50 text-rose-700 rounded-xl text-xs font-bold hover:bg-rose-100 transition">@label('catalog.reset_filters', 'Reset All Filters')</button>
                    @endif
                </div>
            </div>
        @else
            @if($viewMode === 'grid')
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        @php
                            $firstVariant = $product->variants->first();
                            $inStock = $firstVariant ? $firstVariant->getStockForFulfillment(auth()->user()?->shipping_countrycode, auth()->user()?->shipping_state) > 0 : false;
                            $thumbUrl = $firstVariant ? $firstVariant->thumbnailImageUrl() : null;
                        @endphp
                        <div class="group bg-white dark:bg-slate-800 rounded-3xl border border-slate-150 dark:border-slate-700/60 overflow-hidden hover:shadow-xl hover:shadow-indigo-500/5 hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                            <div>
                                <a href="{{ route('shop.product', $product->seo_slug) }}" class="block relative overflow-hidden bg-slate-50 dark:bg-slate-900/50 {{ $aspectClass }}">
                                    @if($thumbUrl)
                                        <img src="{{ $thumbUrl }}" alt="{{ $product->title }}" class="w-full h-full {{ $objectClass }} group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                    @if($firstVariant && $firstVariant->on_sale && $userType == 1)
                                        <span class="absolute top-3 left-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full shadow-md">@label('catalog.sale', 'Sale')</span>
                                    @endif
                                </a>
                                <div class="p-5">
                                    @if($product->brand)
                                        <a href="{{ route('shop.brand', $product->brand->slug) }}" class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 hover:underline mb-1 block">{{ $product->brand->name }}</a>
                                    @endif
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition line-clamp-2">
                                        <a href="{{ route('shop.product', $product->seo_slug) }}">{{ $product->title }}</a>
                                    </h3>
                                    @if($product->short_description)
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 line-clamp-2">{{ strip_tags($product->short_description) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="p-5 pt-0 border-t border-slate-50 dark:border-slate-700/60 mt-auto flex items-center justify-between gap-3">
                                <div>
                                    @if(!$product->is_donation_or_bill_pay && $firstVariant)
                                        @php
                                            $price = $userType == 2 ? $firstVariant->wholesale_price : $firstVariant->public_price;
                                            $onSale = $userType == 1 && $firstVariant->on_sale && $firstVariant->sale_price > 0;
                                        @endphp
                                        @if($onSale)
                                            <div class="flex items-baseline gap-1.5">
                                                <span class="text-lg font-extrabold text-rose-600 dark:text-rose-400">{{ $currencySymbol }}{{ number_format($firstVariant->sale_price, 2) }}</span>
                                                <span class="text-xs text-slate-400 line-through font-semibold">{{ $currencySymbol }}{{ number_format($price, 2) }}</span>
                                            </div>
                                        @else
                                            <span class="text-lg font-extrabold text-slate-900 dark:text-white">{{ $currencySymbol }}{{ number_format($price, 2) }}</span>
                                        @endif
                                    @endif
                                </div>
                                @if($product->is_donation_or_bill_pay)
                                    <a href="{{ route('shop.product', $product->seo_slug) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 hover:scale-105 transition-all">@label('catalog.select_options', 'Select Options')</a>
                                @elseif($firstVariant && $inStock)
                                    <button wire:click="buyNow({{ $firstVariant->id }})" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 hover:scale-105 transition-all">@label('catalog.buy_now', 'Buy Now')</button>
                                @else
                                    <span class="text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-lg">@label('catalog.out_of_stock', 'Out of Stock')</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- List view --}}
                <div class="space-y-4">
                    @foreach($products as $product)
                        @php
                            $firstVariant = $product->variants->first();
                            $inStock = $firstVariant ? $firstVariant->getStockForFulfillment(auth()->user()?->shipping_countrycode, auth()->user()?->shipping_state) > 0 : false;
                            $thumbUrl = $firstVariant ? $firstVariant->thumbnailImageUrl() : null;
                        @endphp
                        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-150 dark:border-slate-700/60 p-4 sm:p-5 flex flex-col sm:flex-row items-center gap-6 hover:shadow-lg transition">
                            <a href="{{ route('shop.product', $product->seo_slug) }}" class="shrink-0 rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-900 {{ $listSizeClass }}">
                                @if($thumbUrl)
                                    <img src="{{ $thumbUrl }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </a>
                            <div class="flex-1 min-w-0 text-center sm:text-left">
                                @if($product->brand)
                                    <a href="{{ route('shop.brand', $product->brand->slug) }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline uppercase tracking-wider block mb-1">{{ $product->brand->name }}</a>
                                @endif
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white hover:text-indigo-600 transition truncate">
                                    <a href="{{ route('shop.product', $product->seo_slug) }}">{{ $product->title }}</a>
                                </h3>
                                @if($product->short_description)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{{ strip_tags($product->short_description) }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 shrink-0">
                                @if(!$product->is_donation_or_bill_pay)
                                    <div class="text-right">
                                        @if($firstVariant)
                                            @php
                                                $price = $userType == 2 ? $firstVariant->wholesale_price : $firstVariant->public_price;
                                                $onSale = $userType == 1 && $firstVariant->on_sale && $firstVariant->sale_price > 0;
                                            @endphp
                                            @if($onSale)
                                                <span class="block text-lg font-extrabold text-rose-600 dark:text-rose-400">{{ $currencySymbol }}{{ number_format($firstVariant->sale_price, 2) }}</span>
                                                <span class="block text-xs text-slate-400 line-through font-semibold">{{ $currencySymbol }}{{ number_format($price, 2) }}</span>
                                            @else
                                                <span class="block text-lg font-extrabold text-slate-900 dark:text-white">{{ $currencySymbol }}{{ number_format($price, 2) }}</span>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                                @if($product->is_donation_or_bill_pay)
                                    <a href="{{ route('shop.product', $product->seo_slug) }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 hover:scale-105 transition-all">@label('catalog.select_options', 'Select Options')</a>
                                @elseif($firstVariant && $inStock)
                                    <button wire:click="buyNow({{ $firstVariant->id }})" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 hover:scale-105 transition-all">@label('catalog.buy_now', 'Buy Now')</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- ADVANCED SEARCH FILTER SLIDEOUT DRAWER --}}
    @if($advancedSearchEnabled)
        <!-- Backdrop Overlay -->
        <div x-show="slideoutOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="slideoutOpen = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100000]"
             style="display: none;"></div>

        <!-- Slideout Drawer Panel -->
        <div x-show="slideoutOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 max-w-md w-full bg-white dark:bg-slate-900 shadow-2xl z-[100001] flex flex-col justify-between overflow-hidden"
             style="display: none;">

            <!-- Drawer Header (Filtered Items Count Bubble + Close Button) -->
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between border-b border-slate-800">
                <span class="px-3 py-1 rounded-full bg-indigo-600/90 text-white text-xs font-extrabold shadow-sm border border-indigo-400/30">
                    {{ number_format($products->total()) }} {{ Str::plural('Product', $products->total()) }}
                </span>
                <button @click="slideoutOpen = false" type="button" class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Drawer Body (Scrollable Controls) -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 scrollbar-thin">

                <!-- 1. Price Range Slider -->
                <div class="p-5 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-200">@label('catalog.price_range', 'Price Range Filter')</h4>
                        <span class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $currencySymbol }}{{ number_format($minPriceFilter ?? 0, 2) }} – {{ $currencySymbol }}{{ number_format($maxPriceFilter ?? $catalogMaxPrice, 2) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">@label('catalog.min_price', 'Min Price') ({{ $currencySymbol }})</label>
                            <input type="number" min="0" max="{{ $catalogMaxPrice }}" step="1" wire:model.live.debounce.300ms="minPriceFilter" class="w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded-xl font-bold focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">@label('catalog.max_price', 'Max Price') ({{ $currencySymbol }})</label>
                            <input type="number" min="0" max="{{ $catalogMaxPrice }}" step="1" wire:model.live.debounce.300ms="maxPriceFilter" class="w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded-xl font-bold focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="space-y-1 pt-1">
                        <div class="flex items-center justify-between text-[10px] font-semibold text-slate-400">
                            <span>{{ $currencySymbol }}0</span>
                            <span>{{ $currencySymbol }}{{ number_format($catalogMaxPrice, 2) }}</span>
                        </div>
                        <input type="range" min="0" max="{{ $catalogMaxPrice }}" step="1" wire:model.live.debounce.150ms="maxPriceFilter" class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                    </div>
                </div>

                <!-- 2. Categories Checkbox Group -->
                @if(!empty($allAvailableCategories) && $allAvailableCategories->isNotEmpty())
                    <div x-data="{ open: true }" class="border border-slate-200 dark:border-slate-700/60 rounded-2xl p-4 bg-white dark:bg-slate-800">
                        <button @click="open = !open" type="button" class="w-full flex items-center justify-between text-left">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-100 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                @label('catalog.filter_by_categories', 'Filter by Categories')
                            </span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-3 space-y-2.5 max-h-64 overflow-y-auto pr-1">
                            @foreach($allAvailableCategories as $rootCat)
                                <div class="space-y-1">
                                    <!-- Root Category -->
                                    <label class="flex items-center gap-2 text-xs font-bold text-slate-800 dark:text-slate-100 cursor-pointer hover:text-indigo-600 transition">
                                        <input type="checkbox" value="{{ $rootCat->id }}" wire:model.live="selectedCategories" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                        <span>{{ $rootCat->name }}</span>
                                    </label>

                                    <!-- Level 2 Subcategories -->
                                    @if($rootCat->children && $rootCat->children->isNotEmpty())
                                        <div class="ml-4 pl-2.5 border-l-2 border-indigo-100 dark:border-slate-700 space-y-1.5 mt-1 my-1">
                                            @foreach($rootCat->children as $childCat)
                                                <div>
                                                    <label class="flex items-center gap-2 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer hover:text-indigo-600 transition">
                                                        <span class="text-slate-300 dark:text-slate-600 font-normal">&ndash;</span>
                                                        <input type="checkbox" value="{{ $childCat->id }}" wire:model.live="selectedCategories" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-3.5 w-3.5">
                                                        <span>{{ $childCat->name }}</span>
                                                    </label>

                                                    <!-- Level 3 Sub-subcategories -->
                                                    @if($childCat->children && $childCat->children->isNotEmpty())
                                                        <div class="ml-4 pl-2 border-l border-slate-200/80 dark:border-slate-700/80 space-y-1 mt-1">
                                                            @foreach($childCat->children as $gChild)
                                                                <label class="flex items-center gap-2 text-[11px] font-normal text-slate-600 dark:text-slate-400 cursor-pointer hover:text-indigo-600 transition">
                                                                    <span class="text-slate-300 dark:text-slate-600 font-normal">&bull;</span>
                                                                    <input type="checkbox" value="{{ $gChild->id }}" wire:model.live="selectedCategories" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-3 w-3">
                                                                    <span>{{ $gChild->name }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 3. Brands Checkbox Group -->
                @if($allAvailableBrands->isNotEmpty())
                    <div x-data="{ open: true }" class="border border-slate-200 dark:border-slate-700/60 rounded-2xl p-4 bg-white dark:bg-slate-800">
                        <button @click="open = !open" type="button" class="w-full flex items-center justify-between text-left">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-100 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                @label('catalog.filter_by_brands', 'Filter by Brands')
                            </span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-3 space-y-2 max-h-48 overflow-y-auto pr-1">
                            @foreach($allAvailableBrands as $b)
                                <label class="flex items-center gap-2.5 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer hover:text-indigo-600 transition">
                                    <input type="checkbox" value="{{ $b->id }}" wire:model.live="selectedBrands" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <span>{{ $b->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 3. Dynamic Variant JSON Attributes Checkbox Groups -->
                @if(!empty($availableVariantAttributes))
                    @foreach($availableVariantAttributes as $attrKey => $attrValues)
                        <div x-data="{ open: true }" class="border border-slate-200 dark:border-slate-700/60 rounded-2xl p-4 bg-white dark:bg-slate-800">
                            <button @click="open = !open" type="button" class="w-full flex items-center justify-between text-left">
                                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-100 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                                    {{ $attrKey }}
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-3 grid grid-cols-2 gap-2 max-h-48 overflow-y-auto pr-1">
                                @foreach($attrValues as $val)
                                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer hover:text-indigo-600 transition">
                                        <input type="checkbox" value="{{ $val }}" wire:model.live="selectedAttributes.{{ $attrKey }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                        <span>{{ $val }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>

            <!-- Drawer Footer (Reset & Apply) -->
            <div class="p-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
                <button wire:click="resetAllAdvancedFilters" type="button" class="px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold text-xs hover:bg-rose-100 hover:text-rose-700 transition">
                    @label('catalog.reset_filters', 'Reset All Filters')
                </button>
                <button @click="slideoutOpen = false" type="button" class="flex-1 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-500/20 transition flex items-center justify-center gap-1.5">
                    <span>@label('catalog.apply_view', 'Apply & View')</span>
                    <span class="px-2 py-0.5 rounded-full bg-white/20 text-white text-[10px] font-black">
                        {{ number_format($products->total()) }}
                    </span>
                </button>
            </div>

        </div>
    @endif
</div>
