<div class="pt-4 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4 bg-white border border-slate-100 px-4 py-2.5 rounded-2xl shadow-sm">
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-400">
                <a href="{{ route('shop.index') }}" wire:navigate class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Shop
                </a>
                @foreach($breadcrumbs as $bc)
                    <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('shop.category', ['category_slug' => $bc->slug]) }}" wire:navigate class="text-slate-500 hover:text-indigo-600 transition-colors">
                        {{ $bc->name }}
                    </a>
                @endforeach
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-800 font-bold truncate max-w-[200px] sm:max-w-xs">
                    {{ $product->title }}
                </span>
            </div>
            @if(auth()->check() && auth()->user()->role_id === 3)
                <a href="{{ route('admin.ecommerce.product-edit', ['id' => $product->id]) }}" target="admin_product_edit"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow-md transition duration-150">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Product (Admin)
                </a>
            @endif
        </div>

        <!-- Alerts -->
        @if(session()->has('status'))
            <div class="mb-8 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-8 p-4 bg-red-50 rounded-2xl border border-red-100 flex items-center gap-3 text-red-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Product Section -->
        @switch($product->layout_type)
            @case(2)
                {{-- Left Side Images, description full-width below --}}
                <div class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm">
                        <!-- Left Side: Visual / Gallery -->
                        <div class="lg:col-span-7 flex flex-col">
                            @include('livewire.partials.product-gallery')
                            @include('livewire.partials.product-video-player')
                        </div>
                        <!-- Right Side: Configuration & Buy -->
                        <div class="lg:col-span-5 flex flex-col justify-start">
                            @include('livewire.partials.product-buy-box')
                        </div>
                    </div>
                    {{-- Full-width description below --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm">
                        @include('livewire.partials.product-description')
                    </div>
                </div>
                @break

            @case(3)
                {{-- Right Side Images With Large Video Player Space Below, description full-width below --}}
                <div class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm">
                        <!-- Left Side: Configuration & Buy -->
                        <div class="lg:col-span-5 flex flex-col justify-start order-2 lg:order-1">
                            @include('livewire.partials.product-buy-box')
                        </div>
                        <!-- Right Side: Visual / Info -->
                        <div class="lg:col-span-7 flex flex-col order-1 lg:order-2">
                            @include('livewire.partials.product-gallery')
                        </div>
                    </div>
                    @if($selectedVariant && $selectedVariant->video_preview)
                        <div class="bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm">
                            @include('livewire.partials.product-video-player')
                        </div>
                    @endif
                    {{-- Full-width description below --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm">
                        @include('livewire.partials.product-description')
                    </div>
                </div>
                @break

            @case(4)
                {{-- Centered Layout With Images On Top, description full-width below --}}
                <div class="space-y-8">
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm space-y-12">
                        <div class="max-w-3xl mx-auto flex flex-col items-stretch">
                            @include('livewire.partials.product-gallery')
                        </div>
                        <div class="max-w-2xl mx-auto flex flex-col items-stretch">
                            @include('livewire.partials.product-buy-box')
                            @include('livewire.partials.product-video-player')
                        </div>
                    </div>
                    {{-- Full-width description below --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm">
                        @include('livewire.partials.product-description')
                    </div>
                </div>
                @break

            @case(5)
                {{-- Centered Layout With Large Video Player On Top, description full-width below --}}
                <div class="space-y-8">
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm space-y-12">
                        @if($selectedVariant && $selectedVariant->video_preview)
                            <div class="max-w-4xl mx-auto">
                                @include('livewire.partials.product-video-player')
                            </div>
                        @endif
                        <div class="max-w-3xl mx-auto flex flex-col items-stretch">
                            @include('livewire.partials.product-gallery')
                        </div>
                        <div class="max-w-2xl mx-auto flex flex-col items-stretch">
                            @include('livewire.partials.product-buy-box')
                        </div>
                    </div>
                    {{-- Full-width description below --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm">
                        @include('livewire.partials.product-description')
                    </div>
                </div>
                @break

            @default
                {{-- Right Side Images (Default) — description full-width below --}}
                <div class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm">
                        <!-- Left Side: Configuration & Buy -->
                        <div class="lg:col-span-5 flex flex-col justify-start order-2 lg:order-1">
                            @include('livewire.partials.product-buy-box')
                        </div>
                        <!-- Right Side: Visual / Info -->
                        <div class="lg:col-span-7 flex flex-col order-1 lg:order-2">
                            @include('livewire.partials.product-gallery')
                            @include('livewire.partials.product-video-player')
                        </div>
                    </div>
                    {{-- Full-width description below --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 lg:p-12 shadow-sm">
                        @include('livewire.partials.product-description')
                    </div>
                </div>
        @endswitch

    {{-- ══════════════════════════════════════════════════════════════════
         YOU MAY ALSO LIKE — Alpine.js scrollable carousel
         ══════════════════════════════════════════════════════════════════ --}}
    @if($relatedProducts->isNotEmpty())
        @php
            $recCards = $relatedProducts->map(function ($rp) use ($userType) {
                $rv       = $rp->variants->first();
                $img      = $rv ? $rv->images->first() : null;
                $thumbUrl = $img ? $img->thumbnailUrl() : null;
                $price    = 0;
                if ($rv) {
                    $base  = $userType == 2 ? $rv->wholesale_price : $rv->public_price;
                    $price = ($rv->on_sale && $rv->sale_price > 0 && $userType != 2) ? $rv->sale_price : $base;
                }
                return [
                    'title'   => $rp->title,
                    'desc'    => $rp->parsed_short_description,
                    'thumb'   => $thumbUrl,
                    'price'   =>  number_format($price, 2),
                    #'price'   => $currencySymbol . number_format($price, 2),
                    'url'     => route('shop.product', $rp->seo_slug),
                    'digital' => $rv ? (bool) $rv->download_item : false,
                ];
            })->values()->toJson(JSON_HEX_APOS | JSON_HEX_QUOT);

            // Image orientation
            $imgOrientation  = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
            $carouselAspect  = $imgOrientation === '1:1' ? 'aspect-square' : 'aspect-video';
            $carouselObject  = $imgOrientation === '1:1' ? 'object-contain' : 'object-cover';
        @endphp

        <div class="mt-16"
             x-data="{
                 cards: {{ $recCards }},
                 track: null,
                 canPrev: false,
                 canNext: true,
                 init() {
                     this.track = this.$refs.track;
                     this.track.addEventListener('scroll', () => this.updateNav(), { passive: true });
                     this.$nextTick(() => this.updateNav());
                 },
                 updateNav() {
                     this.canPrev = this.track.scrollLeft > 8;
                     this.canNext = this.track.scrollLeft + this.track.clientWidth < this.track.scrollWidth - 8;
                 },
                 scroll(dir) {
                     const step = this.track.clientWidth * 0.75;
                     this.track.scrollBy({ left: dir * step, behavior: 'smooth' });
                 },
             }"
        >
            {{-- Section header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Product recommendations for you</h2>
                    
                </div>
                {{-- Prev / Next arrows --}}
                <div class="flex items-center gap-2">
                    <button
                        @click="scroll(-1)"
                        :disabled="!canPrev"
                        :class="canPrev ? 'bg-white border-slate-200 text-slate-700 hover:border-indigo-300 hover:text-indigo-600 shadow-sm' : 'bg-slate-50 border-slate-100 text-slate-300 cursor-not-allowed'"
                        class="p-2.5 rounded-xl border transition duration-150 focus:outline-none"
                        aria-label="Scroll left"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button
                        @click="scroll(1)"
                        :disabled="!canNext"
                        :class="canNext ? 'bg-white border-slate-200 text-slate-700 hover:border-indigo-300 hover:text-indigo-600 shadow-sm' : 'bg-slate-50 border-slate-100 text-slate-300 cursor-not-allowed'"
                        class="p-2.5 rounded-xl border transition duration-150 focus:outline-none"
                        aria-label="Scroll right"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Scrollable track --}}
            <div
                x-ref="track"
                class="flex gap-5 overflow-x-auto pb-3 scroll-smooth snap-x snap-mandatory"
                style="scrollbar-width: none; -ms-overflow-style: none;"
            >
                <template x-for="(card, i) in cards" :key="i">
                    <a
                        :href="card.url"
                        wire:navigate
                        class="group flex-shrink-0 w-56 snap-start bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:border-indigo-200 transition-all duration-200 flex flex-col"
                    >
                        {{-- Thumbnail --}}
                        <div class="{{ $carouselAspect }} bg-gradient-to-br from-indigo-50/60 to-violet-50/60 flex items-center justify-center overflow-hidden relative">
                            <template x-if="card.thumb">
                                <img :src="card.thumb" :alt="card.title"
                                     class="w-full h-full {{ $carouselObject }} group-hover:scale-105 transition-transform duration-300">
                            </template>
                            <template x-if="!card.thumb">
                                <span class="text-indigo-300">
                                    <template x-if="card.digital">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </template>
                                    <template x-if="!card.digital">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </template>
                                </span>
                            </template>
                        </div>

                        {{-- Info --}}
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug" x-text="card.title"></h3>
                            <p class="text-xs text-slate-400 mt-1.5 line-clamp-2 flex-1" x-text="card.desc"></p>
                            <div class="mt-3 pt-3 border-t border-slate-50 flex items-center justify-between">
                                <span class="text-sm font-extrabold text-slate-900" x-text="card.price"></span>
                                <span class="text-[11px] font-bold text-indigo-600 group-hover:text-indigo-700 flex items-center gap-0.5">
                                    View
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    @endif

    {{-- Product Reviews Section --}}
    @if (\App\Models\CmsSetting::isEnabled('enable_reviews') && $product->reviews_enabled)
        <div class="mt-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-slate-100 dark:border-slate-800 pt-10 pb-16">
            @if (\App\Models\CmsSetting::get('third_party_reviews_js'))
                <div class="w-full">
                    {!! \App\Models\CmsSetting::get('third_party_reviews_js') !!}
                </div>
            @else
                <livewire:product-reviews-list :product-id="$product->id" />
            @endif
        </div>
    @endif
</div>
