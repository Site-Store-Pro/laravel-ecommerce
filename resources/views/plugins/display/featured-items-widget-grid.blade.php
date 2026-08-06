{{--
    Featured Items Widget — Grid View (Livewire-aware version)
    Used by FeaturedItemsWidget Livewire component.
    Buy Now calls wire:click="buyNow()" so the modal can fire in-place.
--}}
@php
    use App\Services\DiscountService;
    $colMap = [
        2 => 'sm:grid-cols-2',
        3 => 'sm:grid-cols-2 lg:grid-cols-3',
        4 => 'sm:grid-cols-2 lg:grid-cols-4',
        5 => 'sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5',
        6 => 'sm:grid-cols-3 lg:grid-cols-6',
    ];
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
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent dark:bg-none dark:text-slate-200">
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

                $hasVariantPricing = $product->variants->count() > 1
                    && $product->variants->pluck('public_price')->unique()->count() > 1;
                $isFromPrice = $hasVariantPricing;
            @endphp

            <div class="group bg-white dark:bg-slate-800 rounded-3xl border border-slate-150 dark:border-slate-700/60 overflow-hidden hover:shadow-xl hover:shadow-indigo-500/5 hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                <div>
                    {{-- Product Image --}}
                    <a href="{{ route('shop.product', $product->seo_slug) }}"
                       class="block relative overflow-hidden bg-slate-50 dark:bg-slate-900/50 {{ $aspectClass }}">
                        @if($product->primaryThumbnailUrl())
                            <img src="{{ $product->primaryThumbnailUrl() }}"
                                 alt="{{ $product->title }}"
                                 class="w-full h-full {{ $objectClass }} group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif

                        {{-- Featured badge --}}
                        @if($showBadge ?? true)
                        <span class="absolute top-3 left-3 bg-amber-400 text-white text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-md">
                            @label('plugin.featured', '★ Featured')
                        </span>
                        @endif

                        @if($defaultVariant && $defaultVariant->on_sale)
                            <span class="absolute top-3 right-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full shadow-md">@label('plugin.sale', 'Sale')</span>
                        @endif
                    </a>

                    {{-- Product Info --}}
                    <div class="px-5 pt-2.5 pb-4">
                        @if($product->brand)
                            <a href="{{ route('shop.brand', $product->brand->slug) }}" class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 hover:underline mb-1 block">{{ $product->brand->name }}</a>
                        @endif
                        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition line-clamp-2">
                            <a href="{{ route('shop.product', $product->seo_slug) }}" class="!no-underline no-underline text-inherit hover:text-indigo-600 dark:hover:text-indigo-400" style="text-decoration: none !important;">{{ $product->title }}</a>
                        </h3>
                        @if($product->short_description)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 line-clamp-2">
                                {{ strip_tags($product->short_description) }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Price + Button --}}
                <div class="p-5 pt-0 border-t border-slate-50 dark:border-slate-700/60 mt-auto flex items-center justify-between gap-3">
                    <div>
                        @if(!$product->is_donation_or_bill_pay && $defaultVariant)
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-lg font-extrabold text-slate-900 dark:text-slate-200">
                                    @if($isFromPrice)@label('plugin.from', 'From') @endif${{ number_format($priceToShow, 2) }}
                                </span>
                                @if($priceToShow < $originalPrice)
                                    <span class="text-xs text-slate-400 line-through font-semibold">${{ number_format($originalPrice, 2) }}</span>
                                @endif
                            </div>
                        @elseif(!$product->is_donation_or_bill_pay)
                            <span class="text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-lg">@label('plugin.out_of_stock', 'Out of Stock')</span>
                        @endif
                    </div>

                    @if($product->requiresOptions())
                        <a href="{{ route('shop.product', $product->seo_slug) }}"
                           class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 hover:scale-105 transition-all no-underline shrink-0">
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
                            <button disabled class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-700 rounded-xl cursor-not-allowed whitespace-nowrap shrink-0">
                                @label('plugin.out_of_stock', 'Out of Stock')
                            </button>
                        @else
                            <button wire:click="buyNow({{ $v->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="buyNow({{ $v->id }})"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 hover:scale-105 transition-all shrink-0">
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
