<div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative inline-block text-left">
    <!-- Trigger Button -->
    <button @click="open = !open" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-600 hover:text-indigo-600 focus:outline-none transition-colors py-2">
        <span>Categories</span>
        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180 text-indigo-600': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
         class="absolute left-0 mt-1 w-64 bg-white/95 border border-slate-200/80 rounded-2xl shadow-xl shadow-slate-100/50 py-3 z-50 backdrop-blur-md"
         style="display: none;">
        
        @if($categories->isEmpty())
            <div class="px-4 py-2 text-xs text-slate-400">No categories found.</div>
        @else
            <div class="space-y-1 max-h-[28rem] overflow-y-auto px-2 scrollbar-thin">
                @foreach($categories as $category)
                    <div class="group/item py-0.5">
                        <!-- Top-level Category Link -->
                        <a href="{{ route('shop.category', ['category_slug' => $category->slug]) }}" wire:navigate class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-bold text-slate-800 hover:bg-indigo-50/50 hover:text-indigo-600 transition duration-150">
                            <span>{{ $category->name }}</span>
                            @if($category->children->isNotEmpty())
                                <svg class="w-3 h-3 text-slate-400 group-hover/item:text-indigo-500 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            @endif
                        </a>

                        <!-- Children (Subcategories) display -->
                        @if($category->children->isNotEmpty())
                            <div class="pl-4 pr-2 py-1 space-y-1 border-l border-slate-150 ml-3">
                                @foreach($category->children as $child)
                                    <a href="{{ route('shop.category', ['category_slug' => $child->slug]) }}" wire:navigate class="block px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
