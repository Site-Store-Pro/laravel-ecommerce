<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Language Manager</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Manage system languages and translation coverage.</p>
            </div>
            <button wire:click="openAddModal" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md flex items-center gap-2 transition duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Add Language
            </button>
        </div>

        <!-- Flash messages -->
        @if(session()->has('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl border border-emerald-100 dark:border-emerald-800 flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="p-4 bg-red-50 dark:bg-red-950/30 rounded-2xl border border-red-100 dark:border-red-800 flex items-center gap-3 text-red-800 dark:text-red-300 text-sm font-semibold">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 text-slate-400 font-bold uppercase tracking-wider bg-slate-50 dark:bg-slate-800/50">
                            <th class="py-4 px-4">Language</th>
                            <th class="py-4 px-4">Default</th>
                            <th class="py-4 px-4">Active</th>
                            <th class="py-4 px-4">In Switcher</th>
                            <th class="py-4 px-4">RTL</th>
                            <th class="py-4 px-4">Currency Override</th>
                            <th class="py-4 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($languages as $lang)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <span class="fi fi-{{ strtolower($lang->flag_emoji) }} rounded-sm flex-shrink-0" style="width:1.6em;height:1.2em;font-size:1.4rem;"></span>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                                {{ $lang->name }}
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">{{ $lang->code }}</span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $lang->native_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($lang->is_default)
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                            Default
                                        </span>
                                    @else
                                        <button wire:click="setDefault({{ $lang->id }})" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition">
                                            Set Default
                                        </button>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <button wire:click="toggleActive({{ $lang->id }})" class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ $lang->is_active ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-600' }}">
                                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform {{ $lang->is_active ? 'translate-x-4' : 'translate-x-1' }}"></span>
                                    </button>
                                </td>
                                <td class="py-4 px-4">
                                    <button wire:click="toggleSwitcher({{ $lang->id }})" class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ $lang->show_in_switcher ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-600' }}">
                                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform {{ $lang->show_in_switcher ? 'translate-x-4' : 'translate-x-1' }}"></span>
                                    </button>
                                </td>
                                <td class="py-4 px-4">
                                    @if($lang->rtl)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">RTL</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 font-medium text-slate-700 dark:text-slate-300">
                                    @if($lang->currency_code)
                                        {{ $lang->currency_symbol }} {{ $lang->currency_code }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(!$lang->is_default)
                                            <button wire:click="bulkTranslate({{ $lang->id }})" wire:loading.attr="disabled" class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                                Translate All
                                            </button>
                                        @endif
                                        <button wire:click="editLanguage({{ $lang->id }})" class="px-2.5 py-1.5 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold hover:bg-slate-50 transition">
                                            Edit
                                        </button>
                                        <button x-data="{ confirming: false }" @click="confirming ? $wire.deleteLanguage({{ $lang->id }}) : confirming = true" @click.away="confirming = false" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition" :class="confirming ? 'bg-red-600 text-white' : 'bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50'" {{ $lang->is_default ? 'disabled' : '' }}>
                                            <span x-text="confirming ? 'Confirm?' : 'Delete'"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Translation Coverage Section -->
        <div class="mt-8">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Translation Coverage</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($languages->where('is_default', false) as $lang)
                    @php $stats = $this->translationStats($lang->id); @endphp
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-2">
                                <span class="fi fi-{{ strtolower($lang->flag_emoji) }} rounded-sm" style="width:1.4em;height:1.05em;font-size:1.2rem;"></span>
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $lang->name }}</span>
                            </div>
                            <a href="{{ route('admin.languages.translations', $lang->id) }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                View Details &rarr;
                            </a>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach([
                                'CMS Pages'           => $stats['cms_pages'],
                                'Products'            => $stats['products'],
                                'KB Articles'         => $stats['kb_articles'],
                                'Testimonials'        => $stats['testimonials'],
                                'Nav Items'           => $stats['nav_items'],
                                'List Menus'          => $stats['list_menus'],
                                'Site Labels'         => $stats['site_labels'],
                                'Product Categories'  => $stats['product_categories'],
                                'CMS Categories'      => $stats['cms_categories'],
                                'CMS Tags'            => $stats['cms_tags'],
                                'KB Categories'       => $stats['kb_categories'],
                                'Email Templates'     => $stats['email_templates'],
                            ] as $label => $stat)
                                @php
                                    $pct = $stat['total'] > 0 ? round(($stat['translated'] / $stat['total']) * 100) : 100;
                                    $color = $pct >= 90 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-red-500');
                                @endphp
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-medium text-slate-600 dark:text-slate-400">{{ $label }}</span>
                                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $stat['translated'] }}/{{ $stat['total'] }}</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full {{ $color }} rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button wire:click="bulkTranslate({{ $lang->id }})" wire:loading.attr="disabled" class="w-full py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-400 rounded-xl text-xs font-bold transition flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                Translate Missing Items
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div x-data="{ show: @entangle('showAddModal') }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-900/75 backdrop-blur-sm"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 dark:border-slate-700">
                    <form wire:submit.prevent="saveLanguage">
                        <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                            <h3 class="text-lg leading-6 font-bold text-slate-900 dark:text-white">Add New Language</h3>
                        </div>
                        <div class="p-6 space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Code *</label>
                                    <input type="text" wire:model="code" placeholder="en" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                    @error('code') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Flag Country Code *</label>
                                    <div class="flex items-center gap-3">
                                        <span class="fi fi-{{ strtolower($flag_emoji ?: 'un') }} rounded-sm flex-shrink-0" style="width:2em;height:1.5em;font-size:1.6rem;"></span>
                                        <input type="text" wire:model.live="flag_emoji" placeholder="us" maxlength="2" class="w-24 px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500 uppercase">
                                    </div>
                                    <p class="text-xs text-slate-400 mt-2">Enter the <strong>2-letter ISO country code</strong> (lowercase). The flag previews instantly.<br>
                                        Common codes: <span class="font-mono text-slate-600 dark:text-slate-300">us &nbsp;mx &nbsp;gb &nbsp;fr &nbsp;de &nbsp;es &nbsp;pt &nbsp;it &nbsp;nl &nbsp;jp &nbsp;cn &nbsp;br &nbsp;au &nbsp;ca &nbsp;kr &nbsp;ru &nbsp;ar &nbsp;in &nbsp;sa &nbsp;tr</span>
                                    </p>
                                    @error('flag_emoji') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Name (English) *</label>
                                    <input type="text" wire:model="name" placeholder="English" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                    @error('name') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Native Name *</label>
                                    <input type="text" wire:model="native_name" placeholder="English" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                    @error('native_name') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-4 pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="is_active" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-slate-50 border-slate-300">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Active</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="show_in_switcher" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-slate-50 border-slate-300">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Show in Switcher</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="rtl" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-slate-50 border-slate-300">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">RTL</span>
                                </label>
                            </div>
                            
                            <div x-data="{ open: false }" class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                                <button type="button" @click="open = !open" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 flex justify-between items-center text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Currency Override (Optional)
                                    <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Currency Code</label>
                                            <input type="text" wire:model="currency_code" placeholder="EUR" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Symbol</label>
                                            <input type="text" wire:model="currency_symbol" placeholder="€" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Position</label>
                                            <select wire:model="currency_position" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                                <option value="before">Before (€10)</option>
                                                <option value="after">After (10€)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Decimal Separator</label>
                                            <input type="text" wire:model="decimal_separator" placeholder="." class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Thousands Separator</label>
                                            <input type="text" wire:model="thousands_separator" placeholder="," class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sort Order</label>
                                <input type="number" wire:model="sort_order" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-3 rounded-b-3xl">
                            <button type="button" @click="show = false" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2">
                                <span wire:loading wire:target="saveLanguage" class="animate-spin inline-block w-4 h-4 border-2 border-white/20 border-t-white rounded-full"></span>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Edit Modal (similar to Add) -->
        <div x-data="{ show: @entangle('showEditModal') }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-900/75 backdrop-blur-sm"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 dark:border-slate-700">
                    <form wire:submit.prevent="updateLanguage">
                        <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                            <h3 class="text-lg leading-6 font-bold text-slate-900 dark:text-white">Edit Language</h3>
                        </div>
                        <div class="p-6 space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Code *</label>
                                    <input type="text" wire:model="code" placeholder="en" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                    @error('code') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Flag Country Code *</label>
                                    <div class="flex items-center gap-3">
                                        <span class="fi fi-{{ strtolower($flag_emoji ?: 'un') }} rounded-sm flex-shrink-0" style="width:2em;height:1.5em;font-size:1.6rem;"></span>
                                        <input type="text" wire:model.live="flag_emoji" placeholder="us" maxlength="2" class="w-24 px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <p class="text-xs text-slate-400 mt-2">Enter the <strong>2-letter ISO country code</strong> (lowercase). The flag previews instantly.<br>
                                        Common codes: <span class="font-mono text-slate-600 dark:text-slate-300">us &nbsp;mx &nbsp;gb &nbsp;fr &nbsp;de &nbsp;es &nbsp;pt &nbsp;it &nbsp;nl &nbsp;jp &nbsp;cn &nbsp;br &nbsp;au &nbsp;ca &nbsp;kr &nbsp;ru &nbsp;ar &nbsp;in &nbsp;sa &nbsp;tr</span>
                                    </p>
                                    @error('flag_emoji') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Name (English) *</label>
                                    <input type="text" wire:model="name" placeholder="English" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                    @error('name') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Native Name *</label>
                                    <input type="text" wire:model="native_name" placeholder="English" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                    @error('native_name') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-4 pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="is_active" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-slate-50 border-slate-300">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Active</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="show_in_switcher" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-slate-50 border-slate-300">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Show in Switcher</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="rtl" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-slate-50 border-slate-300">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">RTL</span>
                                </label>
                            </div>
                            
                            <div x-data="{ open: true }" class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                                <button type="button" @click="open = !open" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 flex justify-between items-center text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Currency Override (Optional)
                                    <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Currency Code</label>
                                            <input type="text" wire:model="currency_code" placeholder="EUR" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Symbol</label>
                                            <input type="text" wire:model="currency_symbol" placeholder="€" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Position</label>
                                            <select wire:model="currency_position" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                                <option value="before">Before</option>
                                                <option value="after">After</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Decimal Separator</label>
                                            <input type="text" wire:model="decimal_separator" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Thousands Separator</label>
                                            <input type="text" wire:model="thousands_separator" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sort Order</label>
                                <input type="number" wire:model="sort_order" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-3 rounded-b-3xl">
                            <button type="button" @click="show = false" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2">
                                <span wire:loading wire:target="updateLanguage" class="animate-spin inline-block w-4 h-4 border-2 border-white/20 border-t-white rounded-full"></span>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
