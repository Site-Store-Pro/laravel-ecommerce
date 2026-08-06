<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                    CMS Pages | Posts
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Create and customize gated landing pages, forms, and custom stylesheet/script inclusions.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.cms-pages.create') }}" 
                   class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-6 py-3 rounded-2xl shadow-md shadow-indigo-100 hover:shadow-lg hover:shadow-indigo-200 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create New Page
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar Navigation -->
            <div class="lg:col-span-1">
                @include('layouts.cms-sidebar')
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-6">
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl border border-emerald-100 dark:border-emerald-900/60 flex items-center gap-3 text-emerald-800 dark:text-emerald-400 text-sm font-semibold">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Filter/Search Card -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200/60 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm">
                    <div class="max-w-md">
                        <label for="search" class="sr-only">Search pages</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input wire:model.live="search" type="text" id="search" placeholder="Search pages by title or slug..." 
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
                        </div>
                    </div>
                </div>

                <!-- Pages Table Card -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200/60 dark:border-slate-700/80 rounded-3xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    <th class="py-4 px-6">Page Title</th>
                                    <th class="py-4 px-6">Slug</th>
                                    <th class="py-4 px-6">Author</th>
                                    <th class="py-4 px-6">Expirations & Security</th>
                                    <th class="py-4 px-6 text-center">Status</th>
                                    <th class="py-4 px-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                                @forelse($pages as $page)
                                    <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-700/20 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-slate-900 dark:text-slate-100">{{ $page->title }}</div>
                                            <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Created on {{ $page->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <a href="{{ route('page.show', $page->slug) }}" target="_blank" 
                                               class="inline-flex items-center gap-1.5 font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                                /{{ $page->slug }}
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </a>
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 dark:text-slate-300 font-medium">
                                            {{ $page->author ? $page->author->name : 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex flex-col gap-1">
                                                @if($page->expires_at)
                                                    <div class="inline-flex items-center gap-1 text-xs text-amber-700 dark:text-amber-400 font-semibold bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/60 rounded px-2 py-0.5 w-fit">
                                                        Expires: {{ $page->expires_at->format('M d, Y H:i') }}
                                                    </div>
                                                @endif
                                                @if($page->requires_code)
                                                    <div class="inline-flex items-center gap-1 text-xs text-blue-700 dark:text-blue-400 font-semibold bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/60 rounded px-2 py-0.5 w-fit">
                                                        Gated: Code Required
                                                    </div>
                                                @endif
                                                @if($page->required_product_id)
                                                    <div class="inline-flex items-center gap-1 text-xs text-purple-700 dark:text-purple-400 font-semibold bg-purple-50 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-900/60 rounded px-2 py-0.5 w-fit">
                                                        Requires Product: ID {{ $page->required_product_id }}
                                                    </div>
                                                @endif
                                                @if(!$page->expires_at && !$page->requires_code && !$page->required_product_id)
                                                    <span class="text-xs text-slate-400 dark:text-slate-500">Public Page</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <button wire:click="toggleActive({{ $page->id }})" 
                                                    class="inline-flex items-center justify-center font-bold px-3 py-1 rounded-full text-xs transition-all {{ $page->is_active ? 'bg-emerald-50 border border-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-900/60 dark:text-emerald-400' : 'bg-slate-100 border border-slate-200 text-slate-600 dark:bg-slate-700/60 dark:border-slate-600 dark:text-slate-400' }}">
                                                {{ $page->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.cms-pages.edit', $page->id) }}" 
                                                   class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <button wire:click="duplicatePage({{ $page->id }})" 
                                                        class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 transition-all shadow-sm">
                                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                                    </svg>
                                                    Duplicate
                                                </button>
                                                @if($page->id !== 1)
                                                    <button wire:click="deletePage({{ $page->id }})" 
                                                            wire:confirm="Are you sure you want to delete this dynamic page?"
                                                            class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-50 cursor-not-allowed" title="The Home Page cannot be deleted.">
                                                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                            No custom pages found. Click "Create New Page" to add one!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pages->hasPages())
                        <div class="p-6 border-t border-slate-100 dark:border-slate-700">
                            {{ $pages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
