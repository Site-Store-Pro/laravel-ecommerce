{{--
    Featured Items Widget — List View (Livewire-aware version)
    Used by FeaturedItemsWidget Livewire component.
--}}
@php
    use App\Services\DiscountService;
    $user = auth()->user();
    $imgOrientation = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $objectClass    = $imgOrientation === '1:1' ? 'object-contain' : 'object-cover';
    $listSizeClass  = $imgOrientation === '1:1' ? 'w-24 h-24' : 'w-28 h-24';
@endphp

<div class="featured-items-plugin-section py-8">

    {{-- Section Header --}}
    @if(!empty($header))
        <div class="featured-items-header mb-8">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                {{ $header }}
            </h2>
            <div class="mt-2 w-16 h-1 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500"></div>
        </div>
    @endif

    {{-- Product List --}}
    <div class="flex flex-col gap-3">
        @foreach($products as $product)
            @php
                $defaultVariant = $product->variants->first();
                $priceToShow    = 0;
                $originalPrice  = 0;
                $isFromPrice    = false;

                if ($defaultVariant) {
                    $originalPrice = $defaultVariant->public_price ?? 0;
                    $priceToShow   = DiscountService::getDiscountedPriceForVariant($defaultVariant, $user, 1);
                }

                $hasVariantPricing = $product->variants->count() > 1
                    && $product->variants->pluck('public_price')->unique()->count() > 1;
                $isFromPrice = $hasVariantPricing;
            @endphp

            <div class="group bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-slate-200 transition-all duration-200 flex items-center gap-0 overflow-hidden">

                {{-- Thumbnail --}}
                <a href="{{ route('shop.product', $product->seo_slug) }}"
                   class="{{ $listSizeClass }} shrink-0 bg-gradient-to-br from-indigo-50/60 to-violet-50/60 flex items-center justify-center relative overflow-hidden">
                    @if($defaultVariant && $defaultVariant->thumbnailImageUrl())
                        <img src="{{ $defaultVariant->thumbnailImageUrl() }}"
                             alt="{{ $product->title }}"
                             class="w-full h-full {{ $objectClass }} group-hover:scale-105 transition-transform duration-500">
                    @else
                        <span class="text-indigo-400 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </span>
                    @endif
                    <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 text-[9px] font-bold text-white bg-amber-400 rounded-full">★</span>
                    @if($defaultVariant && $defaultVariant->on_sale)
                        <span class="absolute top-1.5 right-1.5 px-1.5 py-0.5 text-[10px] font-bold text-red-600 bg-red-50 rounded-full border border-red-100">Sale</span>
                    @endif
                </a>

                {{-- Info --}}
                <div class="flex-1 min-w-0 px-5 py-4">
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">
                        <a href="{{ route('shop.product', $product->seo_slug) }}">{{ $product->title }}</a>
                    </h3>
                    @if($product->short_description)
                        <p class="mt-1 text-xs text-slate-400 line-clamp-2 leading-relaxed">
                            {{ strip_tags($product->short_description) }}
                        </p>
                    @endif
                    @if($product->variants->count() > 1)
                        <p class="mt-1.5 text-[11px] text-indigo-500 font-semibold">{{ $product->variants->count() }} variants available</p>
                    @endif
                </div>

                {{-- Price + Action --}}
                <div class="shrink-0 flex items-center gap-4 pr-5">
                    <div class="text-right">
                        @if($defaultVariant)
                            <div class="text-base font-extrabold text-slate-900">
                                {{ $isFromPrice ? 'From ' : '' }}${{ number_format($priceToShow, 2) }}
                            </div>
                            @if($priceToShow < $originalPrice)
                                <div class="text-xs text-slate-400 line-through">${{ number_format($originalPrice, 2) }}</div>
                            @endif
                        @else
                            <span class="text-xs text-slate-400">Out of Stock</span>
                        @endif
                    </div>

                    @if($product->variants->count() === 1)
                        @php
                            $v     = $product->variants->first();
                            $avail = ($v->inventory
                                ? $v->inventory->quantity_available - $v->inventory->reserved_stock
                                : 999);
                        @endphp
                        @if(!$v->download_item && $avail <= 0)
                            <button disabled class="px-4 py-2 text-xs font-bold text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed whitespace-nowrap">
                                Out of Stock
                            </button>
                        @else
                            <button wire:click="buyNow({{ $v->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="buyNow({{ $v->id }})"
                                    class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-sm whitespace-nowrap">
                                <span wire:loading.remove wire:target="buyNow({{ $v->id }})">Buy Now</span>
                                <span wire:loading wire:target="buyNow({{ $v->id }})">Adding...</span>
                            </button>
                        @endif
                    @else
                        <a href="{{ route('shop.product', $product->seo_slug) }}"
                           class="px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition duration-150 whitespace-nowrap">
                            View Options
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
