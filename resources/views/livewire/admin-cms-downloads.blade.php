<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vivid.min.css">
        @endpush

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-teal-900 bg-clip-text text-transparent">
                    CMS Downloads
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Manage secure file downloads added to CMS pages via shortcodes.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.cms-downloads.create') }}" wire:navigate
                   class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white font-semibold px-6 py-3 rounded-2xl shadow-md shadow-teal-100 hover:shadow-lg hover:shadow-teal-200 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Download
                </a>
            </div>
        </div>

        @if(session()->has('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl flex items-center gap-3 font-semibold text-sm">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Left Sidebar --}}
            <div class="lg:col-span-1">
                @include('layouts.cms-sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Search & Filter Bar --}}
                <div class="bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-3xl p-5 shadow-sm">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input wire:model.live.debounce.300ms="search"
                                   type="text"
                                   placeholder="Search by name or label…"
                                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                        </div>
                        <select wire:model.live="filterActive"
                                class="px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 focus:outline-none focus:border-teal-500 transition-colors">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                {{-- Table --}}
                <div class="bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden">
                    @if($downloads->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                            <div class="w-16 h-16 bg-teal-50 dark:bg-teal-950/40 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </div>
                            <h3 class="text-slate-700 dark:text-slate-300 font-bold text-lg">No downloads yet</h3>
                            <p class="text-slate-400 text-sm mt-1 mb-6">Create your first download to add it to CMS pages via shortcodes.</p>
                            <a href="{{ route('admin.cms-downloads.create') }}" wire:navigate
                               class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-semibold px-5 py-2.5 rounded-xl shadow-sm inline-flex items-center gap-2 hover:opacity-90 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                New Download
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-800/60">
                                    <tr class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                        <th class="px-6 py-4 w-12">Type</th>
                                        <th class="px-4 py-4">Name</th>
                                        <th class="px-4 py-4">Shortcode</th>
                                        <th class="px-4 py-4">Source</th>
                                        <th class="px-4 py-4 text-center">Active</th>
                                        <th class="px-4 py-4">Added</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                                    @foreach($downloads as $download)
                                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition-colors">
                                            {{-- File Type Icon --}}
                                            <td class="px-6 py-4">
                                                @if($ext = $download->fileExtension())
                                                    <div class="flex flex-col items-center gap-1">
                                                        <span class="fiv-cla fiv-viv fiv-sqo fiv-icon-{{ $ext }}"
                                                              style="font-size:2em; display:block; line-height:1;"
                                                              title=".{{ strtoupper($ext) }}"></span>
                                                        @if($download->show_icon > 0)
                                                            @php $pos = ['','L','R','T','B'][$download->show_icon] ?? ''; @endphp
                                                            <span class="text-[8px] font-extrabold text-teal-600 bg-teal-50 border border-teal-100 rounded px-1">ICON {{ $pos }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-slate-300 dark:text-slate-600 text-lg">—</span>
                                                @endif
                                            </td>

                                            {{-- Internal Name --}}
                                            <td class="px-4 py-4">
                                                <a href="{{ route('admin.cms-downloads.edit', $download->id) }}" wire:navigate
                                                   class="font-semibold text-slate-800 dark:text-slate-200 hover:text-teal-600 dark:hover:text-teal-400 transition-colors text-sm">
                                                    {{ $download->internal_name }}
                                                </a>
                                                @if($download->link_label)
                                                    <p class="text-xs text-slate-400 mt-0.5">{{ $download->link_label }}</p>
                                                @endif
                                                @if($download->isExpired())
                                                    <span class="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 bg-red-50 border border-red-100 text-red-600 text-[9px] font-bold uppercase rounded-full">
                                                        Expired
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Shortcode --}}
                                            <td class="px-4 py-4">
                                                <code class="text-[10px] font-mono bg-teal-50 dark:bg-teal-950/30 text-teal-700 dark:text-teal-400 border border-teal-100 dark:border-teal-900/50 rounded-lg px-2 py-1 select-all cursor-text whitespace-nowrap">
                                                    [download:{{ $download->id }}{{ $download->link_label ? ' label="' . $download->link_label . '"' : '' }}]
                                                </code>
                                            </td>

                                            {{-- Source Type Badge --}}
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $download->sourceTypeBadgeColor() }}">
                                                    {{ $download->sourceTypeLabel() }}
                                                </span>
                                            </td>

                                            {{-- Active Toggle --}}
                                            <td class="px-4 py-4 text-center">
                                                <button wire:click="toggleActive({{ $download->id }})"
                                                        wire:confirm="{{ $download->is_active ? 'Deactivate this download?' : 'Activate this download?' }}"
                                                        class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none {{ $download->is_active ? 'bg-teal-500' : 'bg-slate-200 dark:bg-slate-700' }}">
                                                    <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow-sm transition-transform {{ $download->is_active ? 'translate-x-4' : 'translate-x-0.5' }}"></span>
                                                </button>
                                            </td>

                                            {{-- Date --}}
                                            <td class="px-4 py-4 text-xs text-slate-400 whitespace-nowrap">
                                                {{ $download->created_at->format('M j, Y') }}
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.cms-downloads.edit', $download->id) }}" wire:navigate
                                                       class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 dark:border-slate-600 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-slate-100 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <button wire:click="deleteDownload({{ $download->id }})"
                                                            wire:confirm="Delete '{{ $download->internal_name }}'? This cannot be undone."
                                                            class="inline-flex items-center justify-center rounded-lg border border-red-100 dark:border-red-900/40 px-2.5 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/40 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($downloads->hasPages())
                            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                                {{ $downloads->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
