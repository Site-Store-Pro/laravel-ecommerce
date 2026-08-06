<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                {{ $templateId ? 'Edit Email Template' : 'New Email Template' }}
            </h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Configure your custom HTML transactional email style.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" wire:click="generatePreview" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 rounded-xl text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Preview Template
            </button>
            <a href="{{ route('admin.email-templates.index') }}" wire:navigate class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 rounded-xl text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm text-sm font-semibold transition-colors">
                Cancel
            </a>
        </div>
    </div>

    <!-- Form container -->
    <div class="max-w-4xl">
        {{-- ── Language Translation Panel ─────────────────────────────────────── --}}
        @php $languages = \App\Models\Language::getAllActive(); @endphp
        @if($languages->count() > 1)
        <div class="mb-6 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl p-4">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mr-2">Editing Language:</span>
                {{-- Default language pill --}}
                <button
                    wire:click="setEditingLanguage(null)"
                    type="button"
                    class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all {{ $editingLanguageId === null ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-indigo-300' }}"
                >
                    🇺🇸 English (Default)
                </button>
                {{-- Non-default language pills --}}
                @foreach($languages->where('is_default', false) as $lang)
                    @php
                        $hasTranslation = $template?->translations->contains('language_id', $lang->id) ?? false;
                        $xlat = $template?->translations->firstWhere('language_id', $lang->id);
                        $xlatStatus = $xlat?->translation_status ?? 'pending';
                    @endphp
                    <button
                        wire:click="setEditingLanguage({{ $lang->id }})"
                        type="button"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all flex items-center gap-1.5 {{ $editingLanguageId === $lang->id ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-indigo-300' }}"
                    >
                        {{ $lang->flag_emoji }} {{ $lang->native_name }}
                        @if($hasTranslation)
                            <span class="w-1.5 h-1.5 rounded-full {{ $xlatStatus === 'reviewed' ? 'bg-emerald-400' : ($xlatStatus === 'ai_translated' ? 'bg-amber-400' : 'bg-slate-300') }}"></span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Translation Editing Panel (shown when non-default language selected) ── --}}
        @if($editingLanguageId !== null)
            @php
                $editLang = $languages->firstWhere('id', $editingLanguageId);
                $existingXlat = $template?->translations->firstWhere('language_id', $editingLanguageId);
                $xlatData = $translationData[$editingLanguageId] ?? [];
            @endphp
            <div class="mb-8 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-amber-900 dark:text-amber-200">{{ $editLang?->flag_emoji }} {{ $editLang?->name }} Translation</h3>
                        <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">Editing translated fields. Default English values shown as placeholders.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($existingXlat)
                            <span class="px-2 py-1 text-xs font-semibold rounded-lg
                                {{ $existingXlat->translation_status === 'reviewed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : ($existingXlat->translation_status === 'ai_translated' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400') }}">
                                {{ ucfirst(str_replace('_', ' ', $existingXlat->translation_status)) }}
                            </span>
                        @endif
                        <button
                            wire:click="aiTranslateEmail"
                            wire:loading.attr="disabled"
                            wire:target="aiTranslateEmail"
                            type="button"
                            class="px-4 py-2 bg-violet-600 hover:bg-violet-700 disabled:opacity-60 text-white text-xs font-bold rounded-xl transition flex items-center gap-2"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span wire:loading.remove wire:target="aiTranslateEmail">AI Translate</span>
                            <span wire:loading wire:target="aiTranslateEmail">Translating...</span>
                        </button>
                        <button
                            wire:click="saveTranslation"
                            type="button"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition"
                        >
                            Save Translation
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    {{-- Subject --}}
                    <div>
                        <label class="block text-xs font-semibold text-amber-800 dark:text-amber-300 mb-1">Subject</label>
                        <input type="text"
                            wire:model="translationData.{{ $editingLanguageId }}.subject"
                            class="w-full border border-amber-200 dark:border-amber-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-amber-400 focus:outline-none"
                            placeholder="{{ $template->subject }}" />
                    </div>

                    {{-- Salutation & Greeting (2-col) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-amber-800 dark:text-amber-300 mb-1">Salutation</label>
                            <input type="text"
                                wire:model="translationData.{{ $editingLanguageId }}.salutation"
                                class="w-full border border-amber-200 dark:border-amber-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-amber-400 focus:outline-none"
                                placeholder="{{ $template->salutation }}" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-amber-800 dark:text-amber-300 mb-1">Greeting</label>
                            <input type="text"
                                wire:model="translationData.{{ $editingLanguageId }}.greeting"
                                class="w-full border border-amber-200 dark:border-amber-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-amber-400 focus:outline-none"
                                placeholder="{{ $template->greeting }}" />
                        </div>
                    </div>

                    {{-- Body --}}
                    <div>
                        <label class="block text-xs font-semibold text-amber-800 dark:text-amber-300 mb-1">Body</label>
                        <textarea
                            wire:model="translationData.{{ $editingLanguageId }}.body"
                            rows="8"
                            class="w-full border border-amber-200 dark:border-amber-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-amber-400 focus:outline-none font-mono"
                            placeholder="{{ Str::limit(strip_tags($template->body ?? ''), 120) }}"></textarea>
                    </div>

                    {{-- Sign Off & Signature (2-col) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-amber-800 dark:text-amber-300 mb-1">Sign Off</label>
                            <input type="text"
                                wire:model="translationData.{{ $editingLanguageId }}.sign_off"
                                class="w-full border border-amber-200 dark:border-amber-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-amber-400 focus:outline-none"
                                placeholder="{{ $template->sign_off }}" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-amber-800 dark:text-amber-300 mb-1">Signature</label>
                            <input type="text"
                                wire:model="translationData.{{ $editingLanguageId }}.signature"
                                class="w-full border border-amber-200 dark:border-amber-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-amber-400 focus:outline-none"
                                placeholder="{{ $template->signature }}" />
                        </div>
                    </div>

                    {{-- Disclaimer & Copyright (2-col) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-amber-800 dark:text-amber-300 mb-1">Disclaimer</label>
                            <textarea
                                wire:model="translationData.{{ $editingLanguageId }}.disclaimer"
                                rows="3"
                                class="w-full border border-amber-200 dark:border-amber-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-amber-400 focus:outline-none"
                                placeholder="{{ Str::limit($template->disclaimer ?? '', 120) }}"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-amber-800 dark:text-amber-300 mb-1">Copyright</label>
                            <input type="text"
                                wire:model="translationData.{{ $editingLanguageId }}.copyright"
                                class="w-full border border-amber-200 dark:border-amber-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-amber-400 focus:outline-none"
                                placeholder="{{ $template->copyright }}" />
                        </div>
                    </div>

                    {{-- Header HTML & Footer HTML (collapsible accordions) --}}
                    <div x-data="{ openHeader: false, openFooter: false }" class="space-y-3">
                        <div class="border border-amber-200 dark:border-amber-700 rounded-xl overflow-hidden">
                            <button @click="openHeader = !openHeader" type="button" class="w-full flex items-center justify-between px-4 py-2.5 bg-amber-50 dark:bg-amber-900/20 text-xs font-semibold text-amber-800 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition">
                                <span>Header HTML</span>
                                <svg class="w-4 h-4 transition-transform" :class="openHeader ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openHeader" x-collapse>
                                <textarea
                                    wire:model="translationData.{{ $editingLanguageId }}.header_html"
                                    rows="5"
                                    class="w-full border-0 border-t border-amber-200 dark:border-amber-700 px-4 py-3 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-0 focus:outline-none font-mono"
                                    placeholder="{{ Str::limit($template->header_html ?? '', 120) }}"></textarea>
                            </div>
                        </div>
                        <div class="border border-amber-200 dark:border-amber-700 rounded-xl overflow-hidden">
                            <button @click="openFooter = !openFooter" type="button" class="w-full flex items-center justify-between px-4 py-2.5 bg-amber-50 dark:bg-amber-900/20 text-xs font-semibold text-amber-800 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition">
                                <span>Footer HTML</span>
                                <svg class="w-4 h-4 transition-transform" :class="openFooter ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openFooter" x-collapse>
                                <textarea
                                    wire:model="translationData.{{ $editingLanguageId }}.footer_html"
                                    rows="5"
                                    class="w-full border-0 border-t border-amber-200 dark:border-amber-700 px-4 py-3 text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-0 focus:outline-none font-mono"
                                    placeholder="{{ Str::limit($template->footer_html ?? '', 120) }}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <form wire:submit="save" class="space-y-8" @if($editingLanguageId !== null) style="display: none;" @endif>
            <!-- 1. Profile Settings -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-700 uppercase tracking-wider">Profile Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Email Type / Category</label>
                        <select wire:model.live="email_type_id" @if($templateId) disabled @endif class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Profile Name</label>
                        <input type="text" wire:model="profile_name" placeholder="e.g. Default Order Confirmation" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('profile_name') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Sender Settings -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-700 uppercase tracking-wider">Sender Configuration</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">FROM Email Address</label>
                        <input type="email" wire:model="from_address" placeholder="leave blank to use defaults" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('from_address') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">FROM Display Name</label>
                        <input type="text" wire:model="from_name" placeholder="leave blank to use defaults" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('from_name') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">BCC Email Address</label>
                        <input type="text" wire:model="bcc_address" placeholder="e.g. admin@store.com" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('bcc_address') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Merge Tags Reference Helper Box -->
            <div class="bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/60 rounded-3xl p-6 shadow-xs">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h4 class="text-sm font-bold text-indigo-950 dark:text-indigo-300">Supported Template Merge Tags</h4>
                        <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-1">You can insert the following variables in the Subject, Salutation, Greeting, or Body areas. They will be parsed dynamically upon sending.</p>
                       <div class="mt-3 flex flex-wrap gap-2">
    @if(in_array($email_type_id, [1, 2, 3]))
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{order_id}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{customer_name}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{order_total}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{order_subtotal}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{order_taxes}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{order_shipping}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{order_items_table}}</span>
        
        @if($email_type_id == 2)
            <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{tracking_number}}</span>
        @endif
        
        @if($email_type_id == 3)
            <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{download_links}}</span>
        @endif
        
    @elseif(in_array($email_type_id, [4, 5, 6]))
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{customer_name}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{customer_email}}</span>
        
        @if($email_type_id == 6)
            <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{activation_url}}</span>
        @endif
        
    @elseif($email_type_id == 7)
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{customer_name}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{reset_url}}</span>
        
    @elseif(in_array($email_type_id, [8, 9, 10]))
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{customer_name}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{ticket_title}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{ticket_status}}</span>
        <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{ticket_url}}</span>
        
        @if($email_type_id == 9)
            <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{reply_author}}</span>
            <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{reply_body}}</span>
        @endif
        
        @if($email_type_id == 10)
            <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{previous_status}}</span>
        @endif
    @endif
    
    <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{app_name}}</span>
    <span class="bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-3xs font-semibold px-2 py-1 rounded font-mono">@{{year}}</span>
</div>

                    </div>
                </div>
            </div>

            <!-- 3. Message Subject & Header/Footer Content -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-700 uppercase tracking-wider">Subject & Header HTML</h3>

                <div class="space-y-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Email Subject Line</label>
                        <input type="text" wire:model="subject" placeholder="e.g. Order Confirmation # @{{order_id}}" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">

                        @error('subject') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Custom Header HTML (Appears above the main container)</label>
                        <div wire:ignore 
                             x-data="{
                                 textVal: @entangle('header_html'),
                                 initTiny() {
                                     tinymce.init({
                                         selector: '#header_html_editor',
                                         license_key: 'gpl',
                                         promotion: false,
                                         height: 180,
                                         menubar: false,
                                         plugins: 'code link lists',
                                         toolbar: 'undo redo | bold italic underline | link | code',
                                         branding: false,
                                         setup: (editor) => {
                                             editor.on('init', () => { editor.setContent(this.textVal || ''); });
                                             editor.on('change blur', () => { this.textVal = editor.getContent(); });
                                         }
                                     });
                                 }
                             }"
                             x-init="initTiny()"
                             x-cleanup="tinymce.remove('#header_html_editor')">
                            <textarea id="header_html_editor" class="w-full"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Top Banner Section -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Image Banner Header</h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="show_banner" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                @if($show_banner)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" wire:key="banner-fields">
                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Banner Image URL</label>
                            <input type="text" wire:model="banner_image_url" placeholder="https://example.com/images/banner.jpg" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                            @error('banner_image_url') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Banner Link URL (Clickable destination)</label>
                            <input type="text" wire:model="banner_image_link" placeholder="https://example.com" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                            @error('banner_image_link') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif
            </div>

            <!-- 5. Message Body Content -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-700 uppercase tracking-wider">Email Core Content</h3>

                <div class="space-y-6">
                    <!-- Salutation Toggle and Input -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <span class="text-sm font-bold text-slate-900 dark:text-white block">Include Salutation Line</span>
                            <span class="text-xs text-slate-400">e.g. "Dear [Customer Name],"</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="include_salutation" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    @if($include_salutation)
                        <div wire:key="salutation-field">
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Salutation Format</label>
                           <input type="text" wire:model="salutation" placeholder="Dear @{{customer_name}}," class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                            @error('salutation') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Greeting (Main top message) -->
                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Main Message Top Block (Greeting)</label>
                        <div wire:ignore 
                             x-data="{
                                 textVal: @entangle('greeting'),
                                 initTiny() {
                                     tinymce.init({
                                         selector: '#greeting_editor',
                                         license_key: 'gpl',
                                         promotion: false,
                                         height: 250,
                                         menubar: false,
                                         plugins: 'code link lists help',
                                         toolbar: 'undo redo | formatselect | bold italic underline forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | link | code',
                                         branding: false,
                                         setup: (editor) => {
                                             editor.on('init', () => { editor.setContent(this.textVal || ''); });
                                             editor.on('change blur', () => { this.textVal = editor.getContent(); });
                                         }
                                     });
                                 }
                             }"
                             x-init="initTiny()"
                             x-cleanup="tinymce.remove('#greeting_editor')">
                            <textarea id="greeting_editor" class="w-full"></textarea>
                        </div>
                    </div>

                    <!-- Body (Additional bottom content) -->
                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Additional Email Body Content (Order details, download links, etc.)</label>
                        <div wire:ignore 
                             x-data="{
                                 textVal: @entangle('body'),
                                 initTiny() {
                                     tinymce.init({
                                         selector: '#body_editor',
                                         license_key: 'gpl',
                                         promotion: false,
                                         height: 250,
                                         menubar: false,
                                         plugins: 'code link lists help',
                                         toolbar: 'undo redo | formatselect | bold italic underline forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | link | code',
                                         branding: false,
                                         setup: (editor) => {
                                             editor.on('init', () => { editor.setContent(this.textVal || ''); });
                                             editor.on('change blur', () => { this.textVal = editor.getContent(); });
                                         }
                                     });
                                 }
                             }"
                             x-init="initTiny()"
                             x-cleanup="tinymce.remove('#body_editor')">
                            <textarea id="body_editor" class="w-full"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. Sign-off & Signatures -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-700 uppercase tracking-wider">Sign-off & Footer Info</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Sign Off phrase</label>
                        <input type="text" wire:model="sign_off" placeholder="Sincerely, / Regards," class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('sign_off') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Signature Text</label>
                        <input type="text" wire:model="signature" placeholder="Customer Service Team" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('signature') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Disclaimer Line</label>
                        <textarea wire:model="disclaimer" rows="2" placeholder="This is a transactional email regarding your recent purchase." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm"></textarea>
                        @error('disclaimer') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Copyright line</label>
                        <textarea wire:model="copyright" rows="2" placeholder="Copyright © {{ date('Y') }} Store Name. All rights reserved." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm"></textarea>
                        @error('copyright') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- 7. Bottom Image Footer section -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Footer Image Logo</h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="show_footer_image" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                @if($show_footer_image)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" wire:key="footer-image-fields">
                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Footer Image URL</label>
                            <input type="text" wire:model="footer_image_url" placeholder="https://example.com/images/footer-logo.png" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                            @error('footer_image_url') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Footer Logo Destination Link</label>
                            <input type="text" wire:model="footer_image_link" placeholder="https://example.com" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                            @error('footer_image_link') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                <div>
                    <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider font-semibold">Custom Footer HTML (Appears below main container)</label>
                    <div wire:ignore 
                         x-data="{
                             textVal: @entangle('footer_html'),
                             initTiny() {
                                 tinymce.init({
                                     selector: '#footer_html_editor',
                                     license_key: 'gpl',
                                     promotion: false,
                                     height: 180,
                                     menubar: false,
                                     plugins: 'code link lists',
                                     toolbar: 'undo redo | bold italic underline | link | code',
                                     branding: false,
                                     setup: (editor) => {
                                         editor.on('init', () => { editor.setContent(this.textVal || ''); });
                                         editor.on('change blur', () => { this.textVal = editor.getContent(); });
                                     }
                                 });
                             }
                         }"
                         x-init="initTiny()"
                         x-cleanup="tinymce.remove('#footer_html_editor')">
                        <textarea id="footer_html_editor" class="w-full"></textarea>
                    </div>
                </div>
            </div>

            <!-- 8. Status & Submit -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-bold text-slate-900 dark:text-white block">Template Active Status</span>
                        <span class="text-xs text-slate-400">If checked, this template profile will be the active layout for this email notification type.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>
            </div>

            <!-- Submit Button Row -->
            <div class="flex justify-end gap-3 pb-8">
                <a href="{{ route('admin.email-templates.index') }}" wire:navigate class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-bold text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 transition duration-150">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-150 transition duration-150">
                    Save Template
                </button>
            </div>
        </form>
    </div>

    <!-- Live Preview Modal -->
    @if($showPreviewModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-slate-500/75 transition-opacity" aria-hidden="true" wire:click="$set('showPreviewModal', false)"></div>

                <!-- Center elements -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-slate-100 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-slate-800 px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white" id="modal-title">
                            Email Template Preview: {{ $profile_name }}
                        </h3>
                        <button type="button" wire:click="$set('showPreviewModal', false)" class="text-slate-400 hover:text-slate-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="px-6 py-6 overflow-y-auto max-h-[70vh] bg-slate-100">
                        <!-- Frame to contain rendered HTML email preview -->
                        <div class="border border-slate-200 rounded-xl bg-white shadow-sm overflow-hidden">
                            {!! $previewHtml !!}
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 px-6 py-4 flex justify-end">
                        <button type="button" wire:click="$set('showPreviewModal', false)" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-sm transition-colors">
                            Close Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Add TinyMCE script from asset pipeline -->
<script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
