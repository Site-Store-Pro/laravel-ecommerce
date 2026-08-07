{{--
    Featured Items Widget — Swiper Slider View (Livewire-aware version)
    Copied 100% from Cross-Sell List Widget Slider display.
--}}
@php
    use App\Services\DiscountService;
    $user = auth()->user();
    $imgOrientation   = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $cssAspectRatio   = $imgOrientation === '1:1' ? '1/1' : '16/10';
    $cssObjectFit     = $imgOrientation === '1:1' ? 'contain' : 'cover';
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

    {{-- Slider wrapper — wire:ignore prevents Livewire from touching the Swiper DOM --}}
    <div class="featured-items-slider-wrapper relative" id="{{ $instanceId }}_outer" wire:ignore>

        <style>
            #{{ $instanceId }}_outer .featured-items-swiper { width: 100%; padding-bottom: 8px; }
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
                border: none; cursor: pointer; flex-shrink: 0;
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
            /* Dark mode overrides */
            .dark #{{ $instanceId }}_outer .fi-card {
                background: #1e293b;
                border-color: rgba(51,65,85,0.6);
            }
            .dark #{{ $instanceId }}_outer .fi-card:hover {
                box-shadow: 0 4px 16px rgba(79,70,229,0.2);
                border-color: #475569;
            }
            .dark #{{ $instanceId }}_outer .fi-img-wrap {
                background: linear-gradient(135deg, rgba(79,70,229,0.2), rgba(124,58,237,0.2));
            }
            .dark #{{ $instanceId }}_outer .fi-title { color: #f1f5f9; }
            .dark #{{ $instanceId }}_outer .fi-card:hover .fi-title { color: #818cf8; }
            .dark #{{ $instanceId }}_outer .fi-desc { color: #94a3b8; }
            .dark #{{ $instanceId }}_outer .fi-footer { border-top-color: rgba(51,65,85,0.6); }
            .dark #{{ $instanceId }}_outer .fi-price { color: #e2e8f0; }
            .dark #{{ $instanceId }}_outer .fi-price-orig { color: #475569; }
            .dark #{{ $instanceId }}_outer .fi-btn-disabled { background: #334155; color: #64748b; }
            .dark #{{ $instanceId }}_outer .fi-btn-outline { background: rgba(79,70,229,0.15); color: #818cf8; }
            .dark #{{ $instanceId }}_outer .fi-btn-outline:hover { background: rgba(79,70,229,0.25); }
            .dark #{{ $instanceId }}_outer .fi-swiper-prev,
            .dark #{{ $instanceId }}_outer .fi-swiper-next {
                background: #1e293b; border-color: #475569; color: #818cf8;
            }
            .dark #{{ $instanceId }}_outer .fi-swiper-prev:hover,
            .dark #{{ $instanceId }}_outer .fi-swiper-next:hover { background: #4f46e5; color: #fff; border-color: #4f46e5; }
        </style>

        <div class="swiper featured-items-swiper" id="{{ $instanceId }}">
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
                                @if($product->primaryThumbnailUrl())
                                    <img src="{{ $product->primaryThumbnailUrl() }}" alt="{{ $product->title }}">
                                @else
                                    <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                @endif
                                @if($defaultVariant && $defaultVariant->on_sale)
                                    <span class="absolute top-3 left-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full shadow-md z-10">@label('plugin.sale', 'Sale')</span>
                                @endif
                            </a>

                            {{-- Body --}}
                            <div class="fi-body">
                                <h3 style="margin:0;padding:0;">
                                    <a href="{{ route('shop.product', $product->seo_slug) }}" class="fi-title !no-underline" style="text-decoration:none !important;">
                                        {{ $product->title }}
                                    </a>
                                </h3>
                                @if($product->short_description)
                                    <p class="fi-desc">{{ strip_tags($product->short_description) }}</p>
                                @endif

                                <div class="fi-footer">
                                    <div>
                                        @if(!$product->is_donation_or_bill_pay && $defaultVariant)
                                            <div class="fi-price">@if($isFromPrice)@label('plugin.from', 'From') @endif${{ number_format($priceToShow, 2) }}</div>
                                            @if($priceToShow < $originalPrice)
                                                <div class="fi-price-orig">${{ number_format($originalPrice, 2) }}</div>
                                            @endif
                                        @elseif(!$product->is_donation_or_bill_pay)
                                            <span style="font-size:12px;color:#94a3b8">@label('plugin.out_of_stock', 'Out of Stock')</span>
                                        @endif
                                    </div>

                                    @if($product->requiresOptions())
                                        <a href="{{ route('shop.product', $product->seo_slug) }}" class="fi-btn fi-btn-primary" style="text-decoration:none;">
                                            @label('plugin.view_options', 'View Options')
                                        </a>
                                    @else
                                        @if(!$v->download_item && $avail <= 0)
                                            <span class="fi-btn fi-btn-disabled">@label('plugin.out_of_stock', 'Out of Stock')</span>
                                        @else
                                            <button wire:click="buyNow({{ $v->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="buyNow({{ $v->id }})"
                                                    class="fi-btn fi-btn-primary">
                                                <span wire:loading.remove wire:target="buyNow({{ $v->id }})">
                                                    @label('plugin.buy_now', 'Buy Now')
                                                </span>
                                                <span wire:loading wire:target="buyNow({{ $v->id }})">@label('plugin.adding', 'Adding...')</span>
                                            </button>
                                        @endif
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                initSwiper_{{ $instanceId }}();
            });
            document.addEventListener('livewire:navigated', function() {
                initSwiper_{{ $instanceId }}();
            });

            function initSwiper_{{ $instanceId }}() {
                var el = document.getElementById('{{ $instanceId }}');
                if (!el) return;
                if (el.swiper) return; // already initialized

                var slides = {{ $slides }};
                var autoplayOption = {{ $autoplay === 'on' ? "{ delay: {$speed}, disableOnInteraction: false }" : 'false' }};

                function createSwiper() {
                    new Swiper('#{{ $instanceId }}', {
                        slidesPerView: 1,
                        spaceBetween: 16,
                        loop: false,
                        autoplay: autoplayOption,
                        navigation: {
                            nextEl: '#{{ $instanceId }}_next',
                            prevEl: '#{{ $instanceId }}_prev',
                        },
                        breakpoints: {
                            640:  { slidesPerView: Math.min(2, slides), spaceBetween: 16 },
                            1024: { slidesPerView: Math.min(3, slides), spaceBetween: 20 },
                            1280: { slidesPerView: slides, spaceBetween: 24 }
                        }
                    });
                }

                if (typeof Swiper !== 'undefined') {
                    createSwiper();
                } else {
                    if (!document.getElementById('swiper-css')) {
                        var css = document.createElement('link');
                        css.id = 'swiper-css';
                        css.rel = 'stylesheet';
                        css.href = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css';
                        document.head.appendChild(css);
                    }
                    if (!document.getElementById('swiper-js')) {
                        var js = document.createElement('script');
                        js.id = 'swiper-js';
                        js.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
                        js.onload = createSwiper;
                        document.head.appendChild(js);
                    } else {
                        document.getElementById('swiper-js').addEventListener('load', createSwiper);
                    }
                }
            }
        </script>
    </div>
</div>
