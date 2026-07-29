<div>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">KB Categories</h2>
                <p class="text-sm text-slate-500 mt-1">Manage article categories for the Knowledge Base</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.kb.index') }}" wire:navigate
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Articles
                </a>
                @if(!$creating)
                    <button x-data x-on:click="$dispatch('start-creating')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-sm shadow-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Category
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Inline Create/Edit Form --}}
            @if($creating)
                <div class="bg-white border border-indigo-200 rounded-2xl shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 text-base mb-5 pb-3 border-b border-slate-100">
                        {{ $editingId ? 'Edit Category' : 'New Category' }}
                    </h3>
                    <div class="grid gap-5 md:grid-cols-2">
                        {{-- Name --}}
                        <div>
                            <x-input-label for="cat-name" :value="__('Category Name')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                            <x-text-input wire:model.live="name" id="cat-name" type="text"
                                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                          placeholder="e.g. Getting Started" autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs" />
                        </div>

                        {{-- Slug --}}
                        <div>
                            <x-input-label for="cat-slug" :value="__('Slug')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                            <x-text-input wire:model="slug" id="cat-slug" type="text"
                                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                          placeholder="getting-started" />
                            <x-input-error :messages="$errors->get('slug')" class="mt-1.5 text-xs" />
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <x-input-label for="cat-desc" :value="__('Description (Optional)')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                            <textarea wire:model="description" id="cat-desc" rows="2"
                                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                      placeholder="Brief description of this category..."></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1.5 text-xs" />
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <x-input-label for="cat-sort" :value="__('Sort Order')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                            <x-text-input wire:model="sort_order" id="cat-sort" type="number"
                                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                          placeholder="0" />
                            <p class="text-[10px] text-slate-400 mt-1">Lower numbers appear first in dropdowns.</p>
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-1.5 text-xs" />
                        </div>
                    </div>

                    {{-- ─── Translations Section ──────────────────────────────────────────────── --}}
                    @if($activeLanguages->isNotEmpty() && $editingId)
                    <div x-data="{ tlOpen: false }" class="border-t border-slate-100 mt-6 pt-4">
                        <button type="button" @click="tlOpen = !tlOpen"
                                class="flex items-center justify-between w-full text-left">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
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
                                                    : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-400' }}">
                                        <span class="fi fi-{{ strtolower($lang->flag_emoji) }}" style="width:1em;height:0.75em;font-size:1rem;"></span>
                                        {{ $lang->name }}
                                    </button>
                                @endforeach
                            </div>

                            @if($tlLangId > 0)
                                <div class="space-y-3 bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    {{-- per-field inputs --}}
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Name (Default: "{{ $name }}")</label>
                                        <input type="text" wire:model="tlBuffer.name"
                                               placeholder="Translation..."
                                               class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                    </div>

                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Description</label>
                                        <textarea wire:model="tlBuffer.description"
                                                  placeholder="Translation..."
                                                  rows="2"
                                                  class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500"></textarea>
                                    </div>

                                    <div class="flex gap-2 pt-1">
                                        <button type="button" wire:click="aiTlCategory({{ $editingId }})"
                                                wire:loading.attr="disabled" wire:target="aiTlCategory({{ $editingId }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-600 text-xs font-bold rounded-lg transition disabled:opacity-60">
                                            <span wire:loading.remove wire:target="aiTlCategory({{ $editingId }})">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L9.09 9.09 2 12l7.09 2.91L12 22l2.91-7.09L22 12l-7.09-2.91L12 2z"/></svg>
                                            </span>
                                            <span wire:loading wire:target="aiTlCategory({{ $editingId }})" class="inline-flex">
                                                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                            </span>
                                            AI Translate All
                                        </button>
                                        <button type="button" wire:click="saveTlCategory({{ $editingId }})"
                                                class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
                                            Save Translation
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-100">
                        <button wire:click="cancelForm" type="button"
                                class="text-sm text-slate-500 hover:text-slate-700 font-medium transition-colors">
                            Cancel
                        </button>
                        @if($editingId)
                            <x-primary-button wire:click="update" wire:loading.attr="disabled"
                                              class="rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500">
                                <span wire:loading.remove wire:target="update">Save Changes</span>
                                <span wire:loading wire:target="update">Saving...</span>
                            </x-primary-button>
                        @else
                            <x-primary-button wire:click="save" wire:loading.attr="disabled"
                                              class="rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500">
                                <span wire:loading.remove wire:target="save">Create Category</span>
                                <span wire:loading wire:target="save">Creating...</span>
                            </x-primary-button>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Search --}}
            <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm p-5">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <x-text-input wire:model.live.debounce.300ms="search" id="cat-search"
                                  class="block w-full ps-9 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                  type="search" placeholder="Search categories..." />
                </div>
            </div>

            {{-- Categories Table --}}
            <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Slug</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Articles</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Sort</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-slate-900">{{ $category->name }}</div>
                                        @if($category->description)
                                            <div class="text-xs text-slate-400 mt-0.5 max-w-xs truncate">{{ $category->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded-lg">{{ $category->slug }}</code>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold {{ $category->articles_count > 0 ? 'text-indigo-600' : 'text-slate-400' }}">
                                            {{ $category->articles_count }} {{ Str::plural('article', $category->articles_count) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $category->sort_order }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3" x-data="{ confirmingDelete: false }">
                                            <button wire:click="startEditing({{ $category->id }})"
                                                    class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                Edit
                                            </button>
                                            <div x-show="!confirmingDelete">
                                                <button @click="confirmingDelete = true"
                                                        class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100/80 px-2.5 py-1.5 rounded-xl transition-colors">
                                                    Delete
                                                </button>
                                            </div>
                                            <div x-show="confirmingDelete" x-cloak class="flex items-center gap-1">
                                                <button @click="confirmingDelete = false"
                                                        class="text-slate-500 hover:text-slate-700 bg-slate-100 px-2 py-1 rounded-xl text-xs">
                                                    Cancel
                                                </button>
                                                <button wire:click="deleteCategory({{ $category->id }})"
                                                        class="text-white bg-rose-600 hover:bg-rose-700 px-2 py-1 rounded-xl text-xs">
                                                    Confirm
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">
                                        No categories found. Click "Add Category" to create the first one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($categories->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
