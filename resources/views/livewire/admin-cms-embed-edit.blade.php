<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cms-embeds.index') }}" wire:navigate
               class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    {{ $embedId ? 'Edit Code Embed' : 'New Code Embed' }}
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    {{ $embedId ? 'Update the embed snippet or settings.' : 'Create a reusable embed shortcode.' }}
                </p>
            </div>
        </div>

        {{-- Flash --}}
        @if(session()->has('status'))
            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-50 rounded-2xl border border-red-100 text-red-700 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p class="font-semibold">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- LEFT PANEL — Settings --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Name --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Details</h3>

                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5 uppercase tracking-wider">
                                Name <span class="text-red-400">*</span>
                            </label>
                            <input type="text" wire:model="name" id="embed_name"
                                   placeholder="e.g. Homepage Intro Video"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-sm focus:outline-none focus:border-indigo-500">
                            <p class="text-xs text-slate-400 mt-1">Internal use only — never shown publicly.</p>
                            @error('name') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        {{-- Active toggle --}}
                        <div class="flex items-center justify-between pt-1">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Active</p>
                                <p class="text-xs text-slate-400">Inactive embeds render as an HTML comment.</p>
                            </div>
                            <button type="button" wire:click="$toggle('is_active')"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                           {{ $is_active ? 'bg-emerald-500' : 'bg-slate-200' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                             {{ $is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Embed Type --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-3">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Embed Type</h3>
                        <p class="text-xs text-slate-400">YouTube and Vimeo embeds are automatically wrapped in a responsive 16:9 container. Other HTML is output verbatim.</p>

                        {{-- YouTube --}}
                        <label class="flex items-start gap-3 p-3 rounded-2xl border cursor-pointer transition
                                      {{ $embed_type == 0 ? 'border-red-300 bg-red-50' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                            <input type="radio" wire:model.live="embed_type" value="0" class="mt-0.5 text-red-500 focus:ring-red-400">
                            <div>
                                <p class="text-sm font-bold text-slate-800">YouTube</p>
                                <p class="text-xs text-slate-500">Paste a YouTube <code class="font-mono bg-slate-100 px-1 rounded">&lt;iframe&gt;</code> embed code.</p>
                            </div>
                        </label>

                        {{-- Vimeo --}}
                        <label class="flex items-start gap-3 p-3 rounded-2xl border cursor-pointer transition
                                      {{ $embed_type == 1 ? 'border-sky-300 bg-sky-50' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                            <input type="radio" wire:model.live="embed_type" value="1" class="mt-0.5 text-sky-500 focus:ring-sky-400">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Vimeo</p>
                                <p class="text-xs text-slate-500">Paste a Vimeo <code class="font-mono bg-slate-100 px-1 rounded">&lt;iframe&gt;</code> embed code.</p>
                            </div>
                        </label>

                        {{-- Other HTML --}}
                        <label class="flex items-start gap-3 p-3 rounded-2xl border cursor-pointer transition
                                      {{ $embed_type == 2 ? 'border-indigo-300 bg-indigo-50' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                            <input type="radio" wire:model.live="embed_type" value="2" class="mt-0.5 text-indigo-500 focus:ring-indigo-400">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Other HTML</p>
                                <p class="text-xs text-slate-500">Any raw HTML snippet — code blocks, custom widgets, third-party embeds, etc.</p>
                            </div>
                        </label>

                        @error('embed_type') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Shortcode badge --}}
                    @if($embedId)
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Shortcode</h3>
                            <p class="text-xs text-slate-500 mb-2">Copy and paste this into any CMS page, product description, or list menu item:</p>
                            <div class="flex items-center gap-2">
                                <code id="embed-shortcode-{{ $embedId }}"
                                      class="flex-1 text-sm bg-slate-900 text-emerald-400 font-mono px-4 py-2.5 rounded-xl select-all">
                                    [code-embed:{{ $embedId }}]
                                </code>
                                <button type="button"
                                        onclick="navigator.clipboard.writeText('[code-embed:{{ $embedId }}]').then(()=>{ this.textContent='✓'; setTimeout(()=>{ this.textContent='Copy'; }, 1500); })"
                                        class="px-3 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                    Copy
                                </button>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">With custom label:
                                <code class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-xs">[code-embed:{{ $embedId }} label="My Embed"]</code>
                            </p>
                        </div>
                    @endif

                </div>

                {{-- RIGHT PANEL — Code Snippet --}}
                <div class="lg:col-span-3 space-y-5">

                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Code Snippet</h3>
                            @if($embed_type == 0)
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">YouTube</span>
                            @elseif($embed_type == 1)
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-700 border border-sky-200">Vimeo</span>
                            @else
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 border border-indigo-200">Other HTML</span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-500">
                            @if($embed_type == 0)
                                Paste your full YouTube <code class="font-mono bg-slate-100 px-1 rounded">&lt;iframe&gt;</code> embed code below. It will be wrapped in a responsive 16:9 container automatically.
                            @elseif($embed_type == 1)
                                Paste your full Vimeo <code class="font-mono bg-slate-100 px-1 rounded">&lt;iframe&gt;</code> embed code below. It will be wrapped in a responsive 16:9 container automatically.
                            @else
                                Enter any raw HTML. It will be output verbatim wherever the shortcode is placed. This textarea is intentionally <strong>not</strong> connected to TinyMCE to protect your code from being reformatted or stripped.
                            @endif
                        </p>

                        {{-- Raw monospace textarea — NO TinyMCE --}}
                        <div wire:ignore class="relative">
                            <textarea
                                wire:model="code_snippet"
                                id="embed_code_snippet"
                                rows="16"
                                spellcheck="false"
                                placeholder="{{ $embed_type < 2 ? '<iframe src=\"https://...\" ...></iframe>' : '<!-- your HTML here -->' }}"
                                class="w-full px-4 py-3 bg-slate-900 text-emerald-300 font-mono text-sm rounded-2xl
                                       border border-slate-700 focus:outline-none focus:border-indigo-500
                                       placeholder-slate-600 resize-y leading-relaxed">{{ $code_snippet }}</textarea>
                        </div>

                        {{-- Keep textarea in sync with wire:model via JS --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const ta = document.getElementById('embed_code_snippet');
                                if (!ta) return;
                                ta.addEventListener('input', function () {
                                    @this.set('code_snippet', ta.value);
                                });
                            });
                        </script>

                        @error('code_snippet') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Live Preview --}}
                    @if(!empty($code_snippet))
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-3">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Live Preview</h3>
                            @if($embed_type == 0 || $embed_type == 1)
                                <p class="text-xs text-slate-400">Shown below in the responsive 16:9 wrapper that will be applied on the frontend.</p>
                                <div class="cms-embed-video-outer" style="max-width:100%;margin:0 auto;">
                                    <div class="cms-embed-video-wrapper" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">
                                        <div style="position:absolute;top:0;left:0;width:100%;height:100%;">
                                            {!! $code_snippet !!}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-amber-600 font-semibold bg-amber-50 border border-amber-200 rounded-xl px-3 py-2">
                                    ⚠ Raw HTML preview — only admins can create embeds. Ensure your HTML is safe before saving.
                                </p>
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm">
                                    {!! $code_snippet !!}
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>

            {{-- Save Bar --}}
            <div class="mt-6 flex items-center justify-between bg-white border border-slate-100 rounded-3xl px-6 py-4 shadow-sm">
                <a href="{{ route('admin.cms-embeds.index') }}" wire:navigate
                   class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition">
                    ← Back to Embeds
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-md hover:bg-indigo-700 transition">
                    {{ $embedId ? 'Update Embed' : 'Save Embed' }}
                </button>
            </div>
        </form>

    </div>
</div>
