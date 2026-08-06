<div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                    Inventory Alert Messages
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Manage the out-of-stock messages that can be assigned to individual products.
                    When a product has zero available stock the assigned message is shown instead of the default.
                </p>
            </div>
            <button wire:click="create"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-md shadow-indigo-100 transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Message
            </button>
        </div>

        <x-toast-alert />

        {{-- Inline Create / Edit Form --}}
        @if($editingId !== null || $message !== '' || $this->editingId === null && $sort_order > 0)
        @endif

        {{-- The form is shown whenever editingId is set OR we just called create() (sort_order > 0 is our proxy) --}}
        @if(isset($editingId) && ($editingId || $sort_order > 0 || strlen($message) > 0))
        <div class="mb-8 bg-white border-2 border-indigo-200 rounded-3xl p-8 shadow-md"
             x-data x-init="$el.scrollIntoView({behavior:'smooth',block:'start'})">
            <h3 class="text-base font-bold text-indigo-800 mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="{{ $editingId ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M12 4v16m8-8H4' }}"/>
                </svg>
                {{ $editingId ? 'Edit Alert Message' : 'New Alert Message' }}
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Message Text <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="message" id="alert_message_input" autofocus
                           placeholder="e.g. Back-Ordered: ETA 2 Weeks"
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 shadow-sm">
                    @error('message') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Sort Order</label>
                        <input type="number" wire:model="sort_order" min="0"
                               class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 shadow-sm">
                    </div>
                    <div class="flex items-center gap-3 pt-5">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-2 text-sm font-semibold text-slate-700">Active</span>
                        </label>
                    </div>
                </div>
            {{-- ─── Translations Section ──────────────────────────────────────────────── --}}
            @if($activeLanguages->isNotEmpty() && $editingId)
            <div x-data="{ tlOpen: false }" class="border-t border-slate-100 dark:border-slate-700 pt-5 mt-5">
                <button type="button" @click="tlOpen = !tlOpen"
                        class="flex items-center justify-between w-full text-left">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                        Language Translations
                    </span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="tlOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="tlOpen" x-cloak class="mt-4 space-y-4">
                    {{-- Language selector pills --}}
                    <div class="flex flex-wrap gap-2">
                        @foreach($activeLanguages as $lang)
                            <button type="button"
                                    wire:click="selectTlLang({{ $lang->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition
                                        {{ $tlLangId === $lang->id
                                            ? 'bg-indigo-600 text-white border-indigo-600 shadow'
                                            : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600 hover:border-indigo-400' }}">
                                <span class="fi fi-{{ strtolower($lang->flag_emoji) }}" style="width:1em;height:0.75em;font-size:1rem;"></span>
                                {{ $lang->name }}
                            </button>
                        @endforeach
                    </div>

                    @if($tlLangId > 0)
                        <div class="space-y-3 bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Translated Message (Default: "{{ $message }}")</label>
                                <input type="text" wire:model="tlBuffer.message"
                                       placeholder="Translation in target language..."
                                       class="w-full px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500 shadow-sm">
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <button type="button" wire:click="aiTlAlert({{ $editingId }})"
                                        wire:loading.attr="disabled" wire:target="aiTlAlert({{ $editingId }})"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-violet-50 hover:bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300 text-xs font-bold rounded-xl transition border border-violet-200/60 disabled:opacity-60 cursor-pointer">
                                    <span wire:loading.remove wire:target="aiTlAlert({{ $editingId }})">
                                        <svg class="w-3.5 h-3.5 text-violet-600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L9.09 9.09 2 12l7.09 2.91L12 22l2.91-7.09L22 12l-7.09-2.91L12 2z"/></svg>
                                    </span>
                                    <span wire:loading wire:target="aiTlAlert({{ $editingId }})" class="inline-flex">
                                        <svg class="w-3.5 h-3.5 animate-spin text-violet-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    </span>
                                    OpenAI AI Translate
                                </button>
                                <button type="button" wire:click="saveTlAlert({{ $editingId }})"
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">
                                    Save Translation
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button wire:click="cancelEdit" type="button"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold rounded-xl transition">
                    Cancel
                </button>
                <button wire:click="save" wire:loading.attr="disabled" type="button"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow transition">
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <span wire:loading wire:target="save"
                          class="inline-block w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    {{ $editingId ? 'Update Message' : 'Create Message' }}
                </button>
            </div>
        </div>
        @endif

        {{-- Delete Confirmation --}}
        @if($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-100 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Delete Alert Message?</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Any products currently assigned this message will automatically revert to the default out-of-stock text.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button wire:click="cancelDelete" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button wire:click="deleteAlert" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Search --}}
        <div class="mb-4">
            <div class="relative max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Search messages..."
                       class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm text-slate-700 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 shadow-sm">
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-3 px-5 w-12">#</th>
                        <th class="py-3 px-4">Message</th>
                        <th class="py-3 px-4 hidden sm:table-cell text-center w-28">Products</th>
                        <th class="py-3 px-4 hidden md:table-cell text-center w-24">Sort</th>
                        <th class="py-3 px-4 text-center w-24">Active</th>
                        <th class="py-3 px-4 text-right w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($alerts as $alert)
                    <tr class="hover:bg-slate-50/40 transition {{ $editingId === $alert->id ? 'bg-indigo-50/40 ring-1 ring-indigo-200 ring-inset' : '' }}">
                        <td class="py-3.5 px-5 text-slate-400 text-xs font-bold">{{ $alert->id }}</td>
                        <td class="py-3.5 px-4">
                            <span class="font-semibold text-slate-800">{{ $alert->message }}</span>
                        </td>
                        <td class="py-3.5 px-4 hidden sm:table-cell text-center">
                            @if($alert->products_count > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                                    {{ $alert->products_count }}
                                </span>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 hidden md:table-cell text-center text-slate-500 text-xs font-mono">
                            {{ $alert->sort_order }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <button wire:click="toggleActive({{ $alert->id }})"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition
                                           {{ $alert->is_active ? 'bg-indigo-600' : 'bg-slate-300' }}">
                                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition
                                             {{ $alert->is_active ? 'translate-x-4' : 'translate-x-1' }}"></span>
                            </button>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $alert->id }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition border border-indigo-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $alert->id }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition border border-rose-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-slate-400 text-sm font-medium">No alert messages found.</p>
                                <button wire:click="create" class="text-indigo-600 text-sm font-bold hover:underline">
                                    Create your first message →
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Default message note --}}
        <div class="mt-6 flex items-start gap-3 px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-500">
            <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>
                <strong class="text-slate-700">Default message:</strong>
                When no alert is assigned to a product, or the assigned alert has been deleted, the storefront will display the standard
                <strong class="text-slate-700">"Currently Unavailable"</strong> text. Deleted alerts automatically unlink from all products — no errors are shown.
            </span>
        </div>

    </div>
</div>
