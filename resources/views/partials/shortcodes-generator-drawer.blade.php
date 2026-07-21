    <!-- Slide-Out Shortcode Generator Drawer -->
    <div x-cloak 
         x-show="showShortcodeGenerator" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="translate-x-full" 
         x-on:click.outside="showShortcodeGenerator = false"
         class="fixed inset-y-0 right-0 w-80 bg-white border-l border-slate-200 z-50 shadow-2xl flex flex-col justify-between">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h4 class="text-sm font-extrabold text-slate-800">Shortcode Generator</h4>
                <p class="text-sm text-slate-400 font-bold uppercase tracking-wider mt-0.5">Find items & copy shortcodes</p>
            </div>
            <button type="button" 
                    x-on:click="showShortcodeGenerator = false" 
                    class="text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-lg hover:bg-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Scope selector -->
        <div class="px-6 pt-4 bg-slate-50/30 border-b border-slate-100 space-y-4">
            <div class="grid grid-cols-6 gap-1 bg-slate-100 p-1 rounded-xl text-center text-[10px] font-bold uppercase tracking-wider">
                <button type="button" wire:click="$set('shortcodeSearchScope', 'all')" class="py-1.5 rounded-lg {{ $shortcodeSearchScope === 'all' ? 'bg-white text-slate-800 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">All</button>
                <button type="button" wire:click="$set('shortcodeSearchScope', 'pages')" class="py-1.5 rounded-lg {{ $shortcodeSearchScope === 'pages' ? 'bg-white text-slate-800 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Pages</button>
                <button type="button" wire:click="$set('shortcodeSearchScope', 'products')" class="py-1.5 rounded-lg {{ $shortcodeSearchScope === 'products' ? 'bg-white text-slate-800 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Prods</button>
                <button type="button" wire:click="$set('shortcodeSearchScope', 'categories')" class="py-1.5 rounded-lg {{ $shortcodeSearchScope === 'categories' ? 'bg-white text-slate-800 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Cats</button>
                <button type="button" wire:click="$set('shortcodeSearchScope', 'brands')" class="py-1.5 rounded-lg {{ $shortcodeSearchScope === 'brands' ? 'bg-white text-slate-800 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Brnd</button>
                <button type="button" wire:click="$set('shortcodeSearchScope', 'downloads')" class="py-1.5 rounded-lg {{ $shortcodeSearchScope === 'downloads' ? 'bg-white text-teal-700 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">DLs</button>
            </div>

            <!-- Live Search Input -->
            <div class="relative pb-4">
                <input 
                    wire:model.live.debounce.300ms="shortcodeSearchQuery" 
                    type="text" 
                    placeholder="Search products, pages..." 
                    class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors" 
                />
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- Search results -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4">
            <p class="text-2xs text-slate-400 font-medium leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                Drag a shortcode card directly into the editor, or click to copy/append it as a text block.
            </p>

            <div class="space-y-2">
                @if(empty($shortcodeSearchQuery))
                    <div class="text-center text-xs text-slate-400 py-8">
                        Type a search query to locate items.
                    </div>
                @else
                    @forelse($shortcodeSearchResults as $result)
                        <div 
                            draggable="true" 
                            ondragstart="
                                event.dataTransfer.setData('text/plain', '{{ $result['shortcode'] }}');
                                event.dataTransfer.setData('text/html', '{{ $result['shortcode'] }}');
                            "
                            x-data="{ copied: false }"
                            class="cursor-grab active:cursor-grabbing p-3 bg-slate-50 hover:bg-violet-50 border border-slate-200 hover:border-violet-300 rounded-xl text-xs transition-all flex flex-col gap-1.5"
                        >
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-700 truncate max-w-[150px]">{{ $result['title'] }}</span>
                                <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-full border {{ $result['badgeColor'] }} shrink-0">
                                    {{ $result['type'] }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between gap-2 bg-white p-1.5 rounded-lg border border-slate-100 mt-1">
                                <span class="font-mono text-slate-500 text-[10px] select-all truncate">{{ $result['shortcode'] }}</span>
                                <div class="flex items-center gap-1 shrink-0">
                                    <!-- Copy -->
                                    <button type="button" 
                                            @click="navigator.clipboard.writeText('{{ $result['shortcode'] }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                            class="text-indigo-600 hover:text-indigo-800 transition-colors p-1"
                                            title="Copy shortcode text">
                                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <svg x-show="copied" x-cloak class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <!-- Append -->
                                    <button type="button"
                                            @click="window.insertHtmlWidget('<p>{{ $result['shortcode'] }}</p>');"
                                            class="text-violet-600 hover:text-violet-800 transition-colors p-1"
                                            title="Insert shortcode into editor">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-xs text-slate-400 py-8">
                            No matching items found.
                        </div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>
