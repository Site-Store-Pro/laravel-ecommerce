<div x-data="{
        showModal: @entangle('showModal').live,
        close() {
            this.showModal = false;
            $wire.closeModal();
        }
     }"
     @open-quick-shop.window="if ($event.detail) { $wire.openQuickShop($event.detail.productId || $event.detail.id || $event.detail); }"
     @openQuickShop.window="if ($event.detail) { $wire.openQuickShop($event.detail.productId || $event.detail.id || $event.detail); }"
     x-init="$watch('showModal', value => {
         if (value) {
             document.body.style.overflow = 'hidden';
         } else {
             document.body.style.overflow = '';
         }
     })"
     x-show="showModal"
     x-cloak
     style="display: none; z-index: 9999999 !important;"
     class="fixed inset-0 z-[9999999] flex items-center justify-center p-4 sm:p-6 md:p-8 lg:p-10 overflow-y-auto"
     @keydown.escape.window="close()">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity"
         @click="close()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    {{-- Modal Card --}}
    @if($product)
        @php
            $hasImages = $product->variants->flatMap(fn($v) => $v->images->where('active', 1))->isNotEmpty();
            $hasVideo = !empty($product->product_video_embed) || ($selectedVariant && !empty($selectedVariant->video_preview));
            $hasVisuals = $hasImages || $hasVideo;
        @endphp
        <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl max-w-5xl w-full max-h-[85vh] sm:max-h-[88vh] my-auto flex flex-col z-10 overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-3"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-3">

            {{-- Modal Header Bar --}}
            <div class="px-6 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3 bg-slate-50/80 dark:bg-slate-900/80 backdrop-blur-sm shrink-0">
                <a href="{{ route('shop.product', $product->seo_slug) }}" wire:navigate
                   class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    @label('catalog.view_full_details', 'View Full Details') &rarr;
                </a>
                <button type="button" @click="close()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-full hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-all focus:outline-none" aria-label="Close Quick Shop">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body: Dynamic Layouts 1-6 --}}
            <div class="p-6 sm:p-8 md:p-10 overflow-y-auto flex-1 space-y-6">

                @switch($product->layout_type)
                    @case(2)
                        {{-- Layout 2: Left Side Images, Buy Box on Right --}}
                        @if($hasVisuals)
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                                <!-- Left Side: Visual / Gallery -->
                                <div class="lg:col-span-6 flex flex-col">
                                    @include('livewire.partials.product-gallery')
                                    @include('livewire.partials.product-video-player')
                                </div>
                                <!-- Right Side: Configuration & Buy -->
                                <div class="lg:col-span-6 flex flex-col justify-start">
                                    @include('livewire.partials.product-buy-box', ['isQuickShop' => true])
                                </div>
                            </div>
                        @else
                            <div class="max-w-2xl mx-auto w-full">
                                @include('livewire.partials.product-buy-box', ['isQuickShop' => true])
                            </div>
                        @endif
                        @break

                    @case(3)
                        {{-- Layout 3: Right Side Images With Video Space Below --}}
                        @if($hasVisuals)
                            <div class="space-y-8">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                                    <!-- Left Side: Configuration & Buy -->
                                    <div class="lg:col-span-6 flex flex-col justify-start order-2 lg:order-1">
                                        @include('livewire.partials.product-buy-box', ['isQuickShop' => true])
                                    </div>
                                    <!-- Right Side: Visual / Info -->
                                    <div class="lg:col-span-6 flex flex-col order-1 lg:order-2">
                                        @include('livewire.partials.product-gallery')
                                    </div>
                                </div>
                                @if($product->product_video_embed || ($selectedVariant && $selectedVariant->video_preview))
                                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 space-y-6">
                                        @if($product->product_video_embed)
                                            <div class="w-full">
                                                {!! $product->parsed_video_embed !!}
                                            </div>
                                        @endif
                                        @if($selectedVariant && $selectedVariant->video_preview)
                                            @include('livewire.partials.product-video-player')
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="max-w-2xl mx-auto w-full">
                                @include('livewire.partials.product-buy-box', ['isQuickShop' => true])
                            </div>
                        @endif
                        @break

                    @case(4)
                        {{-- Layout 4: Centered Layout With Images On Top --}}
                        <div class="space-y-8 max-w-2xl mx-auto w-full">
                            @if($hasImages)
                                <div class="flex flex-col items-stretch">
                                    @include('livewire.partials.product-gallery')
                                </div>
                            @endif
                            <div class="flex flex-col items-stretch w-full">
                                @include('livewire.partials.product-buy-box', ['isQuickShop' => true])
                                @include('livewire.partials.product-video-player')
                            </div>
                        </div>
                        @break

                    @case(5)
                        {{-- Layout 5: Centered Layout With Large Video Player On Top --}}
                        <div class="space-y-8 max-w-2xl mx-auto w-full">
                            @if($product->product_video_embed || ($selectedVariant && $selectedVariant->video_preview))
                                <div class="space-y-4">
                                    @if($product->product_video_embed)
                                        <div class="w-full">
                                            {!! $product->parsed_video_embed !!}
                                        </div>
                                    @endif
                                    @if($selectedVariant && $selectedVariant->video_preview)
                                        @include('livewire.partials.product-video-player')
                                    @endif
                                </div>
                            @endif
                            @if($hasImages)
                                <div class="flex flex-col items-stretch">
                                    @include('livewire.partials.product-gallery')
                                </div>
                            @endif
                            <div class="flex flex-col items-stretch w-full">
                                @include('livewire.partials.product-buy-box', ['isQuickShop' => true])
                            </div>
                        </div>
                        @break

                    @case(6)
                        {{-- Layout 6: No Images | Video On Page --}}
                        <div class="space-y-8 max-w-2xl mx-auto w-full">
                            @if($product->product_video_embed)
                                <div class="w-full">
                                    {!! $product->parsed_video_embed !!}
                                </div>
                            @endif
                            <div class="flex flex-col items-stretch w-full">
                                @include('livewire.partials.product-buy-box', ['isQuickShop' => true])
                                @include('livewire.partials.product-video-player')
                            </div>
                        </div>
                        @break

                    @default
                        {{-- Default (Layout 1): Right Side Images --}}
                        @if($hasVisuals)
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                                <!-- Left Side: Configuration & Buy -->
                                <div class="lg:col-span-6 flex flex-col justify-start order-2 lg:order-1">
                                    @include('livewire.partials.product-buy-box', ['isQuickShop' => true])
                                </div>
                                <!-- Right Side: Visual / Info -->
                                <div class="lg:col-span-6 flex flex-col order-1 lg:order-2">
                                    @include('livewire.partials.product-gallery')
                                    @include('livewire.partials.product-video-player')
                                </div>
                            </div>
                        @else
                            <div class="max-w-2xl mx-auto w-full">
                                @include('livewire.partials.product-buy-box', ['isQuickShop' => true])
                            </div>
                        @endif
                @endswitch

                {{-- Mobile full details link --}}
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-center sm:hidden">
                    <a href="{{ route('shop.product', $product->seo_slug) }}" wire:navigate
                       class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                        @label('catalog.view_full_details', 'View Full Details') &rarr;
                    </a>
                </div>

            </div>
        </div>
    @endif
</div>
