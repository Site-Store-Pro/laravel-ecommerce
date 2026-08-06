<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-screen-xl mx-auto space-y-6">

        {{-- ── Header ─────────────────────────────────────────────── --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Plugin Manager
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Manage display and shipping plugins. Use <code class="bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded text-indigo-600 dark:text-indigo-400 font-mono text-xs">[plugin:shortcode]</code> shortcodes to embed plugins in CMS pages.
                </p>
            </div>
        </div>

        {{-- ── Filter Bar ──────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search plugins…"
                class="flex-1 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl text-sm focus:outline-none focus:border-indigo-500 shadow-sm"
            >
            <select
                wire:model.live="filterType"
                class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-2xl text-sm focus:outline-none focus:border-indigo-500 shadow-sm"
            >
                <option value="">All Types</option>
                @foreach($pluginTypes as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>

        {{-- ── Main Layout: List + Panel ───────────────────────────── --}}
        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- ── Plugin List ──────────────────────────────────────── --}}
            <div class="w-full {{ $selectedPlugin ? 'lg:w-1/2 xl:w-5/12' : 'lg:w-full' }} bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden transition-all duration-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900/40">
                                <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Plugin</th>
                                <th class="text-center px-4 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Type</th>
                                <th class="text-left px-4 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Shortcode</th>
                                <th class="text-center px-4 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ver.</th>
                                <th class="text-center px-4 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Active</th>
                                <th class="px-4 py-3.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                            @forelse($filteredPlugins as $plugin)
                                @php
                                    $typeBadge = match($plugin->type) {
                                        'display'           => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300',
                                        'shipping'          => 'bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300',
                                        'email'             => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300',
                                        'images'            => 'bg-purple-100 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300',
                                        default             => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                                    };
                                    $isSelected = $selectedPlugin && $selectedPlugin->id === $plugin->id;
                                    $showShortcode = in_array($plugin->type, ['display', 'quickcart', 'searchbar', 'checkout-features', 'product-features']);
                                @endphp
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition-colors {{ $isSelected ? 'bg-indigo-50/60 dark:bg-indigo-950/20' : '' }}">
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-800 dark:text-slate-100">{{ $plugin->name }}</div>
                                        @if($plugin->description)
                                            <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 max-w-xs truncate">{{ $plugin->description }}</div>
                                        @endif
                                        @if($plugin->author)
                                            <div class="text-xs text-slate-400 dark:text-slate-500">by {{ $plugin->author }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full uppercase tracking-wide {{ $typeBadge }}">
                                            {{ $plugin->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($showShortcode && $plugin->shortcode)
                                            <code class="text-xs font-mono bg-slate-100 dark:bg-slate-700/60 text-indigo-600 dark:text-indigo-400 px-2 py-1 rounded-lg whitespace-nowrap">[plugin:{{ $plugin->shortcode }}]</code>
                                        @else
                                            <span class="text-slate-300 dark:text-slate-600">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center text-xs text-slate-500 dark:text-slate-400 font-medium">
                                        {{ $plugin->version ?? '—' }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <button
                                            wire:click="toggleActive({{ $plugin->id }})"
                                            wire:loading.attr="disabled"
                                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none {{ $plugin->activation_status ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-600' }}"
                                        >
                                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform {{ $plugin->activation_status ? 'translate-x-4' : 'translate-x-1' }}"></span>
                                        </button>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <button
                                            wire:click="selectPlugin({{ $plugin->id }})"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-xl transition-all {{ $isSelected ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 hover:text-indigo-600 dark:hover:text-indigo-400' }}"
                                        >
                                            Settings
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                                        No plugins found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Settings Panel ───────────────────────────────────── --}}
            @if($selectedPlugin)
            <div class="w-full lg:flex-1 bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden"
                 x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">

                {{-- Panel Header --}}
                <div class="flex items-start justify-between px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ $selectedPlugin->name }}</h2>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">v{{ $selectedPlugin->version ?? '—' }} &middot; {{ ucfirst($selectedPlugin->type) }}</p>
                    </div>
                    <button wire:click="closePanel" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Tabs --}}
                <div class="flex gap-1 px-6 pt-4 border-b border-slate-100 dark:border-slate-700">
                    <button wire:click="setTab('settings')"
                        class="px-4 py-2 text-xs font-bold rounded-t-xl transition-colors {{ $activeTab === 'settings' ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                        Settings
                    </button>
                    <button wire:click="setTab('instructions')"
                        class="px-4 py-2 text-xs font-bold rounded-t-xl transition-colors {{ $activeTab === 'instructions' ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                        Usage
                    </button>
                    @if($selectedPlugin->activation_required === 'yes')
                    <button wire:click="setTab('activation')"
                        class="px-4 py-2 text-xs font-bold rounded-t-xl transition-colors {{ $activeTab === 'activation' ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                        Activation
                    </button>
                    @endif
                    @php $translatableFields = $selectedPlugin->getTranslatableFields(); @endphp
                    @if(!empty($translatableFields))
                    <button wire:click="setTab('translations')"
                        class="px-4 py-2 text-xs font-bold rounded-t-xl transition-colors {{ $activeTab === 'translations' ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                        🌐 Translations
                    </button>
                    @endif
                </div>

                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">

                    {{-- ── Success / Error alerts ── --}}
                    @if($successMessage)
                        <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-sm text-emerald-700 dark:text-emerald-300 font-semibold">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $successMessage }}
                        </div>
                    @endif
                    @if($errorMessage)
                        <div class="flex items-center gap-3 px-4 py-3 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 rounded-2xl text-sm text-rose-700 dark:text-rose-300 font-semibold">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $errorMessage }}
                        </div>
                    @endif

                    {{-- ════════════════════════════════════════════════ --}}
                    {{-- SETTINGS TAB                                      --}}
                    {{-- ════════════════════════════════════════════════ --}}
                    @if($activeTab === 'settings')
                        @php $schema = $selectedPlugin->getOptionsSchema(); @endphp

                        @if($schema->isEmpty())
                            <p class="text-sm text-slate-400 dark:text-slate-500 py-4">This plugin has no configurable settings.</p>
                        @else
                            <div class="space-y-5">
                                @foreach($schema as $option)
                                    @php
                                        $fieldName = $option->field_name;
                                        $currentValue = $settings[$fieldName] ?? $option->field_default_value ?? '';
                                    @endphp

                                    <div class="space-y-1.5">
                                        {{-- Label row --}}
                                        <div class="flex items-center gap-2">
                                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                                {{ $option->field_label }}
                                            </label>
                                            @if($option->field_required === 'yes')
                                                <span class="text-xs text-rose-500 font-bold">* Required</span>
                                            @endif
                                        </div>

                                        {{-- Help text --}}
                                        @if($option->field_help)
                                            <p class="text-xs text-slate-400 dark:text-slate-500">{{ $option->field_help }}</p>
                                        @endif

                                        {{-- ── Field type rendering ── --}}

                                        @if($option->field_type === 'input')
                                            <input
                                                type="text"
                                                wire:model="settings.{{ $fieldName }}"
                                                value="{{ $currentValue }}"
                                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 font-mono"
                                            >

                                        @elseif($option->field_type === 'textarea' && $option->field_editor === 'css')
                                            {{-- CSS code editor --}}
                                            <div class="relative">
                                                <div class="absolute top-2 right-3 text-xs text-slate-500 font-mono pointer-events-none">CSS</div>
                                                <textarea
                                                    wire:model="settings.{{ $fieldName }}"
                                                    rows="18"
                                                    id="plugin_field_{{ $selectedPlugin->id }}_{{ $fieldName }}"
                                                    class="w-full font-mono text-xs bg-slate-900 dark:bg-slate-950 text-emerald-300 p-4 rounded-xl border border-slate-600 dark:border-slate-700 resize-y focus:outline-none focus:border-indigo-500 leading-relaxed"
                                                    spellcheck="false"
                                                >{{ $currentValue }}</textarea>
                                            </div>

                                        @elseif($option->field_type === 'textarea')
                                            <textarea
                                                wire:model="settings.{{ $fieldName }}"
                                                rows="8"
                                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 resize-y font-mono"
                                            >{{ $currentValue }}</textarea>

                                        @elseif($option->field_type === 'checkbox')
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <div class="relative">
                                                    <input
                                                        type="checkbox"
                                                        wire:model="settings.{{ $fieldName }}"
                                                        id="plugin_check_{{ $selectedPlugin->id }}_{{ $fieldName }}"
                                                        class="sr-only peer"
                                                    >
                                                    <div class="w-11 h-6 bg-slate-200 dark:bg-slate-600 peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5 pointer-events-none"></div>
                                                </div>
                                                <span class="text-sm text-slate-600 dark:text-slate-300 font-medium group-hover:text-slate-800 dark:group-hover:text-slate-100 transition-colors">
                                                    {{ $option->field_label }}
                                                </span>
                                            </label>

                                        @elseif($option->field_type === 'select')
                                            <select
                                                wire:model="settings.{{ $fieldName }}"
                                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500"
                                            >
                                                @foreach(array_map('trim', explode(',', $option->field_selections ?? '')) as $opt)
                                                    @if($opt)
                                                        <option value="{{ $opt }}" @selected(($settings[$fieldName] ?? '') === $opt)>{{ $opt }}</option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        @elseif($option->field_type === 'text-only')
                                            <pre class="text-xs bg-slate-900 dark:bg-slate-950 text-slate-300 p-4 rounded-xl overflow-x-auto max-h-56 leading-relaxed font-mono"><code>{{ $option->field_default_value }}</code></pre>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 italic">Reference only — edit the Custom CSS field above to customize.</p>

                                        @else
                                            <input
                                                type="text"
                                                wire:model="settings.{{ $fieldName }}"
                                                value="{{ $currentValue }}"
                                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500"
                                            >
                                        @endif
                                    </div>

                                    @if(!$loop->last)
                                        <hr class="border-slate-100 dark:border-slate-700">
                                    @endif
                                @endforeach
                            </div>

                            <div class="pt-4 flex items-center gap-3">
                                <button
                                    wire:click="saveSettings"
                                    wire:loading.attr="disabled"
                                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2"
                                >
                                    <svg wire:loading wire:target="saveSettings" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    Save Settings
                                </button>
                            </div>
                        @endif
                    @endif

                    {{-- ════════════════════════════════════════════════ --}}
                    {{-- USAGE / INSTRUCTIONS TAB                          --}}
                    {{-- ════════════════════════════════════════════════ --}}
                    @if($activeTab === 'instructions')
                        @php $showShortcode = in_array($selectedPlugin->type, ['display', 'quickcart', 'searchbar', 'checkout-features', 'product-features']); @endphp

                        @if($showShortcode && $selectedPlugin->shortcode)
                            <div class="space-y-2">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Shortcode</p>
                                <div class="flex items-center gap-3 bg-slate-900 dark:bg-slate-950 px-5 py-4 rounded-2xl">
                                    <code class="text-indigo-400 font-mono text-base font-bold flex-1">[plugin:{{ $selectedPlugin->shortcode }}]</code>
                                    <button
                                        onclick="navigator.clipboard.writeText('[plugin:{{ $selectedPlugin->shortcode }}]'); this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy',1500)"
                                        class="text-xs text-slate-400 hover:text-white font-semibold px-3 py-1.5 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors"
                                    >Copy</button>
                                </div>
                            </div>
                        @endif

                        @if($selectedPlugin->usage_instructions)
                            <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-300">
                                {!! $selectedPlugin->usage_instructions !!}
                            </div>
                        @endif

                        @if($selectedPlugin->help_url)
                            <a href="{{ $selectedPlugin->help_url }}" target="_blank"
                               class="inline-flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Documentation
                            </a>
                        @endif

                        @if($selectedPlugin->help_info)
                            <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900 rounded-2xl p-4 text-sm text-blue-800 dark:text-blue-200">
                                {!! $selectedPlugin->help_info !!}
                            </div>
                        @endif
                    @endif

                    {{-- ════════════════════════════════════════════════ --}}
                    {{-- ACTIVATION TAB                                    --}}
                    {{-- ════════════════════════════════════════════════ --}}
                    @if($activeTab === 'activation' && $selectedPlugin->activation_required === 'yes')
                        @if($selectedPlugin->activation_instructions)
                            <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-300">
                                {!! $selectedPlugin->activation_instructions !!}
                            </div>
                        @endif

                        @if($selectedPlugin->activation_status)
                            <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-sm text-emerald-700 dark:text-emerald-300 font-semibold">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $selectedPlugin->activation_success_msg ?: 'Plugin is activated.' }}
                            </div>
                            <button
                                wire:click="deactivatePlugin({{ $selectedPlugin->id }})"
                                class="px-4 py-2 text-sm font-semibold text-rose-600 border border-rose-200 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors"
                            >
                                Deactivate Plugin
                            </button>
                        @else
                            <div class="space-y-3">
                                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Activation Key</label>
                                <input
                                    type="text"
                                    wire:model="activationKey"
                                    placeholder="Enter your activation key…"
                                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 font-mono"
                                >
                                <button
                                    wire:click="activatePlugin"
                                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition-colors"
                                >
                                    Activate Plugin
                                </button>
                            </div>
                        @endif
                    @endif

                    {{-- ════════════════════════════════════════════════ --}}
                    {{-- TRANSLATIONS TAB                                  --}}
                    {{-- ════════════════════════════════════════════════ --}}
                    @if($activeTab === 'translations')
                        @php
                            $tlFields   = $selectedPlugin->getTranslatableFields();
                            $tlLanguages = \App\Models\Language::where('is_default', false)
                                              ->where('is_active', true)
                                              ->orderBy('sort_order')
                                              ->get();
                            $tlDefaults = $selectedPlugin->getSettings();
                        @endphp

                        {{-- Language pill selector --}}
                        @if($tlLanguages->isEmpty())
                            <p class="text-sm text-slate-400 dark:text-slate-500 py-4">No additional languages are active. Enable languages in the Language Manager first.</p>
                        @else
                            <div class="space-y-1.5">
                                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Select Language</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($tlLanguages as $lang)
                                        <button
                                            wire:click="selectTlLang({{ $lang->id }})"
                                            class="px-3 py-1.5 text-xs font-bold rounded-lg border transition-colors
                                                {{ $tlLangId === $lang->id
                                                    ? 'bg-indigo-600 text-white border-indigo-600'
                                                    : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600 hover:border-indigo-400 hover:text-indigo-600' }}"
                                        >
                                            {{ $lang->flag_emoji ?? '' }} {{ $lang->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            @if($tlLangId)
                                <div class="mt-4 space-y-4">
                                    @foreach($tlFields as $fieldName => $fieldLabel)
                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $fieldLabel }}</label>

                                            {{-- Default value reference (read-only) --}}
                                            @if(!empty($tlDefaults[$fieldName]))
                                                <p class="text-xs text-slate-400 dark:text-slate-500">
                                                    <span class="font-semibold text-slate-500">Default:</span>
                                                    <span class="font-mono">{{ $tlDefaults[$fieldName] }}</span>
                                                </p>
                                            @endif

                                            {{-- Translation input --}}
                                            <input
                                                type="text"
                                                wire:model="tlSettings.{{ $fieldName }}"
                                                placeholder="Translation for {{ $fieldLabel }}…"
                                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500"
                                            >
                                        </div>

                                        @if(!$loop->last)
                                            <hr class="border-slate-100 dark:border-slate-700">
                                        @endif
                                    @endforeach

                                    <div class="pt-2 flex flex-wrap items-center gap-3">
                                        <button
                                            wire:click="saveTlSettings"
                                            wire:loading.attr="disabled"
                                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2"
                                        >
                                            <svg wire:loading wire:target="saveTlSettings" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                            </svg>
                                            Save Translations
                                        </button>

                                        <button
                                            wire:click="autoTranslatePlugin"
                                            wire:loading.attr="disabled"
                                            class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl text-sm transition-all shadow-sm flex items-center gap-2"
                                        >
                                            <svg wire:loading wire:target="autoTranslatePlugin" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                            </svg>
                                            <svg wire:loading.remove wire:target="autoTranslatePlugin" class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Re-translate with AI (OpenAI)
                                        </button>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-slate-400 dark:text-slate-500 py-2">Select a language above to begin editing translations.</p>
                            @endif
                        @endif
                    @endif

                </div>{{-- /panel body --}}
            </div>
            @endif

        </div>{{-- /main layout --}}
    </div>
</div>
