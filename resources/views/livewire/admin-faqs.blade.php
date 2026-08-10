<div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-slate-900 to-indigo-900 dark:from-white dark:to-indigo-300 bg-clip-text text-transparent">
                    FAQ Manager
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Manage Frequently Asked Questions. Drag to reorder. Use <code class="font-mono bg-slate-100 dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 px-1.5 py-0.5 rounded text-xs">[plugin:faqs-2026]</code> to embed the accordion on any page.
                </p>
            </div>
            @if(!$isCreating && !$isEditing)
            <button wire:click="startCreate"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-md shadow-indigo-100 dark:shadow-none transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New FAQ
            </button>
            @endif
        </div>

        <x-toast-alert />

        {{-- ── Inline Create / Edit Form ─────────────────────────────────────── --}}
        @if($isCreating || $isEditing)
        <div class="mb-8 bg-white dark:bg-slate-800 border-2 border-indigo-200 dark:border-indigo-700/50 rounded-3xl p-8 shadow-md"
             x-data x-init="$el.scrollIntoView({behavior:'smooth',block:'start'})">
            <h3 class="text-base font-bold text-indigo-800 dark:text-indigo-300 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="{{ $isEditing ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M12 4v16m8-8H4' }}"/>
                </svg>
                {{ $isEditing ? 'Edit FAQ' : 'New FAQ' }}
            </h3>

            <div class="space-y-5">
                {{-- Question --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Question <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="question"
                           id="faq_question_input"
                           placeholder="e.g. What is your return policy?"
                           class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 shadow-sm transition">
                    @error('question') <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Answer --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Answer <span class="text-rose-500">*</span>
                    </label>
                    <textarea wire:model="answer"
                              rows="5"
                              placeholder="Write the full answer here..."
                              class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 shadow-sm resize-y transition"></textarea>
                    @error('answer') <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Sort Order --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sort Order</label>
                        <input type="number"
                               wire:model="sort_order"
                               min="0"
                               class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:border-indigo-400 shadow-sm">
                    </div>

                    {{-- Active toggle --}}
                    <div class="flex items-center pt-5 gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-2 text-sm font-semibold text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                    </div>
                </div>

                {{-- FAQ Translations Manager --}}
                @php
                    $activeLangs = \App\Models\Language::where('is_active', true)->where('is_default', false)->get();
                @endphp
                @if($isEditing && $activeLangs->isNotEmpty())
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700 space-y-4">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 dark:text-white flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                    <span>FAQ Language Translations</span>
                                </h4>
                                <p class="text-2xs text-slate-500 dark:text-slate-400 mt-0.5">View, edit, and translate this FAQ's question and answer for each active non-default language.</p>
                            </div>

                            {{-- Language switcher pills --}}
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @foreach($activeLangs as $lang)
                                    <button type="button"
                                            wire:click="selectTranslationLang('{{ $lang->code }}', {{ $lang->id }})"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition border
                                                {{ $activeLangCode === $lang->code
                                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                                                    : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-indigo-300 hover:bg-indigo-50 dark:hover:bg-slate-700' }}">
                                        <span>{{ $lang->flag_emoji }}</span>
                                        <span>{{ $lang->native_name }}</span>
                                        @php
                                            $tRec = \App\Models\CmsFaqTranslation::where('cms_faq_id', $faqId)->where('language_id', $lang->id)->first();
                                        @endphp
                                        @if($tRec)
                                            <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded-full {{ $tRec->translation_status === 'reviewed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-200' }}">
                                                {{ $tRec->translation_status === 'reviewed' ? '✓' : 'AI' }}
                                            </span>
                                        @else
                                            <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-400">—</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @if($activeLangCode)
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-4">
                                {{-- Status & AI Actions --}}
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xs font-bold text-slate-500 uppercase tracking-wider">Status:</span>
                                        <span class="px-2 py-0.5 rounded-lg text-2xs font-bold
                                            {{ $trans_status === 'reviewed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200' : ($trans_status === 'ai_translated' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-200' : 'bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-400') }}">
                                            {{ $trans_status === 'reviewed' ? 'Reviewed' : ($trans_status === 'ai_translated' ? 'AI Translated' : 'Pending') }}
                                        </span>
                                        @if($trans_translated_at)
                                            <span class="text-2xs text-slate-400">Last updated: {{ $trans_translated_at }}</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                wire:click="aiTranslateFaqInline"
                                                wire:loading.attr="disabled"
                                                class="flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 dark:bg-violet-900/30 hover:bg-violet-100 dark:hover:bg-violet-900/50 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-700 rounded-xl text-2xs font-bold transition">
                                            <span wire:loading wire:target="aiTranslateFaqInline" class="animate-spin inline-block w-3 h-3 border-2 border-violet-500 border-t-transparent rounded-full"></span>
                                            <svg wire:loading.remove wire:target="aiTranslateFaqInline" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            ✦ Generate AI Translation
                                        </button>
                                        <button type="button"
                                                wire:click="autoTranslateFaq"
                                                wire:loading.attr="disabled"
                                                class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 dark:bg-amber-900/30 hover:bg-amber-100 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-700 rounded-xl text-2xs font-bold transition">
                                            <span wire:loading wire:target="autoTranslateFaq" class="animate-spin inline-block w-3 h-3 border-2 border-amber-500 border-t-transparent rounded-full"></span>
                                            <svg wire:loading.remove wire:target="autoTranslateFaq" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                            Queue Bulk Job
                                        </button>
                                    </div>
                                </div>

                                {{-- Editable Translation Fields --}}
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Translated Question</label>
                                        <input type="text" wire:model="trans_question" placeholder="Translated question text..." class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500">
                                    </div>

                                    <div>
                                        <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Translated Answer</label>
                                        <textarea wire:model="trans_answer" rows="4" placeholder="Translated answer content..." class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500"></textarea>
                                    </div>
                                </div>

                                {{-- Save Translation Button --}}
                                <div class="flex justify-end pt-1">
                                    <button type="button"
                                            wire:click="saveTranslation"
                                            wire:loading.attr="disabled"
                                            class="flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow transition">
                                        <span wire:loading wire:target="saveTranslation" class="animate-spin inline-block w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full"></span>
                                        Save Translation
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Form actions --}}
            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100 dark:border-slate-700">
                <button wire:click="cancel" type="button"
                        class="px-4 py-2 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition">
                    Cancel
                </button>
                <button wire:click="saveFaq" wire:loading.attr="disabled" type="button"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow transition">
                    <span wire:loading.remove wire:target="saveFaq">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <span wire:loading wire:target="saveFaq"
                          class="inline-block w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    {{ $isEditing ? 'Update FAQ' : 'Create FAQ' }}
                </button>
            </div>
        </div>
        @endif

        {{-- ── Delete Confirmation Modal ─────────────────────────────────────── --}}
        @if($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-8 max-w-sm w-full space-y-4 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Delete FAQ?</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">This action cannot be undone.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button wire:click="cancelDelete"
                            class="px-4 py-2 border border-slate-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button wire:click="deleteFaq"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Search bar ───────────────────────────────────────────────────── --}}
        <div class="mb-4 flex items-center gap-4">
            <div class="relative flex-1 max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search questions or answers..."
                       class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 shadow-sm placeholder:text-slate-400 transition">
            </div>
            @if($search)
            <button wire:click="$set('search','')" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-semibold transition">
                Clear
            </button>
            @endif
            <span class="text-xs text-slate-400 dark:text-slate-500 ml-auto">
                {{ $faqs->count() }} {{ Str::plural('item', $faqs->count()) }}
                @if($search) matching <em>"{{ $search }}"</em> @endif
            </span>
        </div>

        {{-- ── FAQ Sortable List ────────────────────────────────────────────── --}}
        @if($faqs->isEmpty())
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl py-20 flex flex-col items-center gap-4 shadow-sm">
            <svg class="w-14 h-14 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-slate-400 dark:text-slate-500 text-sm font-medium">
                @if($search)
                    No FAQs match your search.
                @else
                    No FAQs yet. Create your first one!
                @endif
            </p>
            @if(!$search && !$isCreating && !$isEditing)
            <button wire:click="startCreate"
                    class="mt-1 inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New FAQ
            </button>
            @endif
        </div>
        @else

        {{-- Sortable list — uses Alpine.js + SortableJS --}}
        <div
            id="faq-sortable-list"
            class="space-y-3"
            x-data="{
                sortableInstance: null,
                init() {
                    if (typeof Sortable === 'undefined') {
                        // Load SortableJS on demand if not already present
                        const s = document.createElement('script');
                        s.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js';
                        s.onload = () => this.initSortable();
                        document.head.appendChild(s);
                    } else {
                        this.initSortable();
                    }
                },
                initSortable() {
                    this.sortableInstance = Sortable.create(this.$el, {
                        animation: 200,
                        handle: '.faq-drag-handle',
                        ghostClass: 'opacity-30',
                        onEnd: () => {
                            const order = [...this.$el.querySelectorAll('[data-faq-id]')]
                                .map(el => parseInt(el.dataset.faqId));
                            $wire.updateFaqOrder(order);
                        }
                    });
                }
            }"
        >
            @foreach($faqs as $faq)
            <div data-faq-id="{{ $faq->id }}"
                 wire:key="faq-row-{{ $faq->id }}"
                 class="group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 shadow-sm flex items-start gap-4 transition hover:shadow-md {{ !$faq->is_active ? 'opacity-60' : '' }} {{ $isEditing && $faqId === $faq->id ? 'ring-2 ring-indigo-300 dark:ring-indigo-600' : '' }}">

                {{-- Drag handle --}}
                @if(!$search)
                <div class="faq-drag-handle cursor-grab active:cursor-grabbing text-slate-300 dark:text-slate-600 hover:text-slate-400 shrink-0 mt-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </div>
                @else
                <div class="w-5 shrink-0"></div>
                @endif

                {{-- Sort badge --}}
                <div class="shrink-0 w-7 h-7 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-400 dark:text-slate-500 mt-0.5">
                    {{ $faq->sort_order }}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-slate-800 dark:text-slate-100 text-sm leading-snug">
                        {{ Str::limit($faq->question, 120) }}
                    </p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 leading-relaxed">
                        {{ Str::limit(strip_tags($faq->answer), 140) }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 shrink-0 ml-auto">
                    {{-- Active toggle --}}
                    <button wire:click="toggleActive({{ $faq->id }})"
                            title="{{ $faq->is_active ? 'Deactivate' : 'Activate' }}"
                            class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $faq->is_active ? 'bg-indigo-600' : 'bg-slate-300 dark:bg-slate-600' }}">
                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition {{ $faq->is_active ? 'translate-x-4' : 'translate-x-1' }}"></span>
                    </button>

                    {{-- Edit --}}
                    <button wire:click="editFaq({{ $faq->id }})"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-xl transition border border-indigo-100 dark:border-indigo-800/50">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>

                    {{-- Delete --}}
                    <button wire:click="confirmDelete({{ $faq->id }})"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-900/40 rounded-xl transition border border-rose-100 dark:border-rose-800/50">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        @if($search)
        <p class="mt-3 text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/40 rounded-xl px-3 py-2">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Drag-to-sort is disabled while filtering. Clear the search to re-enable.
        </p>
        @endif

        @endif

        {{-- Shortcode reference --}}
        <div class="mt-8 flex items-start gap-3 px-5 py-4 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs text-slate-500 dark:text-slate-400">
            <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
            <div>
                <strong class="text-slate-700 dark:text-slate-300">Shortcode:</strong>
                Embed the FAQ accordion on any CMS page using
                <code class="font-mono bg-slate-100 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 px-1.5 py-0.5 rounded mx-1">[plugin:faqs-2026]</code>
                — only <strong class="text-slate-600 dark:text-slate-300">active</strong> FAQs will be shown, in sort order.
                Configure the header title and CSS override via
                <a href="{{ route('admin.plugins.index') }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">Admin → Plugins → FAQ Accordion Display</a>.
            </div>
        </div>

    </div>
</div>
