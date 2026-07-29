<div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative inline-block text-left w-full md:w-auto">
    <!-- Desktop Trigger -->
    <button @click="open = !open" class="hidden md:inline-flex items-center gap-1 text-sm font-semibold text-slate-600 hover:text-indigo-600 focus:outline-none transition-colors py-2">
        <span>@if(!empty($label)) {{ $label }} @else @label('nav.categories_fallback', 'Categories') @endif</span>
        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180 text-indigo-600': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Mobile Trigger -->
    <button @click="open = !open" class="md:hidden flex w-full items-center justify-between py-2 rounded-xl text-base font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 transition focus:outline-none">
        <span>@if(!empty($label)) {{ $label }} @else @label('nav.categories_fallback', 'Categories') @endif</span>
        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180 text-indigo-600': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Desktop Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
         class="hidden md:block absolute left-0 mt-1 w-64 bg-white/95 dark:bg-slate-800/95 border border-slate-200/80 dark:border-slate-700/80 rounded-2xl shadow-xl shadow-slate-100/50 dark:shadow-none py-3 z-50 backdrop-blur-md"
         style="display: none;">
        
        @if($categories->isEmpty())
            <div class="px-4 py-2 text-xs text-slate-400">@label('nav.categories_empty', 'No categories found.')</div>
        @else
            <div class="space-y-1 max-h-[28rem] overflow-y-auto px-2 scrollbar-thin">
                @foreach($categories as $category)
                    <div class="group/item py-0.5">
                        <!-- Top-level Category Link -->
                        <a href="{{ route('shop.category', ['category_slug' => $category->slug]) }}" wire:navigate class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-bold text-slate-800 dark:text-slate-100 hover:bg-indigo-50/50 dark:hover:bg-slate-700 hover:text-indigo-600 transition duration-150">
                            <span>{{ $category->name }}</span>
                            @if($category->children->isNotEmpty())
                                <svg class="w-3 h-3 text-slate-400 group-hover/item:text-indigo-500 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            @endif
                        </a>

                        <!-- Children (Subcategories) display -->
                        @if($category->children->isNotEmpty())
                            <div class="pl-4 pr-2 py-1 space-y-1 border-l border-slate-150 dark:border-slate-700 ml-3">
                                @foreach($category->children as $child)
                                    <a href="{{ route('shop.category', ['category_slug' => $child->slug]) }}" wire:navigate class="block px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 transition">
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

    <!-- Mobile Accordion Menu -->
    <div x-show="open" 
         x-collapse 
         class="md:hidden mt-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-150/60 dark:border-slate-700/60 rounded-2xl p-3 space-y-2"
         style="display: none;">
        @if($categories->isEmpty())
            <div class="text-xs text-slate-400 py-1 px-3">@label('nav.categories_empty', 'No categories found.')</div>
        @else
            <div class="space-y-1 max-h-[20rem] overflow-y-auto pr-1">
                @foreach($categories as $category)
                    <div class="py-1">
                        <a href="{{ route('shop.category', ['category_slug' => $category->slug]) }}" wire:navigate class="block px-3 py-1.5 rounded-lg text-sm font-bold text-slate-800 dark:text-slate-100 hover:text-indigo-600">
                            {{ $category->name }}
                        </a>
                        @if($category->children->isNotEmpty())
                            <div class="pl-4 space-y-1 border-l border-slate-200 dark:border-slate-700 ml-3 mt-1">
                                @foreach($category->children as $child)
                                    <a href="{{ route('shop.category', ['category_slug' => $child->slug]) }}" wire:navigate class="block px-3 py-1 rounded-md text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600">
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
