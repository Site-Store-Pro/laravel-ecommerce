<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <x-toast-alert />

    {{-- ── DEMO STORE CONTENT BANNER ─────────────────────────────────────────────── --}}
    {{-- Only visible when the database contains is_demo=1 records (DemoStoreSeeder was run) --}}
    @if($this->hasDemoContent)
    <div class="mb-8 rounded-2xl border border-amber-300 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-600/50 overflow-hidden shadow-sm">
        <div class="px-6 py-4 flex items-start gap-4">
            {{-- Icon --}}
            <div class="shrink-0 mt-0.5">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-800/50 text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.347.347a3.5 3.5 0 00-1.025 2.475V19a2 2 0 11-4 0v-.47a3.5 3.5 0 00-1.024-2.476l-.348-.347z"/>
                    </svg>
                </span>
            </div>
            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-bold text-amber-800 dark:text-amber-300">Demo Store Content is Active</h3>
                <p class="mt-1 text-xs text-amber-700 dark:text-amber-400 leading-relaxed">
                    Your store currently contains <strong>demo products, brands, categories, variants, and images</strong> seeded by the
                    <code class="px-1 py-0.5 bg-amber-100 dark:bg-amber-800/60 rounded text-amber-800 dark:text-amber-300 font-mono text-xs">DemoStoreSeeder</code>.
                    When you're ready to go live, use the button below to permanently remove all demo content in one click.
                </p>
                <p class="mt-2 text-xs text-amber-600 dark:text-amber-500">
                    ⚠️ If you have made edits to any demo products, those edits will also be deleted — the system cannot distinguish your modifications from the original demo data.
                </p>
            </div>
            {{-- Action --}}
            <div class="shrink-0">
                <button type="button"
                        wire:click="$set('confirmingDemoPurge', true)"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:bg-red-800 text-white text-xs font-bold transition-all shadow-sm whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Purge Demo Content
                </button>
            </div>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    @if($confirmingDemoPurge)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-data x-init="$el.focus()"
         @keydown.escape.window="$wire.set('confirmingDemoPurge', false)">
        <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden"
             @click.stop>

            {{-- Modal Header --}}
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Confirm Demo Content Deletion</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">This action cannot be undone</p>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5 space-y-4">
                <p class="text-sm text-slate-700 dark:text-slate-300">
                    The following demo-seeded data will be <strong class="text-red-600 dark:text-red-400">permanently deleted</strong> from your database:
                </p>
                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1.5 pl-1">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>All demo products and their metadata</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>All demo product variants and pricing</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>All demo product images (CDN URLs)</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>All demo brands and categories</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>All demo inventory records and events</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>All demo cross-selling relationships</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>All demo product attributes and options</li>
                </ul>
                <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 px-4 py-3">
                    <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">
                        ⚠️ <strong>Important:</strong> If you have edited any demo products, those edits will be deleted too.
                        The system tags records at seed time — post-seed modifications are not tracked separately.
                    </p>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end gap-3">
                <button type="button"
                        wire:click="$set('confirmingDemoPurge', false)"
                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Cancel
                </button>
                <button type="button"
                        wire:click="purgeDemoContent"
                        wire:loading.attr="disabled"
                        wire:target="purgeDemoContent"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 active:bg-red-800 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-bold transition-all shadow-sm">
                    <span wire:loading.remove wire:target="purgeDemoContent">
                        <svg class="w-4 h-4 inline-block -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Yes, Delete All Demo Content
                    </span>
                    <span wire:loading wire:target="purgeDemoContent" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Deleting…
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
    @endif

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Site Settings</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage site identity, appearance, fonts, analytics, and custom script integrations.</p>
    </div>

    <form wire:submit="save" class="space-y-8">

        {{-- ── SITE IDENTITY ── --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center p-2 rounded-lg bg-violet-50 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Site Identity</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Override the site name and set a custom logo image or SVG.</p>
                </div>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700 px-6 py-6 space-y-6">

                {{-- Site Name --}}
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Site Name Override</label>
                    <input type="text" wire:model="site_name"
                           placeholder="{{ config('app.name', 'Support Tickets') }} (leave blank to use APP_NAME from .env)"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-sm font-medium focus:outline-none focus:border-indigo-400">
                    @error('site_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    <p class="text-xs text-slate-400 mt-1.5">Overrides the site name shown in the navigation bar, title tags, and emails. Leave blank to use the APP_NAME .env value.</p>
                </div>

                {{-- Logo Mode Selector --}}
                <div class="pt-4">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-3">Logo Type</label>
                    <div class="flex flex-wrap gap-2 mb-5">
                        @foreach([''=>'Default SVG', 'local'=>'Local Upload', 'url'=>'Direct URL', 'svg'=>'SVG Code', 's3'=>'S3 (App Config)', 'custom_s3'=>'Custom S3', 'cdn'=>'CDN + Path'] as $val=>$label)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="logo_type" value="{{ $val }}" class="sr-only peer">
                                <span class="inline-block px-3 py-1.5 border-2 rounded-xl text-xs font-bold transition-all
                                    {{ $logo_type === $val
                                        ? 'border-indigo-500 bg-indigo-50 text-indigo-700'
                                        : 'border-slate-200 bg-white text-slate-500 hover:border-indigo-300' }}">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Local Upload --}}
                    @if($logo_type === 'local')
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Upload Image File</label>
                            <input type="file" wire:model="logo_upload" accept="image/*"
                                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('logo_upload') <span class="text-xs text-red-500 block">{{ $message }}</span> @enderror
                            @if($logo_path)
                                <p class="text-xs text-emerald-600">✓ Current path: <code>{{ $logo_path }}</code></p>
                            @endif
                        </div>
                    @endif

                    {{-- Direct URL --}}
                    @if($logo_type === 'url')
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Logo URL</label>
                            <input type="text" wire:model="logo_path" placeholder="https://example.com/logo.png"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                            @error('logo_path') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    {{-- SVG Code --}}
                    @if($logo_type === 'svg')
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">SVG HTML Code</label>
                            <textarea wire:model="logo_svg_html" rows="6" placeholder='<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" ...>...</svg>'
                                      class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 rounded-xl text-slate-800 text-sm font-mono focus:outline-none focus:border-indigo-400"></textarea>
                            @error('logo_svg_html') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    {{-- S3 (app config) --}}
                    @if($logo_type === 's3')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">S3 Path / Key</label>
                                <input type="text" wire:model="logo_path" placeholder="logos/my-logo.png"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Optional CDN URL Prefix</label>
                                <input type="text" wire:model="logo_cdn_url" placeholder="https://cdn.example.com"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Uses bucket/region from your app's <code>.env</code> (<code>AWS_BUCKET</code>, <code>AWS_DEFAULT_REGION</code>).</p>
                    @endif

                    {{-- Custom S3 --}}
                    @if($logo_type === 'custom_s3')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Bucket Name</label>
                                <input type="text" wire:model="logo_s3_bucket" placeholder="my-bucket"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Region</label>
                                <input type="text" wire:model="logo_s3_region" placeholder="us-east-1"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">S3 Path / Key</label>
                                <input type="text" wire:model="logo_path" placeholder="logos/my-logo.png"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Optional CDN URL Prefix</label>
                                <input type="text" wire:model="logo_cdn_url" placeholder="https://cdn.example.com"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Note: Access Key and Secret Key for custom S3 are managed via your <code>.env</code> file for security.</p>
                    @endif

                    {{-- CDN + Path --}}
                    @if($logo_type === 'cdn')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">CDN Base URL</label>
                                <input type="text" wire:model="logo_cdn_url" placeholder="https://cdn.example.com"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                                @error('logo_cdn_url') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">File Path</label>
                                <input type="text" wire:model="logo_path" placeholder="logos/logo.png"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                            </div>
                        </div>
                    @endif

                    {{-- Current logo preview --}}
                    @php
                        $currentLogo = \App\Models\CmsSetting::resolveLogoUrl();
                    @endphp
                    @if($currentLogo['type'] === 'url')
                        <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center gap-4">
                            <img src="{{ $currentLogo['value'] }}" alt="Current Logo" class="h-10 w-auto object-contain">
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-600">Current Logo Preview</p>
                                <p class="text-xs text-slate-400 break-all">{{ $currentLogo['value'] }}</p>
                            </div>
                            <button type="button" wire:click="clearLogo" class="px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all">
                                Clear
                            </button>
                        </div>
                    @elseif($currentLogo['type'] === 'svg')
                        <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center gap-4">
                            <div class="h-10 flex items-center">{!! $currentLogo['value'] !!}</div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-600">Current Logo: Custom SVG</p>
                            </div>
                            <button type="button" wire:click="clearLogo" class="px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all">
                                Clear
                            </button>
                        </div>
                    @else
                        <div class="mt-4 p-3 bg-slate-50 border border-dashed border-slate-300 rounded-2xl">
                            <p class="text-xs text-slate-400 text-center">No custom logo — default SVG icon is shown</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- ── APPEARANCE ── --}}

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Appearance</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Control light/dark mode for frontend and admin panels.</p>
                </div>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">

                {{-- Frontend Dark Mode --}}
                <div class="flex items-center justify-between px-6 py-5">
                    <div>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-100">Frontend Dark Mode</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Applies dark theme to all public-facing pages (homepage, shop, CMS pages).</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            wire:model="frontend_dark_mode"
                            id="toggle-frontend-dark"
                            class="sr-only peer"
                        >
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full peer peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 dark:after:border-slate-600 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                {{-- Admin Dark Mode --}}
                <div class="flex items-center justify-between px-6 py-5">
                    <div>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-100">Admin Dark Mode</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Applies dark theme to all admin/dashboard pages. Changes take effect on next page load.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            wire:model="admin_dark_mode"
                            id="toggle-admin-dark"
                            class="sr-only peer"
                        >
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full peer peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 dark:after:border-slate-600 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:bg-indigo-600"></div>
                    </label>
                    @error('admin_dark_mode') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Button Color Picker & Border Radius Theme Customizer --}}
                <div class="px-6 py-6 space-y-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Button Color Scheme &amp; Border Radius</p>
                    <p class="text-xs text-slate-400">Choose custom colors and button shape to reskin the site elements to match your brand identity.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Primary Background Color --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Primary Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model="theme_primary_color"
                                       class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                <input type="text" wire:model="theme_primary_color"
                                       placeholder="#4f46e5"
                                       class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                            </div>
                            @error('theme_primary_color') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Hover Background Color --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Hover Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model="theme_hover_color"
                                       class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                <input type="text" wire:model="theme_hover_color"
                                       placeholder="#4338ca"
                                       class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                            </div>
                            @error('theme_hover_color') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Button Text Color --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Text Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model="theme_text_color"
                                       class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                <input type="text" wire:model="theme_text_color"
                                       placeholder="#ffffff"
                                       class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                            </div>
                            @error('theme_text_color') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Button Border Radius --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Button Shape</label>
                            <select wire:model="theme_border_radius"
                                    class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-medium focus:outline-none focus:border-indigo-400">
                                <option value="0px">Sharp (0px)</option>
                                <option value="0.25rem">Rounded SM (4px)</option>
                                <option value="0.375rem">Rounded MD (6px)</option>
                                <option value="0.5rem">Rounded LG (8px)</option>
                                <option value="0.75rem">Rounded XL (12px)</option>
                                <option value="1rem">Rounded 2XL (16px)</option>
                                <option value="1.5rem">Rounded 3XL (24px)</option>
                                <option value="9999px">Pill / Full (9999px)</option>
                            </select>
                            @error('theme_border_radius') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── LOADERS & INTEGRATIONS ── --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center p-2 rounded-lg bg-violet-50 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Loaders &amp; Integrations</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Google Fonts, Analytics tracking, and sitewide third-party scripts.</p>
                </div>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">

                {{-- Google Fonts URL / HTML --}}
                <div class="px-6 py-5 space-y-2">
                    <label for="google_fonts_url" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Google Fonts Stylesheet Link(s)
                    </label>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Paste complete `<link>` tags (preconnect &amp; stylesheet links) from <a href="https://fonts.google.com" target="_blank" class="text-indigo-500 hover:underline">fonts.google.com</a>, or a full stylesheet URL.
                    </p>
                    <textarea
                        id="google_fonts_url"
                        wire:model="google_fonts_url"
                        rows="4"
                        placeholder="&lt;link rel=&quot;preconnect&quot; href=&quot;https://fonts.googleapis.com&quot;&gt;&#10;&lt;link rel=&quot;preconnect&quot; href=&quot;https://fonts.gstatic.com&quot; crossorigin&gt;&#10;&lt;link href=&quot;https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap&quot; rel=&quot;stylesheet&quot;&gt;"
                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm font-mono placeholder:text-slate-400 dark:placeholder:text-slate-500 transition resize-y"
                    ></textarea>
                    @error('google_fonts_url')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Google Analytics ID --}}
                <div class="px-6 py-5 space-y-2">
                    <label for="google_analytics_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Google Analytics Measurement ID
                    </label>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Your GA4 Measurement ID (format: <code class="bg-slate-100 dark:bg-slate-700 px-1 rounded">G-XXXXXXXXXX</code>).
                        The full gtag.js snippet is injected automatically. Leave blank to disable.
                    </p>
                    <input
                        type="text"
                        id="google_analytics_id"
                        wire:model="google_analytics_id"
                        placeholder="G-XXXXXXXXXX"
                        maxlength="50"
                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm placeholder:text-slate-400 dark:placeholder:text-slate-500 transition"
                    >
                    @error('google_analytics_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Custom JS Loader --}}
                <div class="px-6 py-5 space-y-2">
                    <label for="custom_js_loader" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Custom JS / Third-Party Scripts
                    </label>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Paste complete <code class="bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;script&gt;</code> tags or external script loader snippets.
                        These are injected sitewide just before <code class="bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;/body&gt;</code> on all public pages.
                    </p>
                    <textarea
                        id="custom_js_loader"
                        wire:model="custom_js_loader"
                        rows="6"
                        placeholder="<!-- e.g. Intercom, HubSpot, Hotjar, custom analytics -->"
                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm font-mono placeholder:text-slate-400 dark:placeholder:text-slate-500 transition resize-y"
                    ></textarea>
                    @error('custom_js_loader')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ── PRODUCT REVIEWS ── --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Product Reviews & Ratings</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Enable site-wide user comments and ratings on items, or embed a third-party review script.</p>
                </div>
            </div>
            <div class="px-6 py-5 space-y-6">
                {{-- Global Toggle --}}
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Enable Product Reviews</label>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Allow users to leave ratings and comments on products globally.</span>
                    </div>
                    <button type="button" 
                            wire:click="$toggle('enable_reviews')" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $enable_reviews ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700' }}"
                            role="switch">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $enable_reviews ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </div>

                {{-- Third Party Review Script --}}
                <div class="space-y-2">
                    <label for="third_party_reviews_js" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Third-Party Review Embed Snippet (JavaScript)
                    </label>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Paste third-party review widgets (e.g., Trustpilot, Yotpo, or Google Customer Reviews code). 
                        <strong>Including any code here automatically disables the Laravel native reviews form.</strong>
                    </p>
                    <textarea
                        id="third_party_reviews_js"
                        wire:model="third_party_reviews_js"
                        rows="4"
                        placeholder="&lt;script src=&quot;https://example.com/widget.js&quot;&gt;&lt;/script&gt;"
                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition font-mono"
                    ></textarea>
                    @error('third_party_reviews_js')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ── GENERAL / TIMEZONE ── --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">General</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Site-wide timezone that controls all displayed dates and times.</p>
                </div>
            </div>
            <div class="px-6 py-5 space-y-2">
                <label for="timezone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Site Timezone
                </label>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    All order dates, timestamps, and scheduled jobs will display in this timezone.
                    Changes take effect immediately after saving.
                </p>
                <select
                    id="timezone"
                    wire:model="timezone"
                    class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
                >
                    @foreach($timezoneGroups as $groupLabel => $zones)
                        <optgroup label="{{ $groupLabel }}">
                            @foreach($zones as $tzId => $tzName)
                                <option value="{{ $tzId }}" @selected($timezone === $tzId)>{{ $tzName }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('timezone')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- ── CMS DOWNLOADS ── --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center p-2 rounded-lg bg-teal-50 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        CMS Downloads
                        <a href="{{ route('admin.cms-downloads.index') }}" wire:navigate
                           class="text-teal-500 hover:text-teal-700 dark:hover:text-teal-300 transition-colors"
                           title="Go to Downloads Manager">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        File type icon style used by the
                        <a href="{{ route('admin.cms-downloads.index') }}" wire:navigate class="text-teal-600 dark:text-teal-400 hover:underline font-medium">Downloads Manager</a>
                        when a download link shortcode has an icon position set. The selected pack applies globally to all CMS download links on both admin and public pages.
                    </p>
                </div>
            </div>
            <div class="px-6 py-6">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-4">File Icon Pack</label>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach([
                        ['vivid',   'Vivid',   'file-icon-vivid',    'Bold, colourful filled icons. Best for light backgrounds.'],
                        ['classic', 'Classic', 'file-icon-classic',  'Clean monochrome style. Timeless and professional.'],
                        ['square',  'Square',  'file-icon-square-o', 'Square outline style. Modern and minimal.'],
                    ] as [$val, $name, $cssClass, $desc])
                        <label class="cursor-pointer group">
                            <input type="radio" wire:model.live="file_icon_pack" value="{{ $val }}" class="sr-only peer">
                            <div class="border-2 rounded-2xl p-5 transition-all
                                peer-checked:border-teal-500 peer-checked:bg-teal-50 dark:peer-checked:bg-teal-950/30
                                border-slate-200 dark:border-slate-600
                                hover:border-teal-300 dark:hover:border-teal-700">

                                {{-- Pack name + selected indicator --}}
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-teal-700 dark:group-hover:text-teal-400 transition-colors peer-checked:text-teal-700">
                                        {{ $name }}
                                    </span>
                                    <span class="hidden peer-checked:flex items-center justify-center w-5 h-5 rounded-full bg-teal-500">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    @if($file_icon_pack === $val)
                                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-teal-500">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                    @endif
                                </div>

                                {{-- Live icon previews using each pack's own CSS class --}}
                                <div class="flex items-center gap-2 mb-3 flex-wrap">
                                    @foreach(['pdf','docx','mp4','zip','mp3','jpg'] as $previewExt)
                                        <span class="fiv-cla {{ $cssClass }} fiv-icon-{{ $previewExt }}" style="font-size:1.8em; line-height:1;"></span>
                                    @endforeach
                                </div>

                                <p class="text-xs text-slate-400 dark:text-slate-500 leading-relaxed">{{ $desc }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>

                <p class="text-xs text-slate-400 dark:text-slate-500 mt-4 flex items-start gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0 mt-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    All three packs use the
                    <a href="https://github.com/dmhendricks/file-icon-vectors" target="_blank" rel="noopener" class="text-teal-500 hover:underline">file-icon-vectors</a>
                    library at v1.0.0 via jsDelivr CDN — no additional installation needed.
                    The icon is only shown when a download's icon position is set to anything other than "None" in the Downloads Manager.
                </p>
            </div>
        </div>

        {{-- Shop Display --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Shop Display</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Configure how product images are displayed across the storefront and plugins.</p>
                </div>
            </div>
            <div class="p-6 space-y-5">

                {{-- Image Orientation --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Product Image Orientation</label>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mb-3">
                        Select the aspect ratio that matches your product source images. This affects the shop listing pages, product detail gallery, and all display plugins.
                        Choose <strong>16:9 Widescreen</strong> for landscape/banner-style images, or <strong>1:1 Square</strong> for square product photos.
                        Images will be displayed without cropping when the correct orientation is selected.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                        {{-- 16:9 option --}}
                        <label for="orientation-16-9"
                               class="relative flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-150
                                      {{ $product_image_orientation === '16:9'
                                            ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/20 shadow-sm'
                                            : 'border-slate-200 dark:border-slate-600 hover:border-violet-300 dark:hover:border-violet-500 bg-white dark:bg-slate-700/40' }}">
                            <input type="radio" id="orientation-16-9" wire:model="product_image_orientation"
                                   value="16:9" class="sr-only">
                            {{-- 16:9 icon preview --}}
                            <span class="shrink-0 mt-0.5 flex flex-col items-center justify-center w-14 h-9 rounded-lg border-2
                                         {{ $product_image_orientation === '16:9' ? 'border-violet-400 bg-violet-100 dark:bg-violet-800/40' : 'border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700' }}">
                                <svg class="w-5 h-3 {{ $product_image_orientation === '16:9' ? 'text-violet-500' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 14">
                                    <rect x="1" y="1" width="22" height="12" rx="2" stroke-width="2"/>
                                    <circle cx="12" cy="7" r="3" stroke-width="1.5"/>
                                </svg>
                            </span>
                            <div class="flex-1">
                                <p class="text-sm font-bold {{ $product_image_orientation === '16:9' ? 'text-violet-700 dark:text-violet-300' : 'text-slate-700 dark:text-slate-200' }}">
                                    16:9 Widescreen
                                    @if($product_image_orientation === '16:9')
                                        <span class="ml-1.5 text-[10px] font-semibold text-violet-500 bg-violet-100 dark:bg-violet-900/50 px-1.5 py-0.5 rounded-full">Active</span>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 leading-relaxed">Landscape/banner images. Container fills a 16:9 frame — images are cover-cropped to fill width.</p>
                            </div>
                            @if($product_image_orientation === '16:9')
                                <span class="absolute top-3 right-3 inline-flex items-center justify-center w-5 h-5 rounded-full bg-violet-500 text-white shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            @endif
                        </label>

                        {{-- 1:1 option --}}
                        <label for="orientation-1-1"
                               class="relative flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-150
                                      {{ $product_image_orientation === '1:1'
                                            ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/20 shadow-sm'
                                            : 'border-slate-200 dark:border-slate-600 hover:border-violet-300 dark:hover:border-violet-500 bg-white dark:bg-slate-700/40' }}">
                            <input type="radio" id="orientation-1-1" wire:model="product_image_orientation"
                                   value="1:1" class="sr-only">
                            {{-- 1:1 icon preview --}}
                            <span class="shrink-0 mt-0.5 flex flex-col items-center justify-center w-10 h-10 rounded-lg border-2
                                         {{ $product_image_orientation === '1:1' ? 'border-violet-400 bg-violet-100 dark:bg-violet-800/40' : 'border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700' }}">
                                <svg class="w-5 h-5 {{ $product_image_orientation === '1:1' ? 'text-violet-500' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="2" y="2" width="20" height="20" rx="2" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="4" stroke-width="1.5"/>
                                </svg>
                            </span>
                            <div class="flex-1">
                                <p class="text-sm font-bold {{ $product_image_orientation === '1:1' ? 'text-violet-700 dark:text-violet-300' : 'text-slate-700 dark:text-slate-200' }}">
                                    1:1 Square
                                    @if($product_image_orientation === '1:1')
                                        <span class="ml-1.5 text-[10px] font-semibold text-violet-500 bg-violet-100 dark:bg-violet-900/50 px-1.5 py-0.5 rounded-full">Active</span>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 leading-relaxed">Square product photos. Container is a perfect square — images are contained without cropping.</p>
                            </div>
                            @if($product_image_orientation === '1:1')
                                <span class="absolute top-3 right-3 inline-flex items-center justify-center w-5 h-5 rounded-full bg-violet-500 text-white shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            @endif
                        </label>

                    </div>
                </div>

            </div>
        </div>

        {{-- Save Button --}}


        <div class="flex items-center justify-end gap-4 pt-2">
            <div wire:loading wire:target="save" class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Saving…
            </div>
            <button
                type="submit"
                id="save-settings-btn"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-100 hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Settings
            </button>
        </div>

    </form>

</div>

