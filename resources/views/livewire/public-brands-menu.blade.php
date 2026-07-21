<div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative inline-block text-left">
    <!-- Desktop Trigger -->
    <button @click="open = !open" class="hidden md:inline-flex items-center gap-1 text-sm font-semibold text-slate-600 hover:text-indigo-600 focus:outline-none transition-colors py-2">
        <span>Brands</span>
        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180 text-indigo-600': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Mobile Trigger -->
    <button @click="open = !open" class="md:hidden flex w-full items-center justify-between px-3 py-2 rounded-xl text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition focus:outline-none">
        <span>Brands</span>
        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180 text-indigo-600': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
         class="hidden md:block absolute left-0 mt-1 w-[32rem] bg-white/95 border border-slate-200/80 rounded-3xl shadow-xl shadow-slate-100/50 py-6 px-6 z-50 backdrop-blur-md"
         style="display: none;">
        
        <div class="mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Shop by Brand</h3>
        </div>

        @if($brands->isEmpty())
            <div class="text-xs text-slate-400 py-2">No brands registered.</div>
        @else
            <div class="grid grid-cols-2 gap-4 max-h-[24rem] overflow-y-auto pr-1 scrollbar-thin">
                @foreach($brands as $brand)
                    @php
                        $logoUrl = $brand->brand_icon 
                            ? ($brand->brand_logo_s3 
                                ? Storage::disk('s3')->url($brand->brand_icon) 
                                : Storage::disk('public')->url($brand->brand_icon))
                            : null;
                    @endphp
                    <a href="{{ route('shop.brand', ['brand_slug' => $brand->slug]) }}" 
                       wire:navigate 
                       class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 hover:shadow-sm transition duration-150 group">
                        
                        <!-- Brand Logo / Initials -->
                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200/60 overflow-hidden flex items-center justify-center text-slate-500 font-bold shrink-0 transition group-hover:scale-105">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $brand->name }}" class="w-full h-full object-contain p-1">
                            @else
                                <span class="text-slate-400 text-sm capitalize">{{ substr($brand->name, 0, 1) }}</span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <span class="block text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition truncate">{{ $brand->name }}</span>
                            @if($brand->description)
                                <span class="block text-[10px] text-slate-400 truncate max-w-[180px]">{{ $brand->description }}</span>
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
         class="md:hidden mt-2 bg-slate-50 border border-slate-150/60 rounded-2xl p-3 space-y-2"
         style="display: none;">
        
        @if($brands->isEmpty())
            <div class="text-xs text-slate-400 py-1 px-3">No brands registered.</div>
        @else
            <div class="grid grid-cols-1 gap-2 max-h-[16rem] overflow-y-auto pr-1">
                @foreach($brands as $brand)
                    @php
                        $logoUrl = $brand->brand_icon 
                            ? ($brand->brand_logo_s3 
                                ? Storage::disk('s3')->url($brand->brand_icon) 
                                : Storage::disk('public')->url($brand->brand_icon))
                            : null;
                    @endphp
                    <a href="{{ route('shop.brand', ['brand_slug' => $brand->slug]) }}" 
                       wire:navigate 
                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-white border border-transparent hover:border-slate-100 transition">
                        
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200/60 overflow-hidden flex items-center justify-center text-slate-500 font-bold shrink-0">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $brand->name }}" class="w-full h-full object-contain p-1">
                            @else
                                <span class="text-slate-400 text-xs capitalize">{{ substr($brand->name, 0, 1) }}</span>
                            @endif
                        </div>

                        <span class="text-xs font-bold text-slate-700">{{ $brand->name }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
