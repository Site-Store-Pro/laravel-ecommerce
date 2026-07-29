<div class="py-12" x-data="{ sidebarOpen: true }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.cms-list-menus.index') }}" wire:navigate 
                   class="inline-flex items-center justify-center p-2.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                        Edit List Menu: {{ $menuName }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Menu ID: <span class="bg-indigo-50 text-indigo-700 font-bold px-2 py-0.5 rounded text-xs">#{{ $menuId }}</span> | Shortcode: <span class="font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-xs select-all">[list:{{ $menuId }}]</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = !sidebarOpen" 
                        class="px-5 py-3 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-semibold bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 shadow-sm transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Toggle Search Tool</span>
                </button>
                <button wire:click="saveMenu" 
                        class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-6 py-3 rounded-2xl shadow-md shadow-indigo-100 hover:shadow-lg hover:shadow-indigo-200 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Save Menu Changes
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar Navigation -->
            <div class="lg:col-span-1">
                @include('layouts.cms-sidebar')
            </div>

            <!-- Main Form Section -->
            <div class="lg:col-span-3">
                <div class="flex flex-col xl:flex-row gap-8 items-start">
                    
                    <!-- Form inputs & list items -->
                    <div class="flex-1 space-y-6 w-full">
                        <!-- Menu Settings Card -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200/60 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm space-y-4">
                            <div>
                                <label for="menuName" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Menu Name</label>
                                <input wire:model="menuName" type="text" id="menuName" 
                                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
                                @error('menuName') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="customCss" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Custom CSS (Optional)</label>
                                <textarea wire:model="customCss" id="customCss" rows="5" placeholder="e.g. #cms-menu-{{ $menuId }} { list-style: none; }" 
                                          class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-2xl text-emerald-400 font-mono text-sm placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all spellcheck-false"></textarea>
                            </div>
                        </div>

                        <!-- Menu Items Card -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200/60 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Menu List Items</h3>
                                <button type="button" wire:click="addItem" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 rounded-xl text-xs font-bold bg-slate-50 dark:bg-slate-900 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add List Item
                                </button>
                            </div>

                            @if(empty($itemsData))
                                <div class="border border-dashed border-slate-200 dark:border-slate-700 rounded-2xl p-8 text-center text-slate-400">
                                    No items in this list menu. Click "Add List Item" to add one!
                                </div>
                            @else
                                <div 
                                    id="list-items-sortable" 
                                    class="space-y-4"
                                    x-data="{
                                        sortable: null,
                                        async init() {
                                            const { default: Sortable } = await import('https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/+esm');
                                            this.sortable = Sortable.create(this.$el, {
                                                animation: 200,
                                                handle: '.item-drag-handle',
                                                ghostClass: 'opacity-30',
                                                onEnd: () => {
                                                    const order = [...this.$el.querySelectorAll('[data-item-id]')]
                                                        .map(el => parseInt(el.dataset.itemId));
                                                    $wire.updateItemOrder(order);
                                                }
                                            });
                                        }
                                    }"
                                >
                                    @foreach($itemsData as $itemId => $item)
                                        <div data-item-id="{{ $itemId }}" 
                                             wire:key="list-item-{{ $itemId }}"
                                             class="flex gap-4 items-start p-4 bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200/50 dark:border-slate-700/50 rounded-2xl transition duration-150 hover:border-slate-300 dark:hover:border-slate-600">
                                            
                                            <!-- Drag Handle -->
                                            <div class="item-drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-500 shrink-0 mt-3.5">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                                </svg>
                                            </div>

                                            <!-- Content Input & Save Button -->
                                            <div class="flex-1 flex items-center gap-2">
                                                <input 
                                                    wire:model="itemsData.{{ $itemId }}.list_item" 
                                                    type="text" 
                                                    placeholder="Enter HTML link or drop a shortcode from the panel..." 
                                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm" 
                                                />
                                                <button 
                                                    type="button" 
                                                    wire:click="saveItem({{ $itemId }})"
                                                    wire:dirty.class.remove="invisible"
                                                    wire:target="itemsData.{{ $itemId }}.list_item"
                                                    class="invisible px-3.5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-xl text-xs font-bold shadow-sm transition-all inline-flex items-center gap-1 shrink-0"
                                                    title="Save item edits"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span>Save</span>
                                                </button>
                                            </div>

                                            <!-- Delete Button -->
                                            <button type="button" wire:click="removeItem({{ $itemId }})"
                                                    class="p-2.5 border border-slate-200 dark:border-slate-700 hover:border-rose-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-xl shrink-0 mt-0.5 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="text-xs text-slate-400 flex items-center gap-1.5 bg-slate-50 dark:bg-slate-900/30 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span><strong>Tips:</strong> Reordering list items by dragging their handles will automatically save the order. You can drag and drop shortcode blocks directly from the search drawer into list item inputs. Remember to click "Save Menu Changes" to save text edits.</span>
                            </div>
                        </div>
                    </div>

                    {{-- ─── Translations Section ──────────────────────────────────────────────── --}}
                    @if(isset($activeLanguages) && $activeLanguages->isNotEmpty() && !empty($itemsData))
                    <div x-data="{ tlOpen: false }" class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button type="button" @click="tlOpen = !tlOpen"
                                class="flex items-center justify-between w-full text-left bg-white/50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-700/80 shadow-sm transition-all hover:bg-white dark:hover:bg-slate-800">
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                Translations for Menu Items
                            </span>
                            <svg class="w-5 h-5 text-slate-400 transition-transform" :class="tlOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="tlOpen" x-cloak class="mt-4 space-y-6">
                            {{-- Language selector pills --}}
                            <div class="flex flex-wrap gap-2">
                                @foreach($activeLanguages as $lang)
                                    <button type="button"
                                            wire:click="selectTlLang({{ $lang->id }})"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold border transition
                                                {{ $tlLangId === $lang->id
                                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-md'
                                                    : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-indigo-400' }}">
                                        <span class="fi fi-{{ strtolower($lang->flag_emoji) }}" style="width:1em;height:0.75em;font-size:1.1rem;"></span>
                                        {{ $lang->name }}
                                    </button>
                                @endforeach
                            </div>

                            @if($tlLangId > 0)
                                <div class="grid gap-4">
                                    @foreach($itemsData as $itemId => $item)
                                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl p-5 border border-slate-200/60 dark:border-slate-700/80 shadow-sm" wire:key="tl-item-{{ $itemId }}">
                                            <div class="mb-4">
                                                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block mb-1">Default Label</span>
                                                <div class="text-sm font-semibold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-900 p-2.5 rounded-xl border border-slate-100 dark:border-slate-800">{{ $item['list_item'] ?? '(Empty)' }}</div>
                                            </div>

                                            <div>
                                                <label class="text-xs font-bold text-indigo-500 uppercase tracking-wider block mb-2">Translation (List Item)</label>
                                                <input type="text" wire:model="tlBuffer.{{ $itemId }}.list_item"
                                                       placeholder="Enter translated text..."
                                                       class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>

                                            <div class="flex items-center gap-3 pt-4 mt-4 border-t border-slate-100 dark:border-slate-700">
                                                <button type="button" wire:click="aiTlItem({{ $itemId }})"
                                                        wire:loading.attr="disabled" wire:target="aiTlItem({{ $itemId }})"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-violet-50 hover:bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400 dark:hover:bg-violet-900/50 text-xs font-bold rounded-xl transition disabled:opacity-60">
                                                    <span wire:loading.remove wire:target="aiTlItem({{ $itemId }})">
                                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L9.09 9.09 2 12l7.09 2.91L12 22l2.91-7.09L22 12l-7.09-2.91L12 2z"/></svg>
                                                    </span>
                                                    <span wire:loading wire:target="aiTlItem({{ $itemId }})" class="inline-flex">
                                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                    </span>
                                                    AI Translate
                                                </button>
                                                <button type="button" wire:click="saveTlItem({{ $itemId }})"
                                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                                                    Save Translation
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Slide out Panel / Search tool (Sticky container on desktop) -->
                    <div 
                        x-show="sidebarOpen"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-12"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 translate-x-12"
                        class="w-full xl:w-80 shrink-0 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 shadow-sm sticky top-24 self-start max-h-[85vh] overflow-y-auto space-y-4"
                    >
                        <div class="flex items-center justify-between pb-2 border-b dark:border-slate-700">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wide">Shortcode Generator</h4>
                            <button type="button" @click="sidebarOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Scope selector -->
                        <div class="grid grid-cols-5 gap-1 bg-slate-100 dark:bg-slate-900 p-1 rounded-xl text-center text-xs">
                            <button type="button" wire:click="$set('searchScope', 'all')" class="py-1.5 rounded-lg {{ $searchScope === 'all' ? 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">All</button>
                            <button type="button" wire:click="$set('searchScope', 'pages')" class="py-1.5 rounded-lg {{ $searchScope === 'pages' ? 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Pages</button>
                            <button type="button" wire:click="$set('searchScope', 'products')" class="py-1.5 rounded-lg {{ $searchScope === 'products' ? 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Prods</button>
                            <button type="button" wire:click="$set('searchScope', 'categories')" class="py-1.5 rounded-lg {{ $searchScope === 'categories' ? 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Cats</button>
                            <button type="button" wire:click="$set('searchScope', 'brands')" class="py-1.5 rounded-lg {{ $searchScope === 'brands' ? 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Brnd</button>
                        </div>

                        <!-- Live Search Input -->
                        <div class="relative">
                            <input 
                                wire:model.live.debounce.300ms="searchQuery" 
                                type="text" 
                                placeholder="Search products, pages..." 
                                class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-xs" 
                            />
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                        </div>

                        <!-- Search results -->
                        <div class="space-y-2 mt-4 max-h-[450px] overflow-y-auto pr-1">
                            @if(empty($searchQuery))
                                <div class="text-center text-xs text-slate-400 py-6">
                                    Type a search query to locate items and generate shortcodes.
                                </div>
                            @else
                                @forelse($searchResults as $result)
                                    <div 
                                        draggable="true" 
                                        ondragstart="event.dataTransfer.setData('text/plain', '{{ $result['shortcode'] }}')"
                                        class="cursor-grab active:cursor-grabbing p-3 bg-slate-50 dark:bg-slate-900 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 border border-slate-200 dark:border-slate-700 hover:border-indigo-200 rounded-xl text-xs transition-all flex flex-col gap-1.5"
                                        x-data="{ copied: false }"
                                    >
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-slate-700 dark:text-slate-300 truncate max-w-[150px]">{{ $result['title'] }}</span>
                                            <span class="px-2 py-0.5 text-2xs font-extrabold uppercase rounded-full border {{ $result['badgeColor'] }} shrink-0">
                                                {{ $result['type'] }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between gap-2 bg-white dark:bg-slate-800 p-1.5 rounded-lg border border-slate-100 dark:border-slate-700 mt-1">
                                            <span class="font-mono text-slate-500 dark:text-slate-400 text-3xs select-all truncate">{{ $result['shortcode'] }}</span>
                                            <button type="button" 
                                                    @click="navigator.clipboard.writeText('{{ $result['shortcode'] }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                    class="text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-400 transition-colors inline-flex items-center shrink-0">
                                                <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                <svg x-show="copied" x-cloak class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-xs text-slate-400 py-6">
                                        No matching pages, products, categories, or brands found.
                                    </div>
                                @endforelse
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>
