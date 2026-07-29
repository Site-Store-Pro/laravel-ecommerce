<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                    CMS Categories
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Manage categories to classify your pages and posts.
                </p>
            </div>
            <div>
                <button wire:click="openForm" 
                   class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-6 py-3 rounded-2xl shadow-md shadow-indigo-100 hover:shadow-lg hover:shadow-indigo-200 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create New Category
                </button>
            </div>
        </div>

        @if(session()->has('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl flex items-center gap-3 font-semibold text-sm">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar Navigation -->
            <div class="lg:col-span-1">
                @include('layouts.cms-sidebar')
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-3 space-y-6">
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/60 text-emerald-800 dark:text-emerald-400 rounded-2xl flex items-center gap-3 font-semibold text-sm">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 {{ $isFormOpen ? 'lg:grid-cols-3' : 'lg:grid-cols-1' }} gap-8">
                    <!-- Categories Table / List -->
                    <div class="{{ $isFormOpen ? 'lg:col-span-2' : '' }} bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                                <thead>
                                    <tr class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                        <th class="pb-4">Name</th>
                                        <th class="pb-4">Slug</th>
                                        <th class="pb-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                                    @forelse($categories as $category)
                                        <tr class="hover:bg-slate-50/20 dark:hover:bg-slate-700/10 transition-colors">
                                            <td class="py-4 font-bold text-slate-800 dark:text-slate-200">{{ $category->name }}</td>
                                            <td class="py-4 text-slate-500 dark:text-slate-400">
                                                <div class="flex items-center gap-1.5">
                                                    <span>{{ $category->slug }}</span>
                                                    <a href="{{ route('cms.category', $category->slug) }}" target="_blank" title="View Public Landing Page" class="text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors inline-flex">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="py-4 text-right space-x-2">
                                                <button wire:click="openForm({{ $category->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-semibold">Edit</button>
                                                <button wire:click="deleteCategory({{ $category->id }})" 
                                                        wire:confirm="Are you sure you want to delete this category?" 
                                                        class="text-rose-600 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-300 font-semibold">Delete</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-8 text-center text-slate-400 dark:text-slate-500 font-medium">No categories created yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $categories->links() }}
                        </div>
                    </div>

                    <!-- Form Sidebar/Card -->
                    @if($isFormOpen)
                        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm h-fit">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                                <span class="w-1 h-5 bg-indigo-600 rounded"></span>
                                {{ $categoryId ? 'Edit Category' : 'Create Category' }}
                            </h3>

                            <form wire:submit.prevent="save" class="space-y-6">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Category Name</label>
                                    <input type="text" wire:model.live="name" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-500" placeholder="e.g. Blog" />
                                    @error('name') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Category Slug</label>
                                    <input type="text" wire:model="slug" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-500" placeholder="e.g. blog" />
                                    @error('slug') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>

                                {{-- ─── Translations Section ──────────────────────────────────────────────── --}}
                                @if($activeLanguages->isNotEmpty() && $categoryId)
                                <div x-data="{ tlOpen: false }" class="border-t border-slate-100 dark:border-slate-700 pt-4">
                                    <button type="button" @click="tlOpen = !tlOpen"
                                            class="flex items-center justify-between w-full text-left">
                                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                            Translations
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
                                            <div class="space-y-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                                                {{-- per-field inputs --}}
                                                <div>
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Name (Default: "{{ $name }}")</label>
                                                    <input type="text" wire:model="tlBuffer.name"
                                                           placeholder="Translation..."
                                                           class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                                </div>

                                                <div class="flex gap-2 pt-1">
                                                    <button type="button" wire:click="aiTlCategory({{ $categoryId }})"
                                                            wire:loading.attr="disabled" wire:target="aiTlCategory({{ $categoryId }})"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400 text-xs font-bold rounded-lg transition disabled:opacity-60">
                                                        <span wire:loading.remove wire:target="aiTlCategory({{ $categoryId }})">
                                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L9.09 9.09 2 12l7.09 2.91L12 22l2.91-7.09L22 12l-7.09-2.91L12 2z"/></svg>
                                                        </span>
                                                        <span wire:loading wire:target="aiTlCategory({{ $categoryId }})" class="inline-flex">
                                                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                        </span>
                                                        AI Translate All
                                                    </button>
                                                    <button type="button" wire:click="saveTlCategory({{ $categoryId }})"
                                                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
                                                        Save Translation
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <div class="flex gap-3 justify-end border-t border-slate-50 dark:border-slate-700 pt-6">
                                    <button type="button" wire:click="$set('isFormOpen', false)" class="px-5 py-2.5 bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-colors">Cancel</button>
                                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl text-sm shadow-md transition-colors">Save</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
