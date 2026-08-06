<div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative inline-block text-left w-full md:w-auto">
    <!-- Desktop Trigger -->
    <button @click="open = !open" class="hidden md:inline-flex items-center gap-1.5 dyn-nav-link px-3 py-2 focus:outline-none hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
        <span>@if(!empty($label)) {{ $label }} @else @label('nav.brands_fallback', 'Brands') @endif</span>
        <svg class="w-3 h-3 text-current opacity-60 transition-transform duration-200" :class="{'rotate-180 text-indigo-600 dark:text-indigo-400': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <!-- Mobile Trigger -->
    <button @click="open = !open" class="md:hidden flex w-full items-center justify-between py-2 rounded-xl text-sm font-semibold transition-colors hover:text-indigo-600 dark:hover:text-indigo-400" style="color: var(--nav-mobile-text, #1e293b)">
        <span>@if(!empty($label)) {{ $label }} @else @label('nav.brands_fallback', 'Brands') @endif</span>
        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180 text-indigo-600 dark:text-indigo-400': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Desktop Dropdown Mega Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
         class="hidden md:block absolute left-0 mt-1 w-[32rem] bg-white/95 dark:bg-slate-800/95 border border-slate-200/80 dark:border-slate-700/80 rounded-3xl shadow-xl shadow-slate-100/50 dark:shadow-none py-6 px-6 z-50 backdrop-blur-md"
         style="display: none;">

        @if($brands->isEmpty())
            <div class="text-xs text-slate-400 dark:text-slate-500 py-2">@label('nav.brands_empty', 'No brands registered.')</div>
        @else
            <div class="grid grid-cols-2 gap-4 max-h-[20rem] overflow-y-auto pr-1 scrollbar-thin scroll-smooth overscroll-contain">
                @foreach($brands as $brand)
                    @php
                        $logoUrl = $brand->brand_icon_direct_url
                            ?: ($brand->brand_icon
                                ? ($brand->brand_logo_s3
                                    ? Storage::disk('s3')->url($brand->brand_icon)
                                    : Storage::disk('public')->url($brand->brand_icon))
                                : null);
                    @endphp
                    <a href="{{ route('shop.brand', ['brand_slug' => $brand->slug]) }}"
                       wire:navigate
                       class="flex items-center gap-3 p-2.5 rounded-2xl text-slate-800 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-slate-700 border border-transparent hover:border-slate-100 dark:hover:border-slate-600 hover:shadow-sm transition duration-150 group">

                        <!-- Brand Logo Image (Only rendered if image uploaded) -->
                        @if($logoUrl && $brand->show_image)
                            <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200/60 dark:border-slate-600/60 overflow-hidden flex items-center justify-center text-slate-500 font-bold shrink-0 transition group-hover:scale-105">
                                <img src="{{ $logoUrl }}" alt="{{ $brand->name }}" class="w-full h-full object-contain p-1">
                            </div>
                        @endif

                        <div class="min-w-0">
                            <span class="block text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition truncate">{{ $brand->name }}</span>
                            @if($brand->description)
                                <span class="block text-[10px] text-slate-400 dark:text-slate-500 truncate max-w-[180px]">{{ $brand->description }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Mobile Accordion Menu -->
    <div x-show="open"
         x-collapse
         class="md:hidden mt-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-150/60 dark:border-slate-700/60 rounded-2xl p-3 space-y-2"
         style="display: none;">

        @if($brands->isEmpty())
            <div class="text-xs text-slate-400 dark:text-slate-500 py-1 px-3">@label('nav.brands_empty', 'No brands registered.')</div>
        @else
            <div class="grid grid-cols-1 gap-2 max-h-[14rem] overflow-y-auto pr-1 scrollbar-thin scroll-smooth overscroll-contain">
                @foreach($brands as $brand)
                    @php
                        $logoUrl = $brand->brand_icon_direct_url
                            ?: ($brand->brand_icon
                                ? ($brand->brand_logo_s3
                                    ? Storage::disk('s3')->url($brand->brand_icon)
                                    : Storage::disk('public')->url($brand->brand_icon))
                                : null);
                    @endphp
                    <a href="{{ route('shop.brand', ['brand_slug' => $brand->slug]) }}"
                       wire:navigate
                       class="flex items-center gap-3 p-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-slate-700 border border-transparent hover:border-slate-100 dark:hover:border-slate-600 transition group">

                        @if($logoUrl && $brand->show_image)
                            <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-700 border border-slate-200/60 dark:border-slate-600/60 overflow-hidden flex items-center justify-center text-slate-500 font-bold shrink-0">
                                <img src="{{ $logoUrl }}" alt="{{ $brand->name }}" class="w-full h-full object-contain p-1">
                            </div>
                        @endif

                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $brand->name }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
