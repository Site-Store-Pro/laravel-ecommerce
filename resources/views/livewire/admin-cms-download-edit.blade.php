<div class="py-12"
     x-data="{
         sourceType: @entangle('source_type').live,
         toast: { show: false, message: '' },
         showToast(msg) {
             this.toast = { show: true, message: msg };
             setTimeout(() => { this.toast.show = false; }, 3500);
         }
     }"
     x-on:cms-download-saved.window="showToast($event.detail.message)">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vivid.min.css">
        @endpush

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('admin.cms-downloads.index') }}" wire:navigate
                       class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
                        {{ $downloadId ? 'Edit Download' : 'New Download' }}
                    </h1>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 ml-8">
                    {{ $downloadId ? "Update this download's settings and file source." : 'Configure a new downloadable file for use in CMS page shortcodes.' }}
                </p>
            </div>

            <button wire:click="save" wire:loading.attr="disabled"
                    class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white font-semibold px-6 py-2.5 rounded-2xl shadow-md shadow-teal-100 hover:shadow-lg transition-all inline-flex items-center gap-2 disabled:opacity-70">
                <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">Save Download</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>

        {{-- Status Flash --}}
        @if(session()->has('status'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/60 text-emerald-800 dark:text-emerald-400 rounded-2xl flex items-center gap-3 font-semibold text-sm">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Shortcode Preview (shown after save) --}}
        @if($downloadId && $downloadUuid)
            <div class="mb-6 bg-teal-50 dark:bg-teal-950/30 border border-teal-200 dark:border-teal-800/50 rounded-2xl p-5">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    <span class="text-xs font-extrabold text-teal-700 dark:text-teal-400 uppercase tracking-wider">Your Shortcode</span>
                </div>
                <code class="block font-mono text-sm text-teal-800 dark:text-teal-300 select-all cursor-text bg-white dark:bg-slate-900 border border-teal-200 dark:border-teal-800/60 rounded-xl px-4 py-3">
                    [download:{{ $downloadUuid }}{{ $link_label ? ' label="' . $link_label . '"' : '' }}]
                </code>
                <p class="text-xs text-teal-600/70 dark:text-teal-500 mt-2">
                    Paste into any CMS page. Override the label inline: <code class="font-mono bg-teal-100/60 dark:bg-teal-900/30 px-1 rounded">[download:{{ $downloadUuid }} label="Custom Label"]</code>
                </p>
            </div>
        @endif

        <div class="space-y-6">

            {{-- ═══ Section 1: Basic Info ═══ --}}
            <div class="bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-3xl p-8 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Basic Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">
                            Internal Name <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="internal_name" type="text" placeholder="e.g. User Guide PDF v2"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 text-sm transition-colors">
                        @error('internal_name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">Admin-only label, not shown on the frontend.</p>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">
                            Default Link Label
                        </label>
                        <input wire:model="link_label" type="text" placeholder="e.g. Download PDF Guide"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 text-sm transition-colors">
                        @error('link_label') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">Used if no <code class="font-mono bg-slate-100 dark:bg-slate-700 px-1 rounded">label=</code> is supplied in the shortcode.</p>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">
                            Expiry Date / Time
                        </label>
                        <input wire:model="expires_at" type="datetime-local"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-2xl focus:outline-none focus:border-teal-500 text-sm transition-colors">
                        @error('expires_at') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">After this date/time the download returns a 410 Gone response. Leave blank for no expiry.</p>
                    </div>
                </div>
            </div>

            {{-- ═══ Section 2: File Source ═══ --}}
            <div class="bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-3xl p-8 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    File Source
                </h2>

                {{-- Source Type Selector --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
                    @foreach([
                        [0, 'Local Upload',  'bg-slate-100 dark:bg-slate-700', 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'],
                        [1, 'Direct URL',    'bg-sky-100 dark:bg-sky-950/40',  'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                        [2, 'Env S3',        'bg-amber-100 dark:bg-amber-950/40', 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z'],
                        [3, 'Custom S3',     'bg-violet-100 dark:bg-violet-950/40', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                    ] as [$val, $label, $bg, $icon])
                        <button type="button"
                                x-on:click="sourceType = {{ $val }}; $wire.set('source_type', {{ $val }})"
                                :class="sourceType == {{ $val }} ? 'ring-2 ring-teal-500 bg-teal-50 dark:bg-teal-950/40 border-teal-300 dark:border-teal-700' : 'border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500'"
                                class="flex flex-col items-center gap-2 p-4 border rounded-2xl transition-all text-center cursor-pointer">
                            <div class="w-9 h-9 {{ $bg }} rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $label }}</span>
                        </button>
                    @endforeach
                </div>

                {{-- Mode 0: Local Upload --}}
                <div x-show="sourceType == 0" x-cloak class="space-y-5">
                    <div class="p-5 bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-700">
                        @if($file_path)
                            <div class="flex items-center justify-between mb-4 p-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-teal-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 font-mono">{{ basename($file_path) }}</span>
                                </div>
                                <button wire:click="deleteFile" wire:confirm="Remove this file?"
                                        class="text-red-500 hover:text-red-700 transition-colors p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        @endif
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-2">
                            {{ $file_path ? 'Replace File' : 'Upload File' }}
                        </label>
                        <input wire:model="file_upload" type="file"
                               class="block w-full text-sm text-slate-600 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-teal-950/40 dark:file:text-teal-400 transition-all">
                        @error('file_upload') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-2">Max 100 MB. Files stored in <code class="font-mono bg-slate-100 dark:bg-slate-700 px-1 rounded">storage/app/public/cms_downloads/</code></p>
                    </div>
                </div>

                {{-- Mode 1: Direct URL --}}
                <div x-show="sourceType == 1" x-cloak class="space-y-4">
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">CDN / Direct URL</label>
                    <input wire:model="cdn_url" type="url" placeholder="https://cdn.example.com/file.pdf"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-teal-500 text-sm font-mono transition-colors">
                    @error('cdn_url') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    <p class="text-xs text-slate-400">Visitor will be redirected directly to this URL — no server proxying. Use for public CDN files.</p>
                </div>

                {{-- Mode 2: Env S3 --}}
                <div x-show="sourceType == 2" x-cloak class="space-y-5">
                    <div class="p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 rounded-2xl text-xs text-amber-700 dark:text-amber-400 font-medium">
                        Uses your <code class="font-mono bg-amber-100 dark:bg-amber-900/40 px-1 rounded">.env</code> <code class="font-mono bg-amber-100 dark:bg-amber-900/40 px-1 rounded">AWS_*</code> credentials. A pre-signed URL is generated server-side on each access — credentials never reach the browser.
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">S3 Object Key (File Path)</label>
                            <input wire:model="s3_file_key" type="text" placeholder="uploads/guides/user-guide-v2.pdf"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-teal-500 text-sm font-mono transition-colors">
                            @error('s3_file_key') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">Pre-Signed URL Expiration (Seconds)</label>
                            <input wire:model="s3_expiration_seconds" type="number" min="60" max="86400"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-teal-500 text-sm transition-colors">
                            @error('s3_expiration_seconds') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            <p class="text-xs text-slate-400 mt-1">Default: 600 seconds (10 min). Max: 86400 (24 hours).</p>
                        </div>
                    </div>
                </div>

                {{-- Mode 3: Custom S3 --}}
                <div x-show="sourceType == 3" x-cloak class="space-y-5">
                    <div class="p-4 bg-violet-50 dark:bg-violet-950/30 border border-violet-200 dark:border-violet-800/50 rounded-2xl text-xs text-violet-700 dark:text-violet-400 font-medium">
                        Per-file AWS credentials — allows using a bucket different from your main <code class="font-mono bg-violet-100 dark:bg-violet-900/40 px-1 rounded">.env</code> S3 config. Pre-signed URL generated server-side.
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">AWS Access Key ID</label>
                            <input wire:model="s3_custom_key" type="text" autocomplete="off"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-violet-500 text-sm font-mono transition-colors">
                            @error('s3_custom_key') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">AWS Secret Access Key</label>
                            <input wire:model="s3_custom_secret" type="password" autocomplete="new-password"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-violet-500 text-sm font-mono transition-colors">
                            @error('s3_custom_secret') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">AWS Region</label>
                            <input wire:model="s3_custom_region" type="text" placeholder="us-east-1"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-violet-500 text-sm font-mono transition-colors">
                            @error('s3_custom_region') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">S3 Bucket Name</label>
                            <input wire:model="s3_custom_bucket" type="text"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-violet-500 text-sm font-mono transition-colors">
                            @error('s3_custom_bucket') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">S3 Object Key (File Path)</label>
                            <input wire:model="s3_custom_file_key" type="text" placeholder="folder/file.pdf"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-violet-500 text-sm font-mono transition-colors">
                            @error('s3_custom_file_key') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">Pre-Signed URL Expiration (Seconds)</label>
                            <input wire:model="s3_custom_expiration_seconds" type="number" min="60" max="86400"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-violet-500 text-sm transition-colors">
                            @error('s3_custom_expiration_seconds') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Section 3: Display Options ═══ --}}
            <div class="bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-3xl p-8 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Display Options
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Show Icon Position --}}
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 rounded-2xl">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-3">File Type Icon</span>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                            Prepends a file-type icon to the download link on the frontend. Choose icon position relative to the label.
                        </p>
                        <div class="grid grid-cols-5 gap-2 mb-3">
                            @foreach([
                                [0, 'None',   'M6 18L18 6M6 6l12 12'],
                                [1, 'Left',   'M10 19l-7-7m0 0l7-7m-7 7h18'],
                                [2, 'Right',  'M14 5l7 7m0 0l-7 7m7-7H3'],
                                [3, 'Top',    'M5 10l7-7m0 0l7 7M12 3v18'],
                                [4, 'Bottom', 'M19 14l-7 7m0 0l-7-7m7 7V3'],
                            ] as [$val, $lbl, $iconPath])
                                <label class="flex flex-col items-center gap-1.5 cursor-pointer">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center border-2 transition-all
                                        {{ $show_icon == $val ? 'border-teal-500 bg-teal-50 dark:bg-teal-950/40' : 'border-slate-200 dark:border-slate-600 hover:border-slate-300' }}">
                                        <svg class="w-4 h-4 {{ $show_icon == $val ? 'text-teal-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                                        </svg>
                                    </div>
                                    <span class="text-[10px] font-bold {{ $show_icon == $val ? 'text-teal-600 dark:text-teal-400' : 'text-slate-400' }} uppercase tracking-wide">{{ $lbl }}</span>
                                    <input wire:model.live="show_icon" type="radio" value="{{ $val }}" class="sr-only">
                                </label>
                            @endforeach
                        </div>
                        {{-- Live icon preview --}}
                        @if($show_icon > 0)
                            @php
                                $previewExt = null;
                                if ($source_type === 0 && $file_path) $previewExt = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                                elseif ($source_type === 1 && $cdn_url) $previewExt = strtolower(pathinfo(parse_url($cdn_url, PHP_URL_PATH), PATHINFO_EXTENSION));
                                elseif ($source_type === 2 && $s3_file_key) $previewExt = strtolower(pathinfo($s3_file_key, PATHINFO_EXTENSION));
                                elseif ($source_type === 3 && $s3_custom_file_key) $previewExt = strtolower(pathinfo($s3_custom_file_key, PATHINFO_EXTENSION));
                            @endphp
                            @if($previewExt)
                                <div class="mt-2 flex items-center gap-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2">
                                    <span class="fiv-cla fiv-viv fiv-sqo fiv-icon-{{ $previewExt }}" style="font-size:1.8em;"></span>
                                    <span class="text-xs text-slate-500">Preview: .{{ strtoupper($previewExt) }} icon</span>
                                </div>
                            @else
                                <p class="text-xs text-amber-600 dark:text-amber-400 mt-2">Set a file source above to preview the icon.</p>
                            @endif
                        @endif
                    </div>


                    {{-- Open In New Tab --}}
                    <label class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-teal-300 dark:hover:border-teal-700 transition-colors">
                        <div class="pt-0.5">
                            <input wire:model="open_in_new_tab" type="checkbox" id="open_in_new_tab"
                                   class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 block">Open in New Tab</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 block">Adds <code class="font-mono bg-slate-100 dark:bg-slate-700 px-1 rounded">target="_blank"</code> to the rendered link.</span>
                        </div>
                    </label>

                    {{-- Force Download --}}
                    <label class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-teal-300 dark:hover:border-teal-700 transition-colors">
                        <div class="pt-0.5">
                            <input wire:model="force_download" type="checkbox" id="force_download"
                                   class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 block">Force Download</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 block">Streams the file with <code class="font-mono bg-slate-100 dark:bg-slate-700 px-1 rounded">Content-Disposition: attachment</code> instead of displaying inline. Local source only.</span>
                        </div>
                    </label>

                    {{-- Active --}}
                    <label class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-teal-300 dark:hover:border-teal-700 transition-colors">
                        <div class="pt-0.5">
                            <input wire:model="is_active" type="checkbox" id="is_active"
                                   class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 block">Active</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 block">Inactive downloads return 404 and render as an empty string in shortcodes.</span>
                        </div>
                    </label>
                </div>

                {{-- Custom CSS --}}
                <div class="mt-6">
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">Custom CSS (Optional)</label>
                    <textarea wire:model="custom_css" rows="4" placeholder=".cms-download-link { font-weight: 700; color: #0d9488; }"
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-teal-500 text-sm font-mono transition-colors resize-none"></textarea>
                    @error('custom_css') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    <p class="text-xs text-slate-400 mt-1">Injected as a scoped <code class="font-mono bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;style&gt;</code> block alongside the rendered download link.</p>
                </div>
            </div>

            {{-- ═══ Section 4: Video / Poster Image ═══ --}}
            <div class="bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-3xl p-8 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Video / Poster Image
                </h2>
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-6">For video files — the poster image will be wired into the video player automatically when video support is added.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Poster Image — Local Upload</label>
                        @if($poster_image_path)
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl">
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 font-mono">{{ basename($poster_image_path) }}</span>
                                <button wire:click="deletePosterImage" wire:confirm="Remove this poster image?"
                                        class="text-red-500 hover:text-red-700 transition-colors p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endif
                        <input wire:model="poster_upload" type="file" accept="image/*"
                               class="block w-full text-sm text-slate-600 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-slate-700 dark:file:text-slate-300 transition-all">
                        @error('poster_upload') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1.5">Poster Image — CDN URL</label>
                        <input wire:model="poster_image_cdn_url" type="url" placeholder="https://cdn.example.com/poster.jpg"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl focus:outline-none focus:border-teal-500 text-sm font-mono transition-colors">
                        @error('poster_image_cdn_url') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">Takes priority over local upload if both are set.</p>
                    </div>
                </div>
            </div>

            {{-- Save Button (bottom) --}}
            <div class="flex flex-col items-end gap-3 pb-8">

                {{-- Inline success banner (visible after save when scrolled down) --}}
                @if(session()->has('status'))
                    <div class="w-full flex items-center gap-3 px-5 py-3.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 rounded-2xl text-emerald-800 dark:text-emerald-300 text-sm font-semibold shadow-sm">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <button wire:click="save" wire:loading.attr="disabled"
                        class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white font-semibold px-8 py-3 rounded-2xl shadow-md shadow-teal-100 hover:shadow-lg transition-all inline-flex items-center gap-2 disabled:opacity-70">
                    <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">Save Download</span>
                    <span wire:loading wire:target="save">Saving…</span>
                </button>
            </div>

        </div>
    </div>

    {{-- ── Fixed Toast Notification (always visible regardless of scroll) ── --}}
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         x-cloak
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 pl-4 pr-5 py-3.5 bg-emerald-600 text-white rounded-2xl shadow-2xl shadow-emerald-900/30 text-sm font-semibold max-w-sm pointer-events-none">
        <span class="flex-shrink-0 w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </span>
        <span x-text="toast.message"></span>
    </div>

</div>
