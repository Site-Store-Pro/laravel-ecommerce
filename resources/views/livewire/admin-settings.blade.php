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
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Global Settings</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage global site identity, theme customization, full-page background media, typography, appearance, and integrations.</p>
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
                        <div class="space-y-3">
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
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Direct S3 Bucket File Upload</label>
                                <input type="file" wire:model="logo_upload" accept="image/*"
                                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="text-xs text-slate-400 mt-1">Uploads file directly into your S3 bucket configured in <code>.env</code>.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Custom S3 --}}
                    @if($logo_type === 'custom_s3')
                        <div class="space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Bucket Name</label>
                                    <input type="text" wire:model.live="logo_s3_bucket" placeholder="my-bucket"
                                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Region</label>
                                    <input type="text" wire:model.live="logo_s3_region" placeholder="us-east-1"
                                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">AWS Access Key ID</label>
                                    <input type="text" wire:model.live="logo_s3_key" placeholder="AKIA..."
                                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium focus:outline-none focus:border-indigo-400">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">AWS Secret Access Key</label>
                                    <input type="password" wire:model.live="logo_s3_secret" placeholder="Secret Key"
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
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Direct Custom S3 Bucket File Upload</label>
                                <input type="file" wire:model="logo_upload" accept="image/*"
                                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="text-xs text-slate-400 mt-1">Uploads file directly into your specified custom S3 bucket using these credentials.</p>
                            </div>
                        </div>
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

        {{-- ── SITE THEME CUSTOMIZATION ── --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <span class="inline-flex items-center justify-center p-2 rounded-lg bg-pink-50 dark:bg-pink-900/40 text-pink-600 dark:text-pink-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Site Theme Customization</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Configure full-page background color/image/video, independent element font families &amp; sizes, and content card borders/shading for public pages.</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-700 px-6 py-6 space-y-8">

                {{-- STICKY HEADER NAVIGATION TOGGLE --}}
                <div class="p-4 bg-indigo-50/60 dark:bg-indigo-950/40 rounded-2xl border border-indigo-100 dark:border-indigo-800 flex items-center justify-between gap-4">
                    <div>
                        <h4 class="text-xs font-bold text-indigo-900 dark:text-indigo-200 uppercase tracking-wider">Sticky Header Navigation</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">When enabled, the primary site header and top navigation bar remain fixed at the top of the browser window as visitors scroll down.</p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer shrink-0">
                        <input type="checkbox" wire:model="top_nav_sticky" class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300">Sticky Header Active</span>
                    </label>
                </div>

                {{-- 1. FULL PAGE BACKGROUND MEDIA --}}
                <div class="space-y-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">1. Full Page Background Media Customization</p>
                    <p class="text-xs text-slate-400">Choose how the full-page public background renders. Setting a custom background color, image, or video will override default page backgrounds.</p>

                    {{-- Background Mode Radio Switcher --}}
                    <div class="flex flex-wrap gap-2">
                        @foreach(['default'=>'Default Theme', 'color'=>'Custom Background Color', 'image'=>'Full Page Background Image', 'video'=>'Full Page Background Video'] as $modeVal => $modeLabel)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="page_bg_mode" value="{{ $modeVal }}" class="sr-only peer">
                                <span class="inline-block px-3.5 py-2 border-2 rounded-xl text-xs font-bold transition-all
                                    {{ $page_bg_mode === $modeVal
                                        ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300'
                                        : 'border-slate-200 bg-white dark:bg-slate-800 text-slate-500 hover:border-indigo-300' }}">
                                    {{ $modeLabel }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Custom Background Color --}}
                    @if($page_bg_mode === 'color')
                        <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-2xl border border-slate-200 dark:border-slate-600">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Page Background Color Picker</label>
                            <div class="flex items-center gap-3">
                                <input type="color" wire:model.live="page_bg_color" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                <input type="text" wire:model.live="page_bg_color" placeholder="#f8fafc" class="max-w-xs px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl text-xs font-mono uppercase focus:outline-none">
                            </div>
                        </div>
                    @endif

                    {{-- Full Page Background Image Options --}}
                    @if($page_bg_mode === 'image')
                        <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-2xl border border-slate-200 dark:border-slate-600 space-y-4">
                            {{-- Direct URL Override --}}
                            <div class="p-3 bg-violet-50/50 dark:bg-violet-950/30 rounded-xl border border-violet-200 dark:border-violet-800 space-y-1.5">
                                <label class="block text-[11px] font-bold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Direct Image URL Override (Highest Priority)</label>
                                <input type="text" wire:model="page_bg_image_url" placeholder="https://cdn.example.com/background.jpg" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-violet-300 dark:border-violet-700 rounded-xl text-xs font-medium focus:outline-none focus:border-violet-500">
                                <p class="text-[11px] text-violet-600 dark:text-violet-400">Entering a direct URL here overrides all other file upload sources below.</p>
                            </div>

                            <p class="text-xs font-bold text-slate-600 dark:text-slate-300">File Storage &amp; Upload Source</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['local'=>'Local Upload', 's3'=>'S3 (.env Config)', 'custom_s3'=>'Custom S3 Bucket'] as $imgTypeVal => $imgTypeLabel)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="page_bg_image_type" value="{{ $imgTypeVal }}" class="sr-only peer">
                                        <span class="inline-block px-3 py-1.5 border rounded-xl text-xs font-bold transition-all {{ $page_bg_image_type === $imgTypeVal ? 'border-indigo-500 bg-indigo-100 text-indigo-800' : 'border-slate-200 bg-white text-slate-600' }}">
                                            {{ $imgTypeLabel }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="space-y-3">
                                @if($page_bg_image_type === 'custom_s3')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Bucket Name</label><input type="text" wire:model.live="page_bg_image_s3_bucket" placeholder="Bucket Name" class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Region (us-east-1)</label><input type="text" wire:model.live="page_bg_image_s3_region" placeholder="Region (us-east-1)" class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">AWS Access Key ID</label><input type="text" wire:model.live="page_bg_image_s3_key" placeholder="AKIA..." class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">AWS Secret Access Key</label><input type="password" wire:model.live="page_bg_image_s3_secret" placeholder="Secret Key" class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                    </div>
                                @endif

                                @if($page_bg_image_type === 's3' || $page_bg_image_type === 'custom_s3')
                                    <div><input type="text" wire:model.live="page_bg_image_path" placeholder="Image S3 Key/Path (backgrounds/hero.jpg)" class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                @endif

                                <div>
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Upload File (Local or S3 Bucket)</label>
                                    <input type="file" wire:model="page_bg_image_upload" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700">
                                    @if($page_bg_image_path)
                                        <p class="text-xs text-emerald-600 mt-1">✓ Active path: <code>{{ $page_bg_image_path }}</code></p>
                                    @endif
                                </div>
                            </div>

                            @php $resolvedBgImg = \App\Models\CmsSetting::resolvePageBgImageUrl(); @endphp
                            @if($resolvedBgImg)
                                <div class="mt-2 p-3 bg-white dark:bg-slate-800 rounded-xl border flex items-center gap-3">
                                    <img src="{{ $resolvedBgImg }}" alt="Preview" class="h-12 w-20 object-cover rounded-lg">
                                    <span class="text-xs text-slate-500 break-all flex-1">{{ $resolvedBgImg }}</span>
                                    <button type="button" wire:click="clearBgImage" class="px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all">
                                        Clear
                                    </button>
                                </div>
                            @else
                                <div class="pt-2 flex justify-end">
                                    <button type="button" wire:click="clearBgImage" class="px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Reset / Clear Background Image
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Full Page Background Video Options --}}
                    @if($page_bg_mode === 'video')
                        <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-2xl border border-slate-200 dark:border-slate-600 space-y-4">
                            {{-- Direct URL Override --}}
                            <div class="p-3 bg-violet-50/50 dark:bg-violet-950/30 rounded-xl border border-violet-200 dark:border-violet-800 space-y-1.5">
                                <label class="block text-[11px] font-bold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Direct Video URL Override (Highest Priority)</label>
                                <input type="text" wire:model="page_bg_video_url" placeholder="https://cdn.example.com/background.mp4" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-violet-300 dark:border-violet-700 rounded-xl text-xs font-medium focus:outline-none focus:border-violet-500">
                                <p class="text-[11px] text-violet-600 dark:text-violet-400">Entering a direct URL here overrides all other video upload sources below.</p>
                            </div>

                            <p class="text-xs font-bold text-slate-600 dark:text-slate-300">File Storage &amp; Upload Source (MP4 / WebM)</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['local'=>'Local Upload', 's3'=>'S3 (.env Config)', 'custom_s3'=>'Custom S3 Bucket'] as $vidTypeVal => $vidTypeLabel)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="page_bg_video_type" value="{{ $vidTypeVal }}" class="sr-only peer">
                                        <span class="inline-block px-3 py-1.5 border rounded-xl text-xs font-bold transition-all {{ $page_bg_video_type === $vidTypeVal ? 'border-indigo-500 bg-indigo-100 text-indigo-800' : 'border-slate-200 bg-white text-slate-600' }}">
                                            {{ $vidTypeLabel }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="space-y-3">
                                @if($page_bg_video_type === 'custom_s3')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Bucket Name</label><input type="text" wire:model.live="page_bg_video_s3_bucket" placeholder="Bucket Name" class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Region (us-east-1)</label><input type="text" wire:model.live="page_bg_video_s3_region" placeholder="Region (us-east-1)" class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">AWS Access Key ID</label><input type="text" wire:model.live="page_bg_video_s3_key" placeholder="AKIA..." class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                        <div><label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">AWS Secret Access Key</label><input type="password" wire:model.live="page_bg_video_s3_secret" placeholder="Secret Key" class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                    </div>
                                @endif

                                @if($page_bg_video_type === 's3' || $page_bg_video_type === 'custom_s3')
                                    <div><input type="text" wire:model.live="page_bg_video_path" placeholder="Video S3 Key/Path (backgrounds/hero.mp4)" class="w-full px-3 py-2 bg-white border rounded-xl text-xs"></div>
                                @endif

                                <div>
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Upload Video File (Local or S3 Bucket)</label>
                                    <input type="file" wire:model="page_bg_video_upload" accept="video/mp4,video/webm" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700">
                                    @if($page_bg_video_path)
                                        <p class="text-xs text-emerald-600 mt-1">✓ Active path: <code>{{ $page_bg_video_path }}</code></p>
                                    @endif
                                </div>
                            </div>

                            @php $resolvedBgVid = \App\Models\CmsSetting::resolvePageBgVideoUrl(); @endphp
                            @if($resolvedBgVid)
                                <div class="mt-2 p-3 bg-white dark:bg-slate-800 rounded-xl border flex items-center gap-3">
                                    <video src="{{ $resolvedBgVid }}" class="h-14 w-24 object-cover rounded-lg" autoplay loop muted playsinline></video>
                                    <span class="text-xs text-slate-500 break-all flex-1">{{ $resolvedBgVid }}</span>
                                    <button type="button" wire:click="clearBgVideo" class="px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all">
                                        Clear
                                    </button>
                                </div>
                            @else
                                <div class="pt-2 flex justify-end">
                                    <button type="button" wire:click="clearBgVideo" class="px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Reset / Clear Background Video
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Background Overlay Tint --}}
                    @if($page_bg_mode === 'image' || $page_bg_mode === 'video')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Background Overlay Tint Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="page_bg_overlay_color" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="page_bg_overlay_color" placeholder="#000000" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 rounded-xl text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Overlay Opacity (0.0 to 0.9)</label>
                                <input type="number" step="0.05" min="0" max="0.95" wire:model.live="page_bg_overlay_opacity" placeholder="0.2" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 rounded-xl text-xs font-mono focus:outline-none">
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 2. SITE TYPOGRAPHY & FONT PROPERTIES --}}
                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">2. Site Typography &amp; Font Customization</p>
                    <p class="text-xs text-slate-400">Configure independent font families, font sizes, and text colors for Body, Paragraphs, H1, H2, and H3.</p>

                    {{-- Font Sizes & Colors Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">
                        {{-- Body --}}
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200 dark:border-slate-600 space-y-2.5">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 border-b pb-1">Body Text</p>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Family</label>
                                <input type="text" wire:model="theme_body_font_family" placeholder="'Plus Jakarta Sans', sans-serif" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Size</label>
                                <input type="text" wire:model="theme_body_font_size" placeholder="1rem / 16px" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Text Color</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="color" wire:model.live="theme_body_font_color" class="w-7 h-7 border rounded-lg cursor-pointer p-0 bg-transparent">
                                    <input type="text" wire:model.live="theme_body_font_color" placeholder="#334155" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border rounded-lg text-xs font-mono">
                                </div>
                            </div>
                        </div>

                        {{-- Paragraph --}}
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200 dark:border-slate-600 space-y-2.5">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 border-b pb-1">Paragraph Text (&lt;p&gt;)</p>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Family</label>
                                <input type="text" wire:model="theme_paragraph_font_family" placeholder="inherit" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Size</label>
                                <input type="text" wire:model="theme_paragraph_font_size" placeholder="0.875rem / 14px" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Text Color</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="color" wire:model.live="theme_paragraph_font_color" class="w-7 h-7 border rounded-lg cursor-pointer p-0 bg-transparent">
                                    <input type="text" wire:model.live="theme_paragraph_font_color" placeholder="#475569" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border rounded-lg text-xs font-mono">
                                </div>
                            </div>
                        </div>

                        {{-- Heading H1 --}}
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200 dark:border-slate-600 space-y-2.5">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 border-b pb-1">Heading 1 (&lt;h1&gt;)</p>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Family</label>
                                <input type="text" wire:model="theme_h1_font_family" placeholder="'Outfit', sans-serif" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Size</label>
                                <input type="text" wire:model="theme_h1_font_size" placeholder="2.25rem / 36px" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">H1 Color</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="color" wire:model.live="theme_h1_font_color" class="w-7 h-7 border rounded-lg cursor-pointer p-0 bg-transparent">
                                    <input type="text" wire:model.live="theme_h1_font_color" placeholder="#0f172a" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border rounded-lg text-xs font-mono">
                                </div>
                            </div>
                        </div>

                        {{-- Heading H2 --}}
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200 dark:border-slate-600 space-y-2.5">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 border-b pb-1">Heading 2 (&lt;h2&gt;)</p>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Family</label>
                                <input type="text" wire:model="theme_h2_font_family" placeholder="'Outfit', sans-serif" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Size</label>
                                <input type="text" wire:model="theme_h2_font_size" placeholder="1.75rem / 28px" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">H2 Color</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="color" wire:model.live="theme_h2_font_color" class="w-7 h-7 border rounded-lg cursor-pointer p-0 bg-transparent">
                                    <input type="text" wire:model.live="theme_h2_font_color" placeholder="#0f172a" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border rounded-lg text-xs font-mono">
                                </div>
                            </div>
                        </div>

                        {{-- Heading H3 --}}
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200 dark:border-slate-600 space-y-2.5">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 border-b pb-1">Heading 3 (&lt;h3&gt;)</p>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Family</label>
                                <input type="text" wire:model="theme_h3_font_family" placeholder="'Outfit', sans-serif" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">Font Size</label>
                                <input type="text" wire:model="theme_h3_font_size" placeholder="1.25rem / 20px" class="w-full px-2 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 uppercase mb-1">H3 Color</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="color" wire:model.live="theme_h3_font_color" class="w-7 h-7 border rounded-lg cursor-pointer p-0 bg-transparent">
                                    <input type="text" wire:model.live="theme_h3_font_color" placeholder="#1e293b" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border rounded-lg text-xs font-mono">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. CONTENT AREA & CARD STYLING --}}
                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">3. Content Area &amp; Card Border/Shading Customization</p>
                    <p class="text-xs text-slate-400">Configure content area background colors and content card borders/box-shadows for public pages.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Content Area BG</label>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model.live="theme_content_bg_color" class="w-9 h-9 border rounded-xl cursor-pointer p-0 bg-transparent">
                                <input type="text" wire:model.live="theme_content_bg_color" placeholder="#ffffff" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border rounded-xl text-xs font-mono uppercase focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Content Card BG</label>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model.live="theme_card_bg_color" class="w-9 h-9 border rounded-xl cursor-pointer p-0 bg-transparent">
                                <input type="text" wire:model.live="theme_card_bg_color" placeholder="#ffffff" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border rounded-xl text-xs font-mono uppercase focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Card Border Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model.live="theme_card_border_color" class="w-9 h-9 border rounded-xl cursor-pointer p-0 bg-transparent">
                                <input type="text" wire:model.live="theme_card_border_color" placeholder="#e2e8f0" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border rounded-xl text-xs font-mono uppercase focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Card Shading (Box Shadow)</label>
                            <select wire:model="theme_card_shadow" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none">
                                <option value="">Default Shadow</option>
                                <option value="none">No Shadow (Flat)</option>
                                <option value="sm">Small Shadow (sm)</option>
                                <option value="md">Medium Shadow (md)</option>
                                <option value="lg">Large Shadow (lg)</option>
                                <option value="xl">Extra Large Shadow (xl)</option>
                                <option value="2xl">Deep Shadow (2xl)</option>
                            </select>
                        </div>
                    </div>
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
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Secondary Button Styling Manager</p>
                                <p class="text-xs text-slate-400">Configure secondary buttons (e.g. catalog list/grid display view toggles). Defaults to transparent background with text color matching the primary button.</p>
                            </div>
                            <button type="button"
                                    class="btn-secondary !text-xs"
                                    style="background-color: {{ $theme_secondary_bg_color }}; color: {{ $theme_secondary_text_color }}; border-color: {{ $theme_secondary_border_color }};">
                                Preview Secondary Button
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            {{-- Secondary Text Color --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Text Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model="theme_secondary_text_color"
                                           class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model="theme_secondary_text_color"
                                           placeholder="#4f46e5"
                                           class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>

                            {{-- Secondary Background Color --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Background</label>
                                <input type="text" wire:model="theme_secondary_bg_color"
                                       placeholder="transparent"
                                       class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono focus:outline-none">
                            </div>

                            {{-- Secondary Border Color --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Border Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model="theme_secondary_border_color"
                                           class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model="theme_secondary_border_color"
                                           placeholder="#4f46e5"
                                           class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>

                            {{-- Secondary Hover BG Color --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Hover Background</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model="theme_secondary_hover_bg_color"
                                           class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model="theme_secondary_hover_bg_color"
                                           placeholder="#4f46e5"
                                           class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>

                            {{-- Secondary Hover Text Color --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Hover Text Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model="theme_secondary_hover_text_color"
                                           class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model="theme_secondary_hover_text_color"
                                           placeholder="#ffffff"
                                           class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Go to Top Button Styling Manager --}}
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Go to Top Button Colors</p>
                                <p class="text-xs text-slate-400">Configure background, hover, and icon colors for the floating return-to-top button on all pages.</p>
                            </div>
                            <div class="shrink-0 flex items-center gap-2">
                                <button type="button" 
                                        class="px-4 py-2 text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-1.5"
                                        style="background-color: {{ !empty($backtop_bg_color) ? $backtop_bg_color : 'var(--primary-accent-color, #4f46e5)' }}; color: {{ $backtop_icon_color }};">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                                    <span>Preview Button</span>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Go to Top Background Color --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Background Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="backtop_bg_color"
                                           class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="backtop_bg_color"
                                           placeholder="#4f46e5 (theme default)"
                                           class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>

                            {{-- Go to Top Hover Background Color --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Hover Background Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="backtop_hover_bg_color"
                                           class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="backtop_hover_bg_color"
                                           placeholder="#4338ca (theme default)"
                                           class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>

                            {{-- Go to Top Icon Color --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Icon Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="backtop_icon_color"
                                           class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="backtop_icon_color"
                                           placeholder="#ffffff"
                                           class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shop Catalog Grid & List View Toggle Button Colors --}}
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Shop View Mode Toggle Colors (Grid / List Icons)</p>
                                <p class="text-xs text-slate-400">Configure active and inactive state colors for the search results grid & list view toggle buttons on /shop.</p>
                            </div>
                            <div class="shrink-0 flex items-center gap-1.5">
                                <button type="button" class="p-2 rounded-xl text-xs font-bold transition" style="background-color: {{ $shop_view_mode_active_bg }}; color: {{ $shop_view_mode_active_text }};">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                </button>
                                <button type="button" class="p-2 rounded-xl text-xs font-bold transition" style="background-color: {{ $shop_view_mode_inactive_bg }}; color: {{ $shop_view_mode_inactive_text }};">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Active Background</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="shop_view_mode_active_bg" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="shop_view_mode_active_bg" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Active Icon Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="shop_view_mode_active_text" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="shop_view_mode_active_text" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Inactive Background</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="shop_view_mode_inactive_bg" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="shop_view_mode_inactive_bg" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Inactive Icon Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="shop_view_mode_inactive_text" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="shop_view_mode_inactive_text" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Category, Brand & Subcategory Filter Pills Colors --}}
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Category, Brand &amp; Subcategory Filter Pill Colors (/shop)</p>
                                <p class="text-xs text-slate-400">Configure normal and hover background, text/font, and border colors for Category, Brand, and Subcategory filter pills on /shop.</p>
                            </div>
                            <div class="shrink-0 flex items-center gap-2">
                                <span class="px-3 py-1 text-xs font-bold rounded-xl border transition" style="background-color: {{ $shop_category_pill_bg }}; color: {{ $shop_category_pill_text }}; border-color: {{ $shop_category_pill_border }};">Category</span>
                                <span class="px-3 py-1 text-xs font-bold rounded-xl border transition" style="background-color: {{ $shop_brand_pill_bg }}; color: {{ $shop_brand_pill_text }}; border-color: {{ $shop_brand_pill_border }};">Brand</span>
                                <span class="px-3 py-1 text-xs font-bold rounded-xl border transition" style="background-color: {{ $shop_subcat_pill_bg }}; color: {{ $shop_subcat_pill_text }}; border-color: {{ $shop_subcat_pill_border }};">Subcategory</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Category Pills --}}
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/40 rounded-2xl border border-slate-200 dark:border-slate-600 space-y-3">
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 border-b pb-1">Category Filter Pills</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Background</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_category_pill_bg" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_category_pill_bg" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Text Color</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_category_pill_text" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_category_pill_text" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Border Color</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_category_pill_border" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_category_pill_border" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hover BG</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_category_pill_hover_bg" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_category_pill_hover_bg" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hover Text</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_category_pill_hover_text" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_category_pill_hover_text" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hover Border</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_category_pill_hover_border" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_category_pill_hover_border" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Brand Pills --}}
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/40 rounded-2xl border border-slate-200 dark:border-slate-600 space-y-3">
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 border-b pb-1">Brand Filter Pills</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Background</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_brand_pill_bg" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_brand_pill_bg" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Text Color</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_brand_pill_text" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_brand_pill_text" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Border Color</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_brand_pill_border" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_brand_pill_border" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hover BG</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_brand_pill_hover_bg" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_brand_pill_hover_bg" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hover Text</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_brand_pill_hover_text" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_brand_pill_hover_text" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hover Border</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_brand_pill_hover_border" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_brand_pill_hover_border" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Subcategory Pills --}}
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/40 rounded-2xl border border-slate-200 dark:border-slate-600 space-y-3">
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 border-b pb-1">Subcategory Filter Pills</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Background</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_subcat_pill_bg" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_subcat_pill_bg" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Text Color</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_subcat_pill_text" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_subcat_pill_text" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Border Color</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_subcat_pill_border" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_subcat_pill_border" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hover BG</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_subcat_pill_hover_bg" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_subcat_pill_hover_bg" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hover Text</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_subcat_pill_hover_text" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_subcat_pill_hover_text" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hover Border</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" wire:model.live="shop_subcat_pill_hover_border" class="w-7 h-7 border border-slate-200 rounded-lg cursor-pointer bg-transparent p-0">
                                            <input type="text" wire:model.live="shop_subcat_pill_hover_border" class="flex-1 px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sitewide Pagination Control Colors --}}
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sitewide Pagination Button Box Colors</p>
                                <p class="text-xs text-slate-400">Configure background, number, and hover colors for pagination controls across all catalog & list pages.</p>
                            </div>
                            <div class="shrink-0 flex items-center gap-1">
                                <span class="w-7 h-7 flex items-center justify-center text-xs font-bold rounded-lg shadow-sm" style="background-color: {{ $pagination_active_bg }}; color: {{ $pagination_active_text }};">1</span>
                                <span class="w-7 h-7 flex items-center justify-center text-xs font-bold rounded-lg border border-slate-200" style="background-color: {{ $pagination_inactive_bg }}; color: {{ $pagination_inactive_text }};">2</span>
                                <span class="w-7 h-7 flex items-center justify-center text-xs font-bold rounded-lg" style="background-color: {{ $pagination_hover_bg }}; color: {{ $pagination_active_bg }};">3</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Active Box BG</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="pagination_active_bg" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="pagination_active_bg" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Active Box Text</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="pagination_active_text" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="pagination_active_text" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Inactive Box BG</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="pagination_inactive_bg" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="pagination_inactive_bg" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Inactive Box Text</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="pagination_inactive_text" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="pagination_inactive_text" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Box Hover BG</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="pagination_hover_bg" class="w-10 h-10 border border-slate-200 rounded-xl cursor-pointer bg-transparent p-0">
                                    <input type="text" wire:model.live="pagination_hover_bg" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-mono uppercase focus:outline-none">
                                </div>
                            </div>
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
                    </div>
                </div>

                {{-- Disable Shop Landing Page Toggle --}}
                <div class="pt-5 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Disable Shop Landing Page</label>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 max-w-xl">
                            When enabled, visitors navigating to the main shop landing page (<code class="px-1 py-0.5 bg-slate-100 dark:bg-slate-700 rounded text-slate-600 dark:text-slate-300 font-mono text-[11px]">/shop</code>) without any active category, brand, or search filters will automatically be redirected to the home page.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                        <input type="checkbox" wire:model="disable_shop_landing" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-violet-600"></div>
                    </label>
                </div>

                {{-- Enable Advanced Search Filtering Panel Toggle --}}
                <div class="pt-5 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Enable Advanced Search Filtering Panel</label>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 max-w-xl">
                            When enabled, a slideout drawer and collapsible filter panel will appear on the shop page allowing multi-select filtering by Brands, Categories, Subcategories, Price Slider, and Dynamic JSON Variant Attributes (Size, Color, etc.). (OFF by default).
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                        <input type="checkbox" wire:model="enable_advanced_shop_search" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-indigo-600"></div>
                    </label>
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

