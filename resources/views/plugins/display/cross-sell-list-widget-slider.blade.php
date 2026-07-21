{{--
    Cross-Sell List Widget — Swiper Slider View (Livewire-aware version)
    Used by CrossSellListWidget Livewire component.
    Buy Now calls wire:click="buyNow()" so the modal can fire in-place.
    wire:ignore on the swiper wrapper prevents Livewire from re-rendering the slider DOM.
--}}
@php
    use App\Services\DiscountService;
    $user = auth()->user();
    $imgOrientation   = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $cssAspectRatio   = $imgOrientation === '1:1' ? '1/1' : '16/10';
    $cssObjectFit     = $imgOrientation === '1:1' ? 'contain' : 'cover';
@endphp

@once
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endonce

<div class="cross-sell-list-plugin-section py-8">

    {{-- Section Header --}}
    @if(!empty($header))
        <div class="cross-sell-list-header mb-8 text-center">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                {{ $header }}
            </h2>
            <div class="mt-2 mx-auto w-16 h-1 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500"></div>
        </div>
    @endif

    {{-- Slider wrapper — wire:ignore prevents Livewire from touching the Swiper DOM --}}
    <div class="cross-sell-list-slider-wrapper relative" id="{{ $instanceId }}_outer" wire:ignore>

        <style>
            #{{ $instanceId }}_outer .cross-sell-list-swiper { width: 100%; padding-bottom: 8px; }
            #{{ $instanceId }}_outer .swiper-slide { height: auto; }
            #{{ $instanceId }}_outer .fi-card {
                background: #fff;
                border: 1px solid #f1f5f9;
                border-radius: 24px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.07);
                overflow: hidden;
                display: flex;
                flex-direction: column;
                height: 100%;
                transition: box-shadow 0.3s, border-color 0.3s;
            }
            #{{ $instanceId }}_outer .fi-card:hover { box-shadow: 0 4px 16px rgba(79,70,229,0.12); border-color: #e0e7ff; }
            #{{ $instanceId }}_outer .fi-img-wrap {
                aspect-ratio: {{ $cssAspectRatio }};
                background: linear-gradient(135deg, rgba(238,242,255,0.5), rgba(245,243,255,0.5));
                display: flex; align-items: center; justify-content: center;
                overflow: hidden; position: relative;
            }
            #{{ $instanceId }}_outer .fi-img-wrap img { width: 100%; height: 100%; object-fit: {{ $cssObjectFit }}; transition: transform 0.5s; }
            #{{ $instanceId }}_outer .fi-card:hover .fi-img-wrap img { transform: scale(1.05); }
            #{{ $instanceId }}_outer .fi-badge-featured {
                position: absolute; top: 10px; left: 10px;
                padding: 2px 8px; border-radius: 99px;
                font-size: 10px; font-weight: 700;
                background: #fbbf24; color: #fff;
                box-shadow: 0 1px 3px rgba(0,0,0,0.15);
            }
            #{{ $instanceId }}_outer .fi-badge-sale {
                position: absolute; top: 10px; right: 10px;
                padding: 2px 8px; border-radius: 99px;
                font-size: 10px; font-weight: 700;
                background: #fee2e2; color: #dc2626;
                border: 1px solid #fecaca;
            }
            #{{ $instanceId }}_outer .fi-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
            #{{ $instanceId }}_outer .fi-title {
                font-size: 13px; font-weight: 700;
                color: #0f172a; line-height: 1.4;
                display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
                transition: color 0.2s;
            }
            #{{ $instanceId }}_outer .fi-card:hover .fi-title { color: #4f46e5; }
            #{{ $instanceId }}_outer .fi-desc {
                font-size: 11px; color: #94a3b8; margin-top: 6px;
                display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
            }
            #{{ $instanceId }}_outer .fi-footer {
                margin-top: auto; padding-top: 12px; border-top: 1px solid #f8fafc;
                display: flex; align-items: center; justify-content: space-between; gap: 8px;
            }
            #{{ $instanceId }}_outer .fi-price { font-size: 15px; font-weight: 800; color: #0f172a; }
            #{{ $instanceId }}_outer .fi-price-orig { font-size: 11px; color: #94a3b8; text-decoration: line-through; }
            #{{ $instanceId }}_outer .fi-btn {
                display: inline-flex; align-items: center; gap: 4px;
                padding: 6px 14px; font-size: 11px; font-weight: 700;
                border-radius: 12px; white-space: nowrap;
                text-decoration: none; transition: background 0.15s, color 0.15s;
                border: none; cursor: pointer;
            }
            #{{ $instanceId }}_outer .fi-btn-primary { background: #4f46e5; color: #fff; }
            #{{ $instanceId }}_outer .fi-btn-primary:hover { background: #4338ca; }
            #{{ $instanceId }}_outer .fi-btn-outline { background: #eef2ff; color: #4f46e5; }
            #{{ $instanceId }}_outer .fi-btn-outline:hover { background: #e0e7ff; }
            #{{ $instanceId }}_outer .fi-btn-disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
            #{{ $instanceId }}_outer .fi-swiper-prev,
            #{{ $instanceId }}_outer .fi-swiper-next {
                width: 36px; height: 36px;
                background: #fff; border: 1px solid #e2e8f0;
                border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                display: flex; align-items: center; justify-content: center;
                cursor: pointer; transition: all 0.2s;
                position: absolute; top: 50%; transform: translateY(-50%); z-index: 10;
                color: #4f46e5;
            }
            #{{ $instanceId }}_outer .fi-swiper-prev:hover,
            #{{ $instanceId }}_outer .fi-swiper-next:hover { background: #4f46e5; color: #fff; border-color: #4f46e5; }
            #{{ $instanceId }}_outer .fi-swiper-prev { left: -18px; }
            #{{ $instanceId }}_outer .fi-swiper-next { right: -18px; }
            #{{ $instanceId }}_outer .fi-swiper-prev.swiper-button-disabled,
            #{{ $instanceId }}_outer .fi-swiper-next.swiper-button-disabled { opacity: 0.35; pointer-events: none; }
            @media (max-width: 640px) {
                #{{ $instanceId }}_outer .fi-swiper-prev { left: 0; }
                #{{ $instanceId }}_outer .fi-swiper-next { right: 0; }
            }
        </style>

        <div class="swiper cross-sell-list-swiper" id="{{ $instanceId }}">
            <div class="swiper-wrapper">
                @foreach($products as $product)
                    @php
                        $defaultVariant = $product->variants->first();
                        $priceToShow    = 0;
                        $originalPrice  = 0;
                        if ($defaultVariant) {
                            $originalPrice = $defaultVariant->public_price ?? 0;
                            $priceToShow   = DiscountService::getDiscountedPriceForVariant($defaultVariant, $user, 1);
                        }
                        $isFromPrice = $product->variants->count() > 1
                            && $product->variants->pluck('public_price')->unique()->count() > 1;
                        $v     = $defaultVariant;
                        $avail = ($v && $v->inventory)
                            ? $v->inventory->quantity_available - $v->inventory->reserved_stock
                            : 999;
                    @endphp

                    <div class="swiper-slide" style="height: auto;">
                        <div class="fi-card mx-1">

                            {{-- Image --}}
                            <a href="{{ route('shop.product', $product->seo_slug) }}" class="fi-img-wrap">
                                @if($defaultVariant && $defaultVariant->thumbnailImageUrl())
                                    <img src="{{ $defaultVariant->thumbnailImageUrl() }}" alt="{{ $product->title }}">
                                @else
                                    <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                @endif
                                @if($defaultVariant && $defaultVariant->on_sale)
                                    <span class="fi-badge-sale">Sale</span>
                                @endif
                            </a>

                            {{-- Body --}}
                            <div class="fi-body">
                                <a href="{{ route('shop.product', $product->seo_slug) }}" class="fi-title">
                                    {{ $product->title }}
                                </a>
                                @if($product->short_description)
                                    <p class="fi-desc">{{ strip_tags($product->short_description) }}</p>
                                @endif

                                <div class="fi-footer">
                                    <div>
                                        @if($defaultVariant)
                                            <div class="fi-price">{{ $isFromPrice ? 'From ' : '' }}${{ number_format($priceToShow, 2) }}</div>
                                            @if($priceToShow < $originalPrice)
                                                <div class="fi-price-orig">${{ number_format($originalPrice, 2) }}</div>
                                            @endif
                                        @else
                                            <span style="font-size:12px;color:#94a3b8">Out of Stock</span>
                                        @endif
                                    </div>

                                    @if($product->variants->count() === 1)
                                        @if(!$v->download_item && $avail <= 0)
                                            <span class="fi-btn fi-btn-disabled">Out of Stock</span>
                                        @else
                                            {{-- wire:click works here because this whole blade is inside the CrossSellListWidget Livewire component --}}
                                            <button wire:click="buyNow({{ $v->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="buyNow({{ $v->id }})"
                                                    class="fi-btn fi-btn-primary">
                                                <span wire:loading.remove wire:target="buyNow({{ $v->id }})">
                                                    Buy Now
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                    </svg>
                                                </span>
                                                <span wire:loading wire:target="buyNow({{ $v->id }})">Adding...</span>
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('shop.product', $product->seo_slug) }}" class="fi-btn fi-btn-outline">
                                            Options
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Navigation --}}
            @if($nav !== 'off')
                <div class="fi-swiper-prev" id="{{ $instanceId }}_prev" aria-label="Previous">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
                <div class="fi-swiper-next" id="{{ $instanceId }}_next" aria-label="Next">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Pagination dots --}}
        <div class="swiper-pagination" id="{{ $instanceId }}_pag" style="margin-top: 16px; position: relative;"></div>
    </div>
</div>

<script>
(function () {
    function initCrossSellListSwiper_{{ $instanceId }}() {
        if (typeof Swiper === 'undefined') {
            setTimeout(initCrossSellListSwiper_{{ $instanceId }}, 100);
            return;
        }
        const opts = {
            slidesPerView: 1.2,
            spaceBetween: 0,
            loop: {{ $products->count() > $slides ? 'true' : 'false' }},
            observeParents: true,
            observer: true,
            breakpoints: {
                480:  { slidesPerView: 2, spaceBetween: 0 },
                768:  { slidesPerView: 3, spaceBetween: 0 },
                1024: { slidesPerView: {{ $slides }}, spaceBetween: 0 }
            },
            @if($autoplay !== 'off')
            autoplay: { delay: {{ $speed }}, disableOnInteraction: false, pauseOnMouseEnter: true },
            @endif
            @if($nav !== 'off')
            navigation: {
                prevEl: '#{{ $instanceId }}_prev',
                nextEl: '#{{ $instanceId }}_next',
            },
            @endif
            pagination: {
                el: '#{{ $instanceId }}_pag',
                clickable: true,
                dynamicBullets: true,
            },
        };
        new Swiper('#{{ $instanceId }}', opts);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCrossSellListSwiper_{{ $instanceId }});
    } else {
        initCrossSellListSwiper_{{ $instanceId }}();
    }
})();
</script>
