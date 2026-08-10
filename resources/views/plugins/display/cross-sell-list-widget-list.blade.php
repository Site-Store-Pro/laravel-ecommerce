{{--
    Cross-Sell List Widget — List View (Livewire-aware version)
    Used by CrossSellListWidget Livewire component.
--}}
@php
    use App\Services\DiscountService;
    $user = auth()->user();
    $imgOrientation = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $objectClass    = $imgOrientation === '1:1' ? 'object-contain' : 'object-cover';
    $listSizeClass  = $imgOrientation === '1:1' ? 'w-24 h-24' : 'w-28 h-24';
@endphp

<div class="cross-sell-list-plugin-section py-8">

    {{-- Section Header --}}
    @if(!empty($header))
        <div class="cross-sell-list-header mb-8">
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

            <div class="group bg-white dark:bg-slate-800 rounded-3xl border border-slate-150 dark:border-slate-700/60 p-4 sm:p-5 flex flex-col sm:flex-row items-center gap-6 hover:shadow-lg transition">

                {{-- Thumbnail --}}
                <a href="{{ route('shop.product', $product->seo_slug) }}"
                   class="{{ $listSizeClass }} shrink-0 rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-900 flex items-center justify-center relative">
                    @if($product->primaryThumbnailUrl())
                        <img src="{{ $product->primaryThumbnailUrl() }}"
                             alt="{{ $product->title }}"
                             class="w-full h-full {{ $objectClass }} group-hover:scale-105 transition-transform duration-500">
                    @else
                        <span class="text-slate-300 dark:text-slate-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                    @endif
                    @if($defaultVariant && $defaultVariant->on_sale)
                        <span class="absolute top-1.5 left-1.5 px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-white bg-gradient-to-r from-rose-500 to-pink-500 rounded-full shadow-md">@label('plugin.sale', 'Sale')</span>
                    @endif
                </a>

                {{-- Info --}}
                <div class="flex-1 min-w-0 text-center sm:text-left">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition truncate">
                        <a href="{{ route('shop.product', $product->seo_slug) }}" class="!no-underline no-underline text-inherit hover:text-indigo-600 dark:hover:text-indigo-400" style="text-decoration: none !important;">{{ $product->title }}</a>
                    </h3>
                    @if($product->short_description)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{{ strip_tags($product->short_description) }}</p>
                    @endif
                </div>

                {{-- Price + Action --}}
                <div class="flex items-center gap-4 shrink-0">
                    <div class="text-right">
                        @if(!$product->is_donation_or_bill_pay && $defaultVariant)
                            <span class="block text-lg font-extrabold text-slate-900 dark:text-slate-200">
                                @if($isFromPrice)@label('plugin.from', 'From') @endif${{ number_format($priceToShow, 2) }}
                            </span>
                            @if($priceToShow < $originalPrice)
                                <span class="block text-xs text-slate-400 line-through font-semibold">${{ number_format($originalPrice, 2) }}</span>
                            @endif
                        @elseif(!$product->is_donation_or_bill_pay)
                            <span class="text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-lg">@label('plugin.out_of_stock', 'Out of Stock')</span>
                        @endif
                    </div>

                    @if($product->requiresOptions())
                        <a href="{{ route('shop.product', $product->seo_slug) }}"
                           class="btn-primary inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 hover:scale-105 transition-all no-underline shrink-0">
                            @label('plugin.view_options', 'View Options')
                        </a>
                    @else
                        @php
                            $v     = $product->variants->first();
                            $avail = ($v->inventory
                                ? $v->inventory->quantity_available - $v->inventory->reserved_stock
                                : 999);
                        @endphp
                        @if(!$v->download_item && $avail <= 0)
                            <span class="text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-lg">
                                @label('plugin.out_of_stock', 'Out of Stock')
                            </span>
                        @else
                            <button wire:click="buyNow({{ $v->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="buyNow({{ $v->id }})"
                                    class="btn-primary inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 hover:scale-105 transition-all shrink-0">
                                <span wire:loading.remove wire:target="buyNow({{ $v->id }})">@label('plugin.buy_now', 'Buy Now')</span>
                                <span wire:loading wire:target="buyNow({{ $v->id }})">@label('plugin.adding', 'Adding...')</span>
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
