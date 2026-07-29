    <!-- Slide-Out Link Generator Drawer -->
    <div x-cloak 
         x-show="showLinkGenerator" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="translate-x-full" 
         x-on:click.outside="showLinkGenerator = false"
         class="fixed inset-y-0 right-0 w-85 bg-white border-l border-slate-200 z-50 shadow-2xl flex flex-col justify-between"
         x-data="{ searchTab: 'product', selectedRecord: null }">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h4 class="text-sm font-extrabold text-slate-800">Link Generator</h4>
                <p class="text-sm text-slate-400 font-bold uppercase tracking-wider mt-0.5">Find records & copy public links</p>
            </div>
            <button type="button" 
                    x-on:click="showLinkGenerator = false" 
                    class="text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-lg hover:bg-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Tabs Navigation -->
        <div class="px-6 pt-4 bg-slate-50/30 border-b border-slate-100">
            <div class="flex border-b border-slate-200 text-sm font-bold uppercase tracking-wider">
                <button type="button" 
                        x-on:click="searchTab = 'product'" 
                        class="flex-1 pb-3 text-center transition-all border-b-2"
                        x-bind:class="searchTab === 'product' ? 'border-orange-500 text-orange-500' : 'border-transparent text-slate-400 hover:text-slate-600'">
                    Product
                </button>
                <button type="button" 
                        x-on:click="searchTab = 'brand'" 
                        class="flex-1 pb-3 text-center transition-all border-b-2"
                        x-bind:class="searchTab === 'brand' ? 'border-orange-500 text-orange-500' : 'border-transparent text-slate-400 hover:text-slate-600'">
                    Brand
                </button>
                <button type="button" 
                        x-on:click="searchTab = 'category'" 
                        class="flex-1 pb-3 text-center transition-all border-b-2"
                        x-bind:class="searchTab === 'category' ? 'border-orange-500 text-orange-500' : 'border-transparent text-slate-400 hover:text-slate-600'">
                    Category
                </button>
                <button type="button" 
                        x-on:click="searchTab = 'page'" 
                        class="flex-1 pb-3 text-center transition-all border-b-2"
                        x-bind:class="searchTab === 'page' ? 'border-orange-500 text-orange-500' : 'border-transparent text-slate-400 hover:text-slate-600'">
                    CMS Page
                </button>
            </div>
        </div>

        <!-- Search Fields & Results (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4">
            
            <!-- Live Search Inputs -->
            <div>
                <!-- Product Search -->
                <div x-show="searchTab === 'product'" class="relative">
                    <input type="text" 
                           wire:model.live.debounce.250ms="searchProduct" 
                           placeholder="Type product name or slug..." 
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Brand Search -->
                <div x-show="searchTab === 'brand'" class="relative">
                    <input type="text" 
                           wire:model.live.debounce.250ms="searchBrand" 
                           placeholder="Type brand name or slug..." 
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Category Search -->
                <div x-show="searchTab === 'category'" class="relative">
                    <input type="text" 
                           wire:model.live.debounce.250ms="searchCategory" 
                           placeholder="Type category name or slug..." 
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- CMS Page Search -->
                <div x-show="searchTab === 'page'" class="relative">
                    <input type="text" 
                           wire:model.live.debounce.250ms="searchPage" 
                           placeholder="Type CMS page title or slug..." 
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Live Search Results -->
            <div class="space-y-2">
                <!-- Product Results -->
                <div x-show="searchTab === 'product'">
                    @if(!empty($searchProduct) && count($searchedProducts) > 0)
                        <div class="space-y-1.5">
                            @foreach($searchedProducts as $prod)
                                <button type="button" 
                                        x-on:click="selectedRecord = { type: 'Product', title: '{{ addslashes($prod->title) }}', slug: '{{ addslashes($prod->seo_slug) }}', url: '{{ url('/items/' . $prod->seo_slug) }}' }"
                                        class="w-full text-left p-3 bg-slate-50 hover:bg-violet-50 border border-slate-200 hover:border-violet-300 rounded-xl transition-all flex flex-col gap-0.5">
                                    <span class="text-xs font-bold text-slate-700">{{ $prod->title }}</span>
                                    <span class="text-4xs font-mono text-slate-400">/items/{{ $prod->seo_slug }}</span>
                                </button>
                            @endforeach
                        </div>
                    @elseif(!empty($searchProduct))
                        <p class="text-3xs text-slate-400 italic text-center py-4">No matching products found</p>
                    @else
                        <p class="text-3xs text-slate-400 italic text-center py-4">Start typing to search products...</p>
                    @endif
                </div>

                <!-- Brand Results -->
                <div x-show="searchTab === 'brand'">
                    @if(!empty($searchBrand) && count($searchedBrands) > 0)
                        <div class="space-y-1.5">
                            @foreach($searchedBrands as $brand)
                                <button type="button" 
                                        x-on:click="selectedRecord = { type: 'Brand', title: '{{ addslashes($brand->name) }}', slug: '{{ addslashes($brand->slug) }}', url: '{{ url('/brands/' . $brand->slug) }}' }"
                                        class="w-full text-left p-3 bg-slate-50 hover:bg-violet-50 border border-slate-200 hover:border-violet-300 rounded-xl transition-all flex flex-col gap-0.5">
                                    <span class="text-xs font-bold text-slate-700">{{ $brand->name }}</span>
                                    <span class="text-4xs font-mono text-slate-400">/brands/{{ $brand->slug }}</span>
                                </button>
                            @endforeach
                        </div>
                    @elseif(!empty($searchBrand))
                        <p class="text-3xs text-slate-400 italic text-center py-4">No matching brands found</p>
                    @else
                        <p class="text-3xs text-slate-400 italic text-center py-4">Start typing to search brands...</p>
                    @endif
                </div>

                <!-- Category Results -->
                <div x-show="searchTab === 'category'">
                    @if(!empty($searchCategory) && count($searchedCategories) > 0)
                        <div class="space-y-1.5">
                            @foreach($searchedCategories as $cat)
                                <button type="button" 
                                        x-on:click="selectedRecord = { type: 'Category', title: '{{ addslashes($cat->name) }}', slug: '{{ addslashes($cat->slug) }}', url: '{{ url('/section/' . $cat->slug) }}' }"
                                        class="w-full text-left p-3 bg-slate-50 hover:bg-violet-50 border border-slate-200 hover:border-violet-300 rounded-xl transition-all flex flex-col gap-0.5">
                                    <span class="text-xs font-bold text-slate-700">{{ $cat->name }}</span>
                                    <span class="text-4xs font-mono text-slate-400">/section/{{ $cat->slug }}</span>
                                </button>
                            @endforeach
                        </div>
                    @elseif(!empty($searchCategory))
                        <p class="text-3xs text-slate-400 italic text-center py-4">No matching categories found</p>
                    @else
                        <p class="text-3xs text-slate-400 italic text-center py-4">Start typing to search categories...</p>
                    @endif
                </div>

                <!-- CMS Page Results -->
                <div x-show="searchTab === 'page'">
                    @if(!empty($searchPage) && count($searchedPages) > 0)
                        <div class="space-y-1.5">
                            @foreach($searchedPages as $page)
                                <button type="button" 
                                        x-on:click="selectedRecord = { type: 'CMS Page', title: '{{ addslashes($page->title) }}', slug: '{{ addslashes($page->slug) }}', url: '{{ url('/' . $page->slug) }}' }"
                                        class="w-full text-left p-3 bg-slate-50 hover:bg-violet-50 border border-slate-200 hover:border-violet-300 rounded-xl transition-all flex flex-col gap-0.5">
                                    <span class="text-xs font-bold text-slate-700">{{ $page->title }}</span>
                                    <span class="text-4xs font-mono text-slate-400">/{{ $page->slug }}</span>
                                </button>
                            @endforeach
                        </div>
                    @elseif(!empty($searchPage))
                        <p class="text-3xs text-slate-400 italic text-center py-4">No matching CMS pages found</p>
                    @else
                        <p class="text-3xs text-slate-400 italic text-center py-4">Start typing to search CMS pages...</p>
                    @endif
                </div>
            </div>

            <!-- Selected Record Section -->
            <div x-show="selectedRecord" 
                 x-data="{ copiedType: null }" 
                 class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-sm font-extrabold bg-violet-100 text-violet-700 px-2 py-0.5 rounded-full uppercase tracking-wider inline-block mb-1" x-text="selectedRecord?.type"></span>
                        <h5 class="text-xs font-bold text-slate-700 truncate" x-text="selectedRecord?.title"></h5>
                        <p class="text-3xs text-slate-400 font-mono mt-0.5 truncate" x-text="selectedRecord?.slug"></p>
                    </div>
                    <button type="button" 
                            x-on:click="selectedRecord = null"
                            class="text-slate-400 hover:text-slate-600 transition-colors shrink-0 p-1.5 rounded-lg hover:bg-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-2 pt-3 border-t border-slate-200">
                    <p class="text-sm text-slate-400 font-bold uppercase tracking-wider mb-2">Drag item to editor or click to append</p>

                    <!-- Copy Full URL -->
                    <button type="button" 
                            x-on:click="navigator.clipboard.writeText(selectedRecord?.url ?? ''); copiedType = 'url'; setTimeout(() => { if (copiedType === 'url') copiedType = null; }, 2000)" 
                            class="w-full text-left px-3 py-2.5 bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl text-3xs font-bold flex items-center justify-between gap-2 transition-colors relative group">
                        <span class="truncate">Copy Full URL</span>
                        <span x-show="copiedType === 'url'" class="text-emerald-600 font-bold">Copied!</span>
                        <svg x-show="copiedType !== 'url'" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    </button>

                    <!-- Drag & Click to Append HTML Anchor Link -->
                    <div draggable="true" 
                         x-on:dragstart="
                            event.dataTransfer.setData('text/html', '<a href=\'' + (selectedRecord?.url ?? '') + '\'>' + (selectedRecord?.title ?? '') + '</a>');
                            event.dataTransfer.setData('text/plain', '<a href=\'' + (selectedRecord?.url ?? '') + '\'>' + (selectedRecord?.title ?? '') + '</a>');
                         "
                         x-on:click="window.insertHtmlWidget('<a href=\'' + (selectedRecord?.url ?? '') + '\'>' + (selectedRecord?.title ?? '') + '</a>'); copiedType = 'html'; setTimeout(() => { if (copiedType === 'html') copiedType = null; }, 2000)" 
                         class="w-full text-left px-3 py-2.5 bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl text-3xs font-bold flex items-center justify-between gap-2 transition-colors cursor-grab active:cursor-grabbing relative group select-none">
                        <span class="truncate flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            Drag / Click to Append Anchor Link
                        </span>
                        <span x-show="copiedType === 'html'" class="text-emerald-600 font-bold">Appended!</span>
                        <svg x-show="copiedType !== 'html'" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    </div>

                    <!-- Drag & Click to Append CTA Button Link -->
                    <div draggable="true" 
                         x-on:dragstart="
                            event.dataTransfer.setData('text/html', '<a href=\'' + (selectedRecord?.url ?? '') + '\'><button type=\'button\' class=\'btn-theme-primary\'>' + (selectedRecord?.title ?? '') + '</button></a>');
                            event.dataTransfer.setData('text/plain', '<a href=\'' + (selectedRecord?.url ?? '') + '\'><button type=\'button\' class=\'btn-theme-primary\'>' + (selectedRecord?.title ?? '') + '</button></a>');
                         "
                         x-on:click="window.insertHtmlWidget('<a href=\'' + (selectedRecord?.url ?? '') + '\'><button type=\'button\' class=\'btn-theme-primary\'>' + (selectedRecord?.title ?? '') + '</button></a>'); copiedType = 'cta'; setTimeout(() => { if (copiedType === 'cta') copiedType = null; }, 2000)" 
                         class="w-full text-left px-3 py-2.5 bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl text-3xs font-bold flex items-center justify-between gap-2 transition-colors cursor-grab active:cursor-grabbing relative group select-none">
                        <span class="truncate flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            Drag / Click to Append CTA Button
                        </span>
                        <span x-show="copiedType === 'cta'" class="text-emerald-600 font-bold">Appended!</span>
                        <svg x-show="copiedType !== 'cta'" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    </div>
                </div>
            </div>

        </div>
    </div>
