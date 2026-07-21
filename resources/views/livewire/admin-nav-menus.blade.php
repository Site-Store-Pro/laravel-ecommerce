<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Navigation Builder</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Build and manage dynamic site navigation menus.</p>
        </div>
        <button wire:click="$set('showCreateForm', true)"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Menu
        </button>
    </div>

    {{-- Flash messages --}}
    @if($successMessage)
        <div class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $successMessage }}
        </div>
    @endif
    @if($errorMessage)
        <div class="mb-4 p-3 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 rounded-xl text-sm">
            {{ $errorMessage }}
        </div>
    @endif

    {{-- Create form --}}
    @if($showCreateForm)
    <div class="mb-6 p-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4">Create New Menu</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Menu Name *</label>
                <input wire:model="newName" type="text" placeholder="e.g. Main Menu"
                       class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('newName')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Initial Color Scheme</label>
                <select wire:model="newColorScheme"
                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach($schemes as $scheme)
                        <option value="{{ $scheme }}">{{ ucfirst($scheme) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button wire:click="createMenu"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Create Menu
            </button>
            <button wire:click="$set('showCreateForm', false)"
                    class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-lg transition-colors">
                Cancel
            </button>
        </div>
    </div>
    @endif

    {{-- Menus list --}}
    @if($menus->isEmpty())
        <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-600">
            <svg class="mx-auto w-10 h-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
            </svg>
            <p class="text-slate-500 dark:text-slate-400 text-sm">No navigation menus yet. Create your first menu above.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($menus as $menu)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Primary badge --}}
                    @if($menu->is_primary)
                        <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
                            PRIMARY
                        </span>
                    @endif
                    {{-- Color chip --}}
                    <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full
                        @switch($menu->color_scheme)
                            @case('dark') bg-slate-800 text-slate-200 @break
                            @case('indigo') bg-indigo-600 text-white @break
                            @case('slate') bg-slate-500 text-white @break
                            @case('transparent') bg-slate-100 dark:bg-slate-700 text-slate-500 border border-dashed border-slate-300 @break
                            @case('custom') bg-purple-100 text-purple-700 @break
                            @default bg-white border border-slate-200 text-slate-600 @break
                        @endswitch
                    ">{{ ucfirst($menu->color_scheme) }}</span>

                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $menu->name }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            slug: <code class="font-mono">{{ $menu->slug }}</code> &bull;
                            {{ $menu->items_count }} item{{ $menu->items_count !== 1 ? 's' : '' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    {{-- Active toggle --}}
                    <button wire:click="toggleActive({{ $menu->id }})"
                            class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors {{ $menu->is_active ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 hover:bg-slate-200' }}">
                        {{ $menu->is_active ? 'Active' : 'Inactive' }}
                    </button>

                    {{-- Set Primary --}}
                    @unless($menu->is_primary)
                    <button wire:click="setPrimary({{ $menu->id }})"
                            class="text-xs px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 font-medium transition-colors">
                        Set Primary
                    </button>
                    @endunless

                    {{-- Edit --}}
                    <a href="{{ route('admin.nav-builder.edit', $menu->id) }}" wire:navigate
                       class="text-xs px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 font-semibold transition-colors">
                        Edit Items
                    </a>

                    {{-- Duplicate --}}
                    <button wire:click="duplicateMenu({{ $menu->id }})"
                            class="text-xs px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 font-medium transition-colors">
                        Duplicate
                    </button>

                    {{-- Delete --}}
                    @unless($menu->is_primary)
                    <button wire:click="deleteMenu({{ $menu->id }})"
                            wire:confirm="Delete menu '{{ $menu->name }}' and all its items? This cannot be undone."
                            class="text-xs px-3 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 font-medium transition-colors">
                        Delete
                    </button>
                    @endunless
                </div>
            </div>
            @endforeach
        </div>

        <p class="mt-4 text-xs text-slate-400 dark:text-slate-500 text-center">
            The menu marked <strong>PRIMARY</strong> is rendered in the public site header. Only one menu can be primary at a time.
        </p>
    @endif
</div>
