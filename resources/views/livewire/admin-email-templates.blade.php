<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Email Notification Templates</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Manage dynamic custom email notifications sent from your online store.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.email-templates.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 shadow-md shadow-indigo-200 dark:shadow-none transition-all duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Email Template
            </a>
        </div>
    </div>

    <!-- Banner Alerts -->
    @if (session()->has('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-medium flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('status') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-medium flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Search Controls -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4 mb-6">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search templates by profile name or subject line..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm">
        </div>
    </div>

    <!-- Email Types Listing -->
    <div class="space-y-6">
        @foreach($types as $type)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <!-- Type Header -->
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                            {{ $type->ordering }}
                        </span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ $type->name }}</h2>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Slug: <code class="bg-slate-100 dark:bg-slate-900 px-1 py-0.5 rounded text-indigo-600 dark:text-indigo-400 font-mono">{{ $type->slug }}</code></p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.email-templates.create', ['type_id' => $type->id]) }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Profile
                        </a>
                    </div>
                </div>

                <!-- Templates List -->
                @if($type->templates->isEmpty())
                    <div class="p-6 text-center text-sm text-slate-400 dark:text-slate-500">
                        No profiles configured for this notification.
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($type->templates as $tpl)
                            <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 hover:bg-slate-50/30 dark:hover:bg-slate-900/10 transition-colors">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white truncate">
                                            {{ $tpl->profile_name }}
                                        </h3>
                                        @if($tpl->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-2xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900">
                                                Active
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 truncate">
                                        Subject: <span class="font-mono text-slate-600 dark:text-slate-400 font-medium">{{ $tpl->subject }}</span>
                                    </p>
                                    @if($tpl->from_address)
                                        <p class="text-3xs text-slate-400 mt-0.5">
                                            From: {{ $tpl->from_name ? $tpl->from_name . ' <' . $tpl->from_address . '>' : $tpl->from_address }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 self-end sm:self-center">
                                    <!-- Active Toggle Button -->
                                    @if(!$tpl->is_active)
                                        <button wire:click="toggleActive({{ $tpl->id }})" wire:confirm="Are you sure you want to activate this template? This will deactivate the current active template." class="inline-flex items-center justify-center px-2.5 py-1.5 border border-slate-200 dark:border-slate-700 text-2xs font-semibold rounded-lg text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-2xs">
                                            Make Active
                                        </button>
                                    @endif

                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.email-templates.edit', $tpl->id) }}" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors" title="Edit Template">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <!-- Duplicate Button -->
                                    <button wire:click="duplicateTemplate({{ $tpl->id }})" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors" title="Duplicate/Copy Template">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <button wire:click="deleteTemplate({{ $tpl->id }})" wire:confirm="Are you sure you want to delete this email template?" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors" title="Delete Template">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
