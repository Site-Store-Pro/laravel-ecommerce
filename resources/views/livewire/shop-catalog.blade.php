@php
    $imgOrientation = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $aspectClass    = $imgOrientation === '1:1' ? 'aspect-square' : 'aspect-video';
    $objectClass    = $imgOrientation === '1:1' ? 'object-contain' : 'object-cover';
    $listSizeClass  = $imgOrientation === '1:1' ? 'w-24 h-24' : 'w-28 h-24';
@endphp
<div class="pt-4 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session()->has('status'))
            <div class="mb-6 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 rounded-2xl border border-red-100 flex items-center gap-3 text-red-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-12">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">{{ $pageTitle }}</h1>
                @if($pageDescription)
                    <p class="mt-2 text-sm text-slate-500">{{ $pageDescription }}</p>
                @endif
            </div>
            <!-- Search Bar -->
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input wire:model.live="search" type="text" placeholder="Search products..." class="pl-10 pr-4 py-2.5 w-full bg-white border border-slate-200 text-slate-700 placeholder-slate-400 rounded-2xl shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition duration-150">
            </div>
        </div>

        @if($userType == 2)
            <div class="mb-8 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3">
                <span class="p-2 rounded-xl bg-emerald-500 text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <span class="text-sm font-bold text-emerald-800">Wholesale Account Active</span>
                    <p class="text-xs text-emerald-600">You are seeing wholesale pricing on eligible items.</p>
                </div>
            </div>
        @endif

        @if($activeCategory)
                <div class="mb-8 flex items-center gap-2 animate-fade-in">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Category:</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-150 text-xs font-bold text-indigo-700">
                        {{ $activeCategory->name }}
                        <button wire:click="clearCategory" class="hover:text-indigo-900 font-black text-xs ml-1 focus:outline-none">×</button>
                    </span>
                </div>
        @endif

        @if($activeBrand)
                <div class="mb-8 flex items-center gap-2 animate-fade-in">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Brand:</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-50 border border-violet-150 text-xs font-bold text-violet-700">
                        {{ $activeBrand->name }}
                        <button wire:click="clearBrand" class="hover:text-violet-900 font-black text-xs ml-1 focus:outline-none">×</button>
                    </span>
                </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════
             FILTER PANELS — only shown when there's >1 option
             ════════════════════════════════════════════════════════ --}}

        {{-- Category Drill-Down --}}
        @if($filterCategories->isNotEmpty())
            <div class="mb-6 bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        {{ $category ? 'Subcategories' : 'Browse by Category' }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach($filterCategories as $cat)
                            {{-- Root / current-level category --}}
                            <div class="flex flex-col gap-1.5">
                                <a href="{{ route('shop.index', array_filter(['category' => $cat->slug, 'brand' => $brand])) }}"
                                   wire:navigate
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 hover:border-indigo-300 text-indigo-700 text-xs font-bold rounded-xl transition duration-150 shadow-sm group">
                                    <svg class="w-3 h-3 opacity-60 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    {{ $cat->name }}
                                </a>

                                {{-- Children --}}
                                @if($cat->children->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5 pl-4 border-l-2 border-indigo-100">
                                        @foreach($cat->children as $child)
                                            <div class="flex flex-col gap-1">
                                                <a href="{{ route('shop.index', array_filter(['category' => $child->slug, 'brand' => $brand])) }}"
                                                   wire:navigate
                                                   class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 text-slate-600 hover:text-indigo-600 text-[11px] font-semibold rounded-lg transition duration-150">
                                                    <svg class="w-2.5 h-2.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                    {{ $child->name }}
                                                </a>

                                                {{-- Grandchildren --}}
                                                @if($child->children->isNotEmpty())
                                                    <div class="flex flex-wrap gap-1 pl-3 border-l border-slate-200">
                                                        @foreach($child->children as $grandchild)
                                                            <a href="{{ route('shop.index', array_filter(['category' => $grandchild->slug, 'brand' => $brand])) }}"
                                                               wire:navigate
                                                               class="inline-flex items-center gap-1 px-2 py-0.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-500 hover:text-indigo-500 text-[10px] font-medium rounded-md transition duration-150">
                                                                <span class="text-slate-300">↳</span>
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
            <div class="mb-6 bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Filter by Brand</span>
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
                               class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-violet-50 border border-slate-200 hover:border-violet-300 text-slate-700 hover:text-violet-700 text-xs font-semibold rounded-xl transition duration-150 shadow-sm group shrink-0">
                                @if($fbLogoUrl)
                                    <img src="{{ $fbLogoUrl }}" alt="{{ $fb->name }}" class="w-5 h-5 object-contain rounded">
                                @else
                                    <span class="w-5 h-5 rounded bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">{{ substr($fb->name, 0, 1) }}</span>
                                @endif
                                {{ $fb->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════
             RESULTS TOOLBAR — count · per-page · view toggle
             ════════════════════════════════════════════════════════ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            {{-- Left: result count --}}
            <p class="text-sm text-slate-500">
                @if($products->total() > 0)
                    Showing
                    <span class="font-semibold text-slate-800">{{ $products->firstItem() }}</span>–<span class="font-semibold text-slate-800">{{ $products->lastItem() }}</span>
                    of <span class="font-semibold text-slate-800">{{ $products->total() }}</span> products
                @else
                    No products found
                @endif
            </p>

            {{-- Right: per-page selector + grid/list toggle --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Per-page --}}
                @if($products->total() > 5)
                    <label class="text-xs font-semibold text-slate-400 whitespace-nowrap">Show</label>
                    <select wire:model.live="perPage"
                            class="pl-3 pr-8 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition duration-150 cursor-pointer">
                        @foreach([5, 10, 15, 25, 50, 75, 100] as $n)
                            <option value="{{ $n }}">{{ $n }}</option>
                        @endforeach
                    </select>
                    <span class="text-xs font-semibold text-slate-400">per page</span>

                    {{-- Divider --}}
                    <span class="w-px h-5 bg-slate-200 mx-1"></span>
                @endif

                {{-- Grid toggle --}}
                <button wire:click="$set('viewMode', 'grid')"
                        id="view-toggle-grid"
                        title="Grid view"
                        class="p-1.5 rounded-lg transition duration-150 {{ $viewMode === 'grid' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100' }}">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>

                {{-- List toggle --}}
                <button wire:click="$set('viewMode', 'list')"
                        id="view-toggle-list"
                        title="List view"
                        class="p-1.5 rounded-lg transition duration-150 {{ $viewMode === 'list' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100' }}">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 000 2h14a1 1 0 100-2H3zm0 4a1 1 0 000 2h14a1 1 0 100-2H3zm0 4a1 1 0 000 2h14a1 1 0 100-2H3zm0 4a1 1 0 000 2h14a1 1 0 100-2H3z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Products Grid --}}
        @if($products->isEmpty())
            <div class="text-center py-16 bg-white border border-slate-100 rounded-3xl shadow-sm">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-slate-900">No products found</h3>
                <p class="mt-1 text-sm text-slate-500">Try adjusting your search terms.</p>
            </div>

        {{-- ════════════════════════════════════════════
             GRID VIEW
             ════════════════════════════════════════════ --}}
        @elseif($viewMode === 'grid')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                    @php
                        $defaultVariant = $product->variants->first();
                        $priceToShow = 0;
                        $originalPrice = 0;
                        $hasVariantPricing = false;
                        $hasOptionalFees = false;
                        $minPrice = null;
                        $minOriginalPrice = null;

                        if ($product->variants->isNotEmpty()) {
                            $prices = [];
                            $variantPairs = [];
                            foreach ($product->variants as $variant) {
                                $orig = $userType == 2 ? $variant->wholesale_price : $variant->public_price;
                                $show = \App\Services\DiscountService::getDiscountedPriceForVariant($variant, auth()->user(), 1);
                                if ($vatInclusive && $merchantVatRate > 0) {
                                    $show = $show * (1 + $merchantVatRate / 100);
                                    $orig = $orig * (1 + $merchantVatRate / 100);
                                }
                                $prices[] = (float) $show;
                                $variantPairs[$variant->id] = [
                                    'priceToShow' => $show,
                                    'originalPrice' => $orig,
                                ];
                            }
                            $hasVariantPricing = count(array_unique($prices)) > 1;
                            $minPrice = min($prices);
                            foreach ($variantPairs as $vId => $pair) {
                                if (abs($pair['priceToShow'] - $minPrice) < 0.0001) {
                                    $minOriginalPrice = $pair['originalPrice'];
                                    break;
                                }
                            }
                            if ($defaultVariant) {
                                $originalPrice = $userType == 2 ? $defaultVariant->wholesale_price : $defaultVariant->public_price;
                                $priceToShow   = \App\Services\DiscountService::getDiscountedPriceForVariant($defaultVariant, auth()->user(), 1);
                                if ($vatInclusive && $merchantVatRate > 0) {
                                    $priceToShow   = $priceToShow   * (1 + $merchantVatRate / 100);
                                    $originalPrice = $originalPrice * (1 + $merchantVatRate / 100);
                                }
                            }
                        }

                        foreach ($product->fields as $field) {
                            foreach ($field->options as $opt) {
                                $modifier = $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                                if ($modifier > 0) {
                                    $hasOptionalFees = true;
                                    break 2;
                                }
                            }
                        }

                        $isFromPrice = $hasVariantPricing || $hasOptionalFees;
                        if ($isFromPrice && $minPrice !== null) {
                            $priceToShow = $minPrice;
                            $originalPrice = $minOriginalPrice;
                        }
                    @endphp
                    <div class="group bg-white border border-slate-100 rounded-3xl shadow-sm hover:shadow-md hover:border-slate-200 transition-all duration-300 flex flex-col overflow-hidden">
                        <a href="{{ route('shop.product', $product->seo_slug) }}" wire:navigate class="{{ $aspectClass }} bg-gradient-to-br from-indigo-50/50 to-violet-50/50 flex items-center justify-center relative overflow-hidden">
                            @if($defaultVariant && $defaultVariant->thumbnailImageUrl())
                                <img src="{{ $defaultVariant->thumbnailImageUrl() }}" alt="{{ $product->title }}"
                                     class="w-full h-full {{ $objectClass }}">
                            @else
                                <span class="p-4 rounded-full bg-white shadow-md text-indigo-600 group-hover:scale-110 transition-all duration-300 relative z-10">
                                    @if($defaultVariant && $defaultVariant->download_item)
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @else
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    @endif
                                </span>
                            @endif
                            @if($defaultVariant && $defaultVariant->on_sale && $userType != 2)
                                <span class="absolute top-4 right-4 px-2.5 py-1 text-xs font-bold text-red-600 bg-red-50 rounded-full border border-red-100">Sale</span>
                            @endif
                        </a>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                    <a href="{{ route('shop.product', $product->seo_slug) }}" wire:navigate>{{ $product->title }}</a>
                                </h3>
                                <p class="mt-2 text-sm text-slate-500 line-clamp-2">{!! $product->parsed_short_description !!}</p>
                            </div>
                            <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                                <div>
                                    @if($defaultVariant)
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-xl font-extrabold text-slate-900">{{ $isFromPrice ? 'From ' : '' }}{{ $currencySymbol }}{{ number_format($priceToShow, 2) }}</span>
                                            @if($priceToShow < $originalPrice)
                                                <span class="text-xs text-slate-400 line-through">{{ $currencySymbol }}{{ number_format($originalPrice, 2) }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400">Out of Stock</span>
                                    @endif
                                </div>
                                @if($product->variants->count() == 1)
                                    @php $v = $product->variants->first(); $avail = ($v->inventory ? $v->inventory->quantity_available - $v->inventory->reserved_stock : 0); @endphp
                                    @if(!$v->download_item && $avail <= 0)
                                        <button disabled class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed">Out of Stock</button>
                                    @else
                                        <button wire:click="buyNow({{ $v->id }})" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-sm">
                                            Buy Now <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('shop.product', $product->seo_slug) }}" wire:navigate class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition duration-150">
                                        View Options <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        {{-- ════════════════════════════════════════════
             LIST VIEW
             ════════════════════════════════════════════ --}}
        @else
            <div class="flex flex-col gap-3">
                @foreach($products as $product)
                    @php
                        $defaultVariant = $product->variants->first();
                        $priceToShow = 0;
                        $originalPrice = 0;
                        $hasVariantPricing = false;
                        $hasOptionalFees = false;
                        $minPrice = null;
                        $minOriginalPrice = null;

                        if ($product->variants->isNotEmpty()) {
                            $prices = [];
                            $variantPairs = [];
                            foreach ($product->variants as $variant) {
                                $orig = $userType == 2 ? $variant->wholesale_price : $variant->public_price;
                                $show = \App\Services\DiscountService::getDiscountedPriceForVariant($variant, auth()->user(), 1);
                                if ($vatInclusive && $merchantVatRate > 0) {
                                    $show = $show * (1 + $merchantVatRate / 100);
                                    $orig = $orig * (1 + $merchantVatRate / 100);
                                }
                                $prices[] = (float) $show;
                                $variantPairs[$variant->id] = [
                                    'priceToShow' => $show,
                                    'originalPrice' => $orig,
                                ];
                            }
                            $hasVariantPricing = count(array_unique($prices)) > 1;
                            $minPrice = min($prices);
                            foreach ($variantPairs as $vId => $pair) {
                                if (abs($pair['priceToShow'] - $minPrice) < 0.0001) {
                                    $minOriginalPrice = $pair['originalPrice'];
                                    break;
                                }
                            }
                            if ($defaultVariant) {
                                $originalPrice = $userType == 2 ? $defaultVariant->wholesale_price : $defaultVariant->public_price;
                                $priceToShow   = \App\Services\DiscountService::getDiscountedPriceForVariant($defaultVariant, auth()->user(), 1);
                                if ($vatInclusive && $merchantVatRate > 0) {
                                    $priceToShow   = $priceToShow   * (1 + $merchantVatRate / 100);
                                    $originalPrice = $originalPrice * (1 + $merchantVatRate / 100);
                                }
                            }
                        }

                        foreach ($product->fields as $field) {
                            foreach ($field->options as $opt) {
                                $modifier = $userType == 2 ? $opt->option_wholesale_price_modifier : $opt->option_price_modifier;
                                if ($modifier > 0) {
                                    $hasOptionalFees = true;
                                    break 2;
                                }
                            }
                        }

                        $isFromPrice = $hasVariantPricing || $hasOptionalFees;
                        if ($isFromPrice && $minPrice !== null) {
                            $priceToShow = $minPrice;
                            $originalPrice = $minOriginalPrice;
                        }
                    @endphp

                    <div class="group bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-slate-200 transition-all duration-200 flex items-center gap-0 overflow-hidden">

                        {{-- Thumbnail --}}
                        <a href="{{ route('shop.product', $product->seo_slug) }}" wire:navigate class="{{ $listSizeClass }} shrink-0 bg-gradient-to-br from-indigo-50/60 to-violet-50/60 flex items-center justify-center relative overflow-hidden">
                            @if($defaultVariant && $defaultVariant->thumbnailImageUrl())
                                <img src="{{ $defaultVariant->thumbnailImageUrl() }}" alt="{{ $product->title }}"
                                     class="w-full h-full {{ $objectClass }}">
                            @else
                                <span class="text-indigo-400 group-hover:scale-110 transition-all duration-300">
                                    @if($defaultVariant && $defaultVariant->download_item)
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @else
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    @endif
                                </span>
                            @endif
                            @if($defaultVariant && $defaultVariant->on_sale && $userType != 2)
                                <span class="absolute top-2 left-2 px-1.5 py-0.5 text-[10px] font-bold text-red-600 bg-red-50 rounded-full border border-red-100">Sale</span>
                            @endif
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0 px-5 py-4">
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">
                                <a href="{{ route('shop.product', $product->seo_slug) }}" wire:navigate>{{ $product->title }}</a>
                            </h3>
                            <p class="mt-1 text-xs text-slate-400 line-clamp-2 leading-relaxed">{!! $product->parsed_short_description !!}</p>
                            @if($product->variants->count() > 1)
                                <p class="mt-1.5 text-[11px] text-indigo-500 font-semibold">{{ $product->variants->count() }} variants available</p>
                            @endif
                        </div>

                        {{-- Price + Action --}}
                        <div class="shrink-0 flex items-center gap-4 pr-5">
                            <div class="text-right">
                                @if($defaultVariant)
                                    <div class="text-base font-extrabold text-slate-900">{{ $isFromPrice ? 'From ' : '' }}{{ $currencySymbol }}{{ number_format($priceToShow, 2) }}</div>
                                    @if($priceToShow < $originalPrice)
                                        <div class="text-xs text-slate-400 line-through">{{ $currencySymbol }}{{ number_format($originalPrice, 2) }}</div>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400">Out of Stock</span>
                                @endif
                            </div>

                            @if($product->variants->count() == 1)
                                @php $v = $product->variants->first(); $avail = ($v->inventory ? $v->inventory->quantity_available - $v->inventory->reserved_stock : 0); @endphp
                                @if(!$v->download_item && $avail <= 0)
                                    <button disabled class="px-4 py-2 text-xs font-bold text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed whitespace-nowrap">Out of Stock</button>
                                @else
                                    <button wire:click="buyNow({{ $v->id }})" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-sm whitespace-nowrap">
                                        Add to Cart
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('shop.product', $product->seo_slug) }}" wire:navigate
                                   class="px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition duration-150 whitespace-nowrap">
                                    View Options
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="mt-10 flex flex-col items-center gap-3">
                {{-- Links --}}
                <nav class="flex items-center gap-1.5 flex-wrap justify-center">
                    {{-- Previous --}}
                    @if($products->onFirstPage())
                        <span class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed">← Prev</span>
                    @else
                        <button wire:click="previousPage" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition duration-150 shadow-sm">← Prev</button>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                        @if($page == $products->currentPage())
                            <span class="px-3 py-1.5 text-xs font-bold text-white bg-indigo-600 border border-indigo-600 rounded-xl shadow-sm">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition duration-150 shadow-sm">{{ $page }}</button>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($products->hasMorePages())
                        <button wire:click="nextPage" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition duration-150 shadow-sm">Next →</button>
                    @else
                        <span class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed">Next →</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>

</div>
