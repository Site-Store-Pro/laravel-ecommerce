{{--
    Featured Items Plugin — Grid View
    Matches the shop-catalog.blade.php grid card design.
    Shortcode: [plugin:featured-items] or [plugin:featured-items display=grid cols=3]
--}}
@php
    use App\Services\DiscountService;
    $colMap = [2 => 'sm:grid-cols-2', 3 => 'sm:grid-cols-2 lg:grid-cols-3', 4 => 'sm:grid-cols-2 lg:grid-cols-4', 5 => 'sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5', 6 => 'sm:grid-cols-3 lg:grid-cols-6'];
    $colClass = $colMap[$cols] ?? 'sm:grid-cols-2 lg:grid-cols-4';
    $user = auth()->user();
    $imgOrientation = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $aspectClass    = $imgOrientation === '1:1' ? 'aspect-square' : 'aspect-video';
    $objectClass    = $imgOrientation === '1:1' ? 'object-contain' : 'object-cover';
@endphp

<div class="featured-items-plugin-section py-8">

    {{-- Section Header --}}
    @if(!empty($header))
        <div class="featured-items-header mb-8 text-center">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                {{ $header }}
            </h2>
            <div class="mt-2 mx-auto w-16 h-1 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500"></div>
        </div>
    @endif

    {{-- Product Grid --}}
    <div class="grid grid-cols-1 {{ $colClass }} gap-6">
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

                $hasVariantPricing = $product->variants->count() > 1 &&
                    $product->variants->pluck('public_price')->unique()->count() > 1;
                $isFromPrice = $hasVariantPricing;
            @endphp

            <div class="group bg-white border border-slate-100 rounded-3xl shadow-sm hover:shadow-md hover:border-slate-200 transition-all duration-300 flex flex-col overflow-hidden">

                {{-- Product Image --}}
                <a href="{{ route('shop.product', $product->seo_slug) }}"
                   class="{{ $aspectClass }} bg-gradient-to-br from-indigo-50/50 to-violet-50/50 flex items-center justify-center relative overflow-hidden">
                    @if($product->primaryThumbnailUrl())
                        <img src="{{ $product->primaryThumbnailUrl() }}"
                             alt="{{ $product->title }}"
                             class="w-full h-full {{ $objectClass }} group-hover:scale-105 transition-transform duration-500">
                    @else
                        <span class="p-4 rounded-full bg-white shadow-md text-indigo-600 group-hover:scale-110 transition-all duration-300 relative z-10">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </span>
                    @endif

                    {{-- Featured badge --}}
                    <span class="absolute top-3 left-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-400 text-white shadow-sm">
                        @label('plugin.featured', '★ Featured')
                    </span>

                    @if($defaultVariant && $defaultVariant->on_sale)
                        <span class="absolute top-3 right-3 px-2.5 py-1 text-xs font-bold text-red-600 bg-red-50 rounded-full border border-red-100">@label('plugin.sale', 'Sale')</span>
                    @endif
                </a>

                {{-- Product Info --}}
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug">
                            <a href="{{ route('shop.product', $product->seo_slug) }}">{{ $product->title }}</a>
                        </h3>
                        @if($product->short_description)
                            <p class="mt-2 text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ strip_tags($product->short_description) }}
                            </p>
                        @endif
                    </div>

                    {{-- Price + Button --}}
                    <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between gap-2">
                        <div>
                            @if($defaultVariant)
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-lg font-extrabold text-slate-900">
                                        @if($isFromPrice)@label('plugin.from', 'From') @endif${{ number_format($priceToShow, 2) }}
                                    </span>
                                    @if($priceToShow < $originalPrice)
                                        <span class="text-xs text-slate-400 line-through">${{ number_format($originalPrice, 2) }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-slate-400">@label('plugin.out_of_stock', 'Out of Stock')</span>
                            @endif
                        </div>

                        @if($product->variants->count() === 1)
                            @php
                                $v = $product->variants->first();
                                $avail = ($v->inventory ? $v->inventory->quantity_available - $v->inventory->reserved_stock : 999);
                            @endphp
                            @if(!$v->download_item && $avail <= 0)
                                <button disabled class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed whitespace-nowrap">
                                    @label('plugin.out_of_stock', 'Out of Stock')
                                </button>
                            @else
                                <a href="{{ route('shop.product', $product->seo_slug) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-sm whitespace-nowrap">
                                    @label('plugin.buy_now', 'Buy Now')
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('shop.product', $product->seo_slug) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition duration-150 whitespace-nowrap">
                                @label('plugin.view_options', 'View Options')
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
