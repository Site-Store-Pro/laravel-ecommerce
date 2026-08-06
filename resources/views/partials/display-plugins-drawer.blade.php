@php
    $displayPlugins = $displayPlugins ?? (\App\Models\Plugin::where('type', 'display')->get() ?? collect());
@endphp

    <!-- Slide-Out Plugins Library Drawer -->
    <div x-cloak 
         x-show="showPluginsPanel" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="translate-x-full" 
         x-on:click.outside="showPluginsPanel = false"
         class="fixed inset-y-0 right-0 w-80 bg-white border-l border-slate-200 z-50 shadow-2xl flex flex-col justify-between">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h4 class="text-sm font-extrabold text-slate-800">Plugins</h4>
                <p class="text-sm text-slate-400 font-bold uppercase tracking-wider mt-0.5">Drag to place or Click to append below</p>
            </div>
            <button type="button" 
                    x-on:click="showPluginsPanel = false" 
                    class="text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-lg hover:bg-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Instructions -->
        <div class="px-6 py-4 bg-emerald-50 border-b border-emerald-100 text-xs text-emerald-800 leading-relaxed font-medium">
            <p>Drag and drop a plugin card directly into the TinyMCE editor, or click a card to automatically append its shortcode block to the bottom of the page content.</p>
        </div>

        <!-- Plugins List (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4">
            @if($displayPlugins->isNotEmpty())
                @foreach($displayPlugins as $plugin)
                    <div draggable="true" 
                         x-on:dragstart="event.dataTransfer.setData('text/html', '[plugin:{{ $plugin->shortcode }}]'); event.dataTransfer.setData('text/plain', '[plugin:{{ $plugin->shortcode }}]')"
                         x-on:click="window.insertPluginShortcode('[plugin:{{ $plugin->shortcode }}]')"
                         class="p-4 bg-slate-50 hover:bg-white border border-slate-200 rounded-2xl hover:border-emerald-400 hover:shadow-md transition-all cursor-grab active:cursor-grabbing select-none group">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v2a2 2 0 11-4 0V4zm-4 4a2 2 0 114 0v12a2 2 0 100-4v-4a2 2 0 114 0v4a2 2 0 100 4v-8zm-2 4h4M9 16H5m14-4h-4m4 4h-4"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h5 class="text-xs font-bold text-slate-700 truncate group-hover:text-emerald-700 transition-colors">{{ $plugin->name }}</h5>
                                    <span class="text-4xs font-bold bg-slate-200 text-slate-500 px-1.5 py-0.5 rounded-full shrink-0">v{{ $plugin->version }}</span>
                                </div>
                                <p class="text-sm text-slate-400 mt-1 font-mono uppercase tracking-wider">[plugin:{{ $plugin->shortcode }}]</p>
                                <p class="text-3xs text-slate-500 mt-2 leading-relaxed line-clamp-3">{{ $plugin->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="py-12 text-center text-slate-400 space-y-2">
                    <svg class="w-8 h-8 mx-auto stroke-current opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-bold uppercase tracking-wider">No display plugins active</p>
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 text-center">
            <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Active Plugins Only</span>
        </div>
    </div>
