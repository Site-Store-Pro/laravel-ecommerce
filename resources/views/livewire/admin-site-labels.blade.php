<div class="min-h-screen bg-gray-50 dark:bg-gray-900">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white font-extrabold tracking-tight">Site Labels</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    @if($this->isDefaultLanguage)
                        Override any hardcoded text on public-facing pages. Changes take effect immediately.
                    @else
                        Manage translated labels for the selected language. AI translation populates the buffer for review before saving.
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Bulk AI translate (non-default language only) --}}
                @if(!$this->isDefaultLanguage)
                <button wire:click="aiTranslateSectionInline"
                        wire:loading.attr="disabled"
                        title="AI-translate all labels on the current page into the selected language. Results go into the edit buffer for review."
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg border border-violet-300 text-violet-700 dark:border-violet-600 dark:text-violet-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">
                    <span wire:loading wire:target="aiTranslateSectionInline" class="animate-spin w-3.5 h-3.5 border-2 border-violet-400 border-t-transparent rounded-full inline-block"></span>
                    <svg class="w-3.5 h-3.5" wire:loading.remove wire:target="aiTranslateSectionInline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    ✦ AI Translate Page
                </button>
                @endif

                @if($activeSection > 0 && $this->isDefaultLanguage)
                <button wire:click="resetSection"
                        wire:confirm="Reset ALL custom overrides in this section? This cannot be undone."
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-300 text-red-600 dark:border-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset Section
                </button>
                @elseif($this->isDefaultLanguage)
                <button wire:click="resetSection"
                        wire:confirm="Reset ALL custom overrides across every section? This cannot be undone."
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-300 text-red-600 dark:border-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset All Overrides
                </button>
                @endif
            </div>
        </div>

        {{-- ── Language Tabs ──────────────────────────────────────────────── --}}
        @if($this->languages->count() > 1)
        <div class="mt-3 flex items-center gap-1 flex-wrap">
            {{-- Default language tab --}}
            <button wire:click="$set('selectedLanguageId', 0)"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors
                           {{ $this->isDefaultLanguage
                               ? 'bg-indigo-600 text-white border-indigo-600'
                               : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600' }}">
                Default Language
            </button>
            {{-- Non-default language tabs --}}
            @foreach($this->languages->where('is_default', false) as $lang)
            <button wire:click="$set('selectedLanguageId', {{ $lang->id }})"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors
                           {{ $selectedLanguageId === $lang->id
                               ? 'bg-indigo-600 text-white border-indigo-600'
                               : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600' }}">
                @if($lang->flag_code)
                <span class="fi fi-{{ $lang->flag_code }} text-sm"></span>
                @endif
                {{ $lang->name }}
            </button>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── Flash Message ───────────────────────────────────────────────── --}}
    @if($flashMessage)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false; $wire.clearFlash(); }, 4000)"
         class="mx-6 mt-4 flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-medium
                {{ $flashType === 'success' ? 'bg-green-50 text-green-800 border border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700' : 'bg-red-50 text-red-800 border border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700' }}">
        @if($flashType === 'success')
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        @else
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        @endif
        {{ $flashMessage }}
    </div>
    @endif

    <div class="flex min-h-[calc(100vh-160px)]">

        {{-- ── Section Sidebar ─────────────────────────────────────────────── --}}
        <aside class="w-60 shrink-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
            <div class="p-3">
                <button wire:click="$set('activeSection', 0)"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition-colors
                               {{ $activeSection === 0 ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span>All Sections</span>
                    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs rounded-full
                                 {{ $activeSection === 0 ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-800 dark:text-indigo-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ array_sum($this->sectionCounts) }}
                    </span>
                </button>

                <div class="mt-1 space-y-0.5">
                    @foreach($this->sections as $section)
                    @php
                        $total  = $this->sectionCounts[$section->id] ?? 0;
                        $custom = $this->customCounts[$section->id]  ?? 0;
                        $active = $activeSection === $section->id;
                    @endphp
                    <button wire:click="$set('activeSection', {{ $section->id }})"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors
                                   {{ $active ? 'bg-indigo-50 text-indigo-700 font-medium dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="truncate text-left">{{ $section->name }}</span>
                        <div class="flex items-center gap-1 ml-1 shrink-0">
                            @if($custom > 0)
                            <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-xs rounded-full bg-amber-100 text-amber-700 dark:bg-amber-800/50 dark:text-amber-300" title="{{ $custom }} overrides">{{ $custom }}</span>
                            @endif
                            <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-xs rounded-full
                                         {{ $active ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-800 dark:text-indigo-200' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">{{ $total }}</span>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- ── Main Content ─────────────────────────────────────────────────── --}}
        <main class="flex-1 overflow-hidden">

            {{-- Toolbar --}}
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-5 py-3 flex items-center gap-3">
                {{-- Search --}}
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           placeholder="Search keys, descriptions, default text..."
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    @endif
                </div>

                {{-- Custom / translated only toggle --}}
                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer select-none">
                    <input wire:model.live="showCustomOnly" type="checkbox"
                           class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                    <span>{{ $this->isDefaultLanguage ? 'Custom overrides only' : 'Translated only' }}</span>
                </label>

                {{-- Per-page --}}
                <select wire:model.live="perPage"
                        class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                    <option value="100">100 / page</option>
                </select>

                {{-- Wire loading spinner --}}
                <div wire:loading class="text-indigo-500">
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </div>
            </div>

            {{-- ═══ DEFAULT LANGUAGE TABLE ═══════════════════════════════════════ --}}
            @if($this->isDefaultLanguage)
            <div class="overflow-auto" style="height: calc(100vh - 270px);">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-gray-50 dark:bg-gray-900 z-10">
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400 w-48">Label Key</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400 w-40">File / Section</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400">Description</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400 w-56">Default Text</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400 w-72">Custom Override</th>
                            <th class="px-4 py-3 w-24"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($this->labels as $label)
                        @php
                            $hasCustom   = $label->label_custom !== null && $label->label_custom !== '';
                            $inBuffer    = array_key_exists($label->id, $editBuffer);
                            $bufferValue = $inBuffer ? $editBuffer[$label->id] : '';
                            $isDirty     = $inBuffer && $bufferValue !== ($label->label_custom ?? '');
                        @endphp
                        <tr wire:key="label-{{ $label->id }}"
                            class="group hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors
                                   {{ $hasCustom ? 'border-l-2 border-l-amber-400 dark:border-l-amber-500' : '' }}">

                            <td class="px-4 py-3 align-top">
                                <code class="text-xs font-mono text-indigo-600 dark:text-indigo-400 break-all leading-tight">{{ $label->label_key }}</code>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono break-all">{{ $label->file_name }}</div>
                                @if($label->section)
                                <span class="inline-flex mt-1 items-center px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">{{ $label->section->name }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top text-xs text-gray-600 dark:text-gray-400 leading-relaxed">{{ $label->label_description }}</td>
                            <td class="px-4 py-3 align-top">
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed italic line-clamp-3">{{ $label->label_default }}</p>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="relative">
                                    <textarea
                                        wire:model="editBuffer.{{ $label->id }}"
                                        rows="2"
                                        placeholder="{{ $hasCustom ? $label->label_custom : 'Leave blank to use default…' }}"
                                        class="w-full text-xs px-2.5 py-2 border rounded-lg resize-y leading-relaxed transition-colors
                                               {{ $isDirty
                                                    ? 'border-amber-400 dark:border-amber-500 bg-amber-50/30 dark:bg-amber-900/10 text-gray-900 dark:text-gray-100'
                                                    : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}
                                               focus:outline-none focus:ring-2 focus:ring-indigo-400 placeholder-gray-400 dark:placeholder-gray-500"
                                    >{{ $hasCustom && !$inBuffer ? $label->label_custom : $bufferValue }}</textarea>
                                    @if($isDirty)
                                    <span class="absolute -top-1.5 -right-1.5 w-3 h-3 rounded-full bg-amber-400 border-2 border-white dark:border-gray-800" title="Unsaved changes"></span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-3 align-top">
                                <div class="flex flex-col gap-1.5">
                                    <button wire:click="saveLabel({{ $label->id }})"
                                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                   {{ $isDirty ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300' }}
                                                   transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Save
                                    </button>
                                    @if($hasCustom)
                                    <button wire:click="clearLabel({{ $label->id }})"
                                            wire:confirm="Remove the custom override and revert to the default text?"
                                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Clear
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <svg class="mx-auto w-10 h-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No labels found for your current filters.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ═══ NON-DEFAULT LANGUAGE TRANSLATION TABLE ═══════════════════════ --}}
            @else
            <div class="overflow-auto" style="height: calc(100vh - 270px);">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-gray-50 dark:bg-gray-900 z-10">
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400 w-44">Label Key</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400 w-36">Section</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400 w-56">Source (Default Language)</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400">
                                Translation
                                <span class="ml-1 text-xs font-normal text-violet-600 dark:text-violet-400">✦ AI available</span>
                            </th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500 dark:text-gray-400 w-20">Status</th>
                            <th class="px-4 py-3 w-28"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($this->labels as $label)
                        @php
                            $translation   = $label->translations->first();   // eager-loaded for current lang
                            $hasTranslation = $translation && !empty($translation->label_value);
                            $status         = $translation->translation_status ?? 'pending';
                            $inBuffer       = array_key_exists($label->id, $editBuffer);
                            $bufferValue    = $inBuffer ? $editBuffer[$label->id] : '';
                            $savedValue     = $hasTranslation ? $translation->label_value : '';
                            $isDirty        = $inBuffer && $bufferValue !== $savedValue;
                        @endphp
                        <tr wire:key="tlabel-{{ $label->id }}-{{ $selectedLanguageId }}"
                            class="group hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors
                                   {{ $hasTranslation ? 'border-l-2 border-l-violet-400 dark:border-l-violet-500' : '' }}">

                            {{-- Key --}}
                            <td class="px-4 py-3 align-top">
                                <code class="text-xs font-mono text-indigo-600 dark:text-indigo-400 break-all leading-tight">{{ $label->label_key }}</code>
                            </td>

                            {{-- Section --}}
                            <td class="px-4 py-3 align-top">
                                @if($label->section)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">{{ $label->section->name }}</span>
                                @endif
                            </td>

                            {{-- Source text (default lang) --}}
                            <td class="px-4 py-3 align-top">
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed italic">{{ $label->resolve() }}</p>
                            </td>

                            {{-- Translation input --}}
                            <td class="px-4 py-3 align-top">
                                <div class="relative">
                                    <textarea
                                        wire:model="editBuffer.{{ $label->id }}"
                                        rows="2"
                                        placeholder="{{ $hasTranslation ? $savedValue : 'Enter translation or use ✦ AI…' }}"
                                        class="w-full text-xs px-2.5 py-2 border rounded-lg resize-y leading-relaxed transition-colors
                                               {{ $isDirty
                                                    ? 'border-violet-400 dark:border-violet-500 bg-violet-50/30 dark:bg-violet-900/10 text-gray-900 dark:text-gray-100'
                                                    : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}
                                               focus:outline-none focus:ring-2 focus:ring-violet-400 placeholder-gray-400 dark:placeholder-gray-500"
                                    >{{ $hasTranslation && !$inBuffer ? $savedValue : $bufferValue }}</textarea>
                                    @if($isDirty)
                                    <span class="absolute -top-1.5 -right-1.5 w-3 h-3 rounded-full bg-violet-400 border-2 border-white dark:border-gray-800" title="Unsaved changes"></span>
                                    @endif
                                </div>
                            </td>

                            {{-- Status badge --}}
                            <td class="px-4 py-3 align-top">
                                @if(!$hasTranslation)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">Pending</span>
                                @elseif($status === 'reviewed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Reviewed</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">AI Draft</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-3 py-3 align-top">
                                <div class="flex flex-col gap-1.5">
                                    <button wire:click="saveTranslation({{ $label->id }})"
                                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                   {{ $isDirty ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300' }}
                                                   transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Save
                                    </button>
                                    <button wire:click="aiTranslateLabelInline({{ $label->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="aiTranslateLabelInline({{ $label->id }})"
                                            title="Generate AI translation — result goes into the input above for review"
                                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg border border-violet-200 dark:border-violet-700 text-violet-700 dark:text-violet-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">
                                        <span wire:loading wire:target="aiTranslateLabelInline({{ $label->id }})" class="animate-spin w-3 h-3 border-2 border-violet-400 border-t-transparent rounded-full inline-block"></span>
                                        <svg class="w-3 h-3" wire:loading.remove wire:target="aiTranslateLabelInline({{ $label->id }})" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        ✦ AI
                                    </button>
                                    @if($hasTranslation)
                                    <button wire:click="clearTranslation({{ $label->id }})"
                                            wire:confirm="Remove this translation and fall back to the default language text?"
                                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Clear
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <svg class="mx-auto w-10 h-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No labels found for your current filters.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Pagination --}}
            @if($this->labels->hasPages())
            <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-5 py-3">
                {{ $this->labels->links() }}
            </div>
            @endif
        </main>
    </div>
</div>
