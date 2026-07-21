<div>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">
                    {{ __('Knowledge Base Articles') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Manage public articles, guides, and documentations</p>
            </div>
            <a href="{{ route('admin.kb.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-sm shadow-indigo-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Article
            </a>
            <a href="{{ route('admin.kb.categories') }}" wire:navigate
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-sm font-semibold shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Categories
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-toast-alert />

            {{-- Stats Row --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-slate-50 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Articles</p>
                    <h3 class="text-3xl font-extrabold text-slate-700 mt-2">{{ $totalCount }}</h3>
                </div>

                <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-emerald-50 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active / Visible</p>
                    <h3 class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $activeCount }}</h3>
                </div>

                <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-amber-50 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hidden / Draft</p>
                    <h3 class="text-3xl font-extrabold text-amber-600 mt-2">{{ $inactiveCount }}</h3>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm p-5">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-label for="search" :value="__('Search Articles')" class="text-slate-500 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <x-text-input wire:model.live.debounce.300ms="search" id="search"
                                          class="block w-full ps-9 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                          type="search" placeholder="Search by title, content or slug..." />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="statusFilter" :value="__('Status Filter')" class="text-slate-500 font-semibold" />
                        <select wire:model.live="statusFilter" id="statusFilter"
                                class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm">
                            <option value="">All Statuses</option>
                            <option value="active">Active (Visible)</option>
                            <option value="inactive">Inactive (Draft)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Articles Table --}}
            <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Article</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">SEO Link / Slug</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Sort</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Action</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Added</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Modified</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($articles as $article)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-semibold text-slate-900 max-w-xs truncate">{{ $article->title }}</div>
                                            @if($article->meta_description)
                                                <div class="text-xs text-slate-400 max-w-xs truncate mt-0.5">{{ $article->meta_description }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        <a href="{{ route('kb.show', $article->seo_link) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline flex items-center gap-1 max-w-[200px]">
                                            <span class="truncate">/kb/{{ $article->seo_link }}</span>
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button wire:click="toggleActive({{ $article->id }})"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold border transition-all
                                                {{ $article->article_active === 1 ? 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 border-slate-200 text-slate-600 hover:bg-slate-200' }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $article->article_active === 1 ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            {{ $article->article_active === 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $article->sort_order }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                        <div class="flex items-center justify-start gap-3" x-data="{ confirmingDelete: false }">
                                             <a href="{{ route('admin.kb.edit', $article->id) }}"
                                                class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                Edit
                                             </a>

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
                                                <button wire:click="deleteArticle({{ $article->id }})"
                                                        class="text-white bg-rose-600 hover:bg-rose-700 px-2 py-1 rounded-xl text-xs">
                                                    Confirm
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $article->date_added ?? $article->created_at?->format('Y-m-d') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $article->date_modified ?? $article->updated_at?->format('Y-m-d') ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-500 text-sm">
                                        No Knowledge Base articles found matching your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if ($articles->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
