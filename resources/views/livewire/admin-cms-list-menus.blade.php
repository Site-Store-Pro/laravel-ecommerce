<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                    List Menus Manager
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Create and manage reusable list menus that can be placed in footers, headers, or CMS pages using `[list:ID]` shortcodes.
                </p>
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

                <!-- Create New Menu Card -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200/60 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4">Create New List Menu</h2>
                    <form wire:submit.prevent="createMenu" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input wire:model="newMenuName" type="text" placeholder="e.g. Footer Link List" 
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
                            @error('newMenuName') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" 
                                class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-6 py-3 rounded-2xl shadow-md shadow-indigo-100 hover:shadow-lg hover:shadow-indigo-200 transition-all inline-flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Menu
                        </button>
                    </form>
                </div>

                <!-- Search Card -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200/60 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm">
                    <div class="max-w-md">
                        <label for="search" class="sr-only">Search menus</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input wire:model.live="search" type="text" id="search" placeholder="Search menus by name..." 
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
                        </div>
                    </div>
                </div>

                <!-- Menus Table Card -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200/60 dark:border-slate-700/80 rounded-3xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    <th class="py-4 px-6">Menu Name</th>
                                    <th class="py-4 px-6 text-center">Items Count</th>
                                    <th class="py-4 px-6">Shortcode</th>
                                    <th class="py-4 px-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                                @forelse($menus as $menu)
                                    <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-700/20 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-slate-900 dark:text-slate-100">{{ $menu->name }}</div>
                                            <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Created on {{ $menu->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="py-4 px-6 text-center text-slate-600 dark:text-slate-300 font-semibold">
                                            {{ $menu->items_count }}
                                        </td>
                                        <td class="py-4 px-6 font-mono text-xs">
                                            <div class="flex items-center gap-2" x-data="{ copied: false }">
                                                <span class="bg-slate-100 dark:bg-slate-900 px-2.5 py-1 rounded text-slate-700 dark:text-slate-300 font-semibold select-all">[list:{{ $menu->id }}]</span>
                                                <button type="button" 
                                                        @click="navigator.clipboard.writeText('[list:{{ $menu->id }}]'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                        class="text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center" 
                                                        title="Copy shortcode">
                                                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                    <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.cms-list-menus.edit', $menu->id) }}" wire:navigate 
                                                   class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <button wire:click="deleteMenu({{ $menu->id }})" 
                                                        wire:confirm="Are you sure you want to delete this list menu? All its item associations will be permanently removed."
                                                        class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition-all shadow-sm">
                                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                            No list menus found. Type a name above to create your first menu list!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($menus->hasPages())
                        <div class="p-6 border-t border-slate-100 dark:border-slate-700">
                            {{ $menus->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
