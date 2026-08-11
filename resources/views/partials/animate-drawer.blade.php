{{-- ═══════════════════════════════════════════════════════════════════
     Animate Drawer — AOS Animation Panel
     Triggered by showAnimatePanel Alpine state on parent x-data.
     Applies data-aos-* attributes to the TinyMCE cursor's block element.
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-cloak
     x-show="showAnimatePanel"
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300 transform"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="translate-x-full"
     x-on:click.outside="showAnimatePanel = false"
     x-data="{
         staged: null,
         applied: false,
         stagedLabel: '',
         duration: 600,
         delay: 0,
         offset: 80,
         easing: 'ease-out-cubic',
         once: true,
         mirror: false,
         mobileEnable: false,

         stageAnimation(name, label) {
             this.staged = name;
             this.stagedLabel = label;
         },

         _getEditor() {
             return tinymce.activeEditor
                 ?? tinymce.get('cms_page_content_editor')
                 ?? tinymce.get('cms_page_left_col_editor')
                 ?? tinymce.get('cms_page_right_col_editor')
                 ?? tinymce.get('cms_page_trans_content_editor')
                 ?? null;
         },

         _getBlock(editor) {
             const node  = editor.selection.getNode();
             const block = editor.dom.getParent(node, n => editor.dom.isBlock(n)) || node;
             if (!block || block === editor.getBody()) return null;
             return block;
         },

         applyToBlock() {
             const editor = this._getEditor();
             if (!editor) { alert('No active TinyMCE editor found.'); return; }
             if (!this.staged) { alert('Select an animation first.'); return; }
             const block = this._getBlock(editor);
             if (!block) {
                 alert('Click inside a paragraph, heading, or div in the editor first.');
                 return;
             }
             const dom = editor.dom;
             dom.setAttrib(block, 'data-aos',          this.staged);
             dom.setAttrib(block, 'data-aos-duration', String(this.duration));
             dom.setAttrib(block, 'data-aos-offset',   String(this.offset));
             dom.setAttrib(block, 'data-aos-easing',   this.easing);
             dom.setAttrib(block, 'data-aos-once',     this.once   ? 'true' : 'false');
             if (this.delay > 0) {
                 dom.setAttrib(block, 'data-aos-delay', String(this.delay));
             } else {
                 dom.setAttrib(block, 'data-aos-delay', null);
             }
             if (this.mirror) {
                 dom.setAttrib(block, 'data-aos-mirror', 'true');
             } else {
                 dom.setAttrib(block, 'data-aos-mirror', null);
             }
             if (this.mobileEnable) {
                 dom.setAttrib(block, 'data-aos-mobile', 'true');
             } else {
                 dom.setAttrib(block, 'data-aos-mobile', null);
             }
             editor.undoManager.add();
             editor.dispatch('change');
         },

         removeFromBlock() {
             const editor = this._getEditor();
             if (!editor) return;
             const block = this._getBlock(editor);
             if (!block) return;
             const dom = editor.dom;
             ['data-aos','data-aos-duration','data-aos-delay','data-aos-offset',
              'data-aos-easing','data-aos-once','data-aos-mirror','data-aos-mobile'
             ].forEach(attr => block.removeAttribute(attr));
             editor.undoManager.add();
             editor.dispatch('change');
             this.staged = null;
             this.stagedLabel = '';
         }
     }"
     class="fixed inset-y-0 right-0 w-[340px] bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-700 z-50 shadow-2xl flex flex-col">

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-violet-50/60 dark:bg-violet-950/20 shrink-0">
        <div>
            <h4 class="text-sm font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-violet-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3l14 9-14 9V3z"/></svg>
                </span>
                Animations
            </h4>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider mt-0.5">Click block in editor → select effect → Apply</p>
        </div>
        <button type="button"
                x-on:click="showAnimatePanel = false"
                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- ── Scrollable Body ─────────────────────────────────────────── --}}
    <div class="flex-1 overflow-y-auto">

        {{-- Staged Settings (shown when an animation is selected) --}}
        <div x-show="staged !== null"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="p-4 bg-violet-50 dark:bg-violet-950/30 border-b border-violet-100 dark:border-violet-900/40">

            {{-- Selected animation badge --}}
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Selected:</span>
                <span class="px-2.5 py-1 bg-violet-600 text-white text-xs font-bold rounded-full" x-text="stagedLabel"></span>
            </div>

            {{-- Duration --}}
            <div class="mb-3">
                <label class="flex justify-between text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">
                    <span>Duration</span><span class="text-violet-600 font-extrabold" x-text="duration + 'ms'"></span>
                </label>
                <input type="range" min="200" max="2000" step="50" x-model.number="duration"
                       class="w-full h-1.5 rounded-full accent-violet-600 cursor-pointer">
            </div>

            {{-- Delay --}}
            <div class="mb-3">
                <label class="flex justify-between text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">
                    <span>Delay</span><span class="text-violet-600 font-extrabold" x-text="delay + 'ms'"></span>
                </label>
                <input type="range" min="0" max="1000" step="50" x-model.number="delay"
                       class="w-full h-1.5 rounded-full accent-violet-600 cursor-pointer">
            </div>

            {{-- Offset --}}
            <div class="mb-3">
                <label class="flex justify-between text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">
                    <span>Offset</span><span class="text-violet-600 font-extrabold" x-text="offset + 'px'"></span>
                </label>
                <input type="range" min="0" max="300" step="10" x-model.number="offset"
                       class="w-full h-1.5 rounded-full accent-violet-600 cursor-pointer">
            </div>

            {{-- Easing --}}
            <div class="mb-3">
                <label class="text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1 block">Easing</label>
                <select x-model="easing"
                        class="w-full text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-violet-400">
                    <option value="ease">ease</option>
                    <option value="ease-in">ease-in</option>
                    <option value="ease-out">ease-out</option>
                    <option value="ease-in-out">ease-in-out</option>
                    <option value="linear">linear</option>
                    <option value="ease-out-cubic">ease-out-cubic (smooth)</option>
                    <option value="ease-in-back">ease-in-back (spring)</option>
                    <option value="ease-out-back">ease-out-back (spring out)</option>
                </select>
            </div>

            {{-- Toggles row --}}
            <div class="grid grid-cols-3 gap-2 mb-4">
                {{-- Once --}}
                <label class="flex flex-col items-center gap-1 cursor-pointer group">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Once</span>
                    <button type="button" x-on:click="once = !once"
                            :class="once ? 'bg-violet-600' : 'bg-slate-200 dark:bg-slate-700'"
                            class="relative w-8 h-4 rounded-full transition-colors duration-200 focus:outline-none">
                        <span :class="once ? 'translate-x-4' : 'translate-x-0.5'"
                              class="absolute top-0.5 w-3 h-3 bg-white rounded-full shadow transition-transform duration-200 block"></span>
                    </button>
                </label>
                {{-- Mirror --}}
                <label class="flex flex-col items-center gap-1 cursor-pointer group">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Mirror</span>
                    <button type="button" x-on:click="mirror = !mirror"
                            :class="mirror ? 'bg-violet-600' : 'bg-slate-200 dark:bg-slate-700'"
                            class="relative w-8 h-4 rounded-full transition-colors duration-200 focus:outline-none">
                        <span :class="mirror ? 'translate-x-4' : 'translate-x-0.5'"
                              class="absolute top-0.5 w-3 h-3 bg-white rounded-full shadow transition-transform duration-200 block"></span>
                    </button>
                </label>
                {{-- Mobile --}}
                <label class="flex flex-col items-center gap-1 cursor-pointer group">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Mobile</span>
                    <button type="button" x-on:click="mobileEnable = !mobileEnable"
                            :class="mobileEnable ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-700'"
                            class="relative w-8 h-4 rounded-full transition-colors duration-200 focus:outline-none">
                        <span :class="mobileEnable ? 'translate-x-4' : 'translate-x-0.5'"
                              class="absolute top-0.5 w-3 h-3 bg-white rounded-full shadow transition-transform duration-200 block"></span>
                    </button>
                </label>
            </div>

            {{-- Mobile note --}}
            <p x-show="!mobileEnable" class="text-[10px] text-slate-400 dark:text-slate-500 mb-3 -mt-1 italic">
                Animations hidden on screens &lt; 768px by default. Enable "Mobile" to override.
            </p>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button type="button"
                        x-on:click="applyToBlock(); applied = true; setTimeout(() => { applied = false; showAnimatePanel = false; }, 1500)"
                        :disabled="applied"
                        :class="applied ? 'bg-emerald-600 cursor-default' : 'bg-violet-600 hover:bg-violet-700'"
                        class="flex-1 text-white text-xs font-extrabold px-3 py-2 rounded-xl transition-colors shadow-sm hover:shadow-md">
                    <span x-text="applied ? '✓ Animation Added' : '◆ Apply to Block'"></span>
                </button>
                <button type="button"
                        x-on:click="removeFromBlock()"
                        class="flex-1 bg-slate-100 dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-950/30 text-slate-600 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 text-xs font-extrabold px-3 py-2 rounded-xl transition-colors border border-slate-200 dark:border-slate-700">
                    ✕ Remove
                </button>
            </div>
        </div>

        {{-- No animation selected hint --}}
        <div x-show="staged === null" class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium text-center">
                ↓ Select an animation below, then click <strong class="text-violet-500">Apply to Block</strong>
            </p>
        </div>

        <div class="p-4 space-y-5">

            {{-- ── FADE ──────────────────────────────────────────── --}}
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-2 px-1 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 inline-block"></span>Fade
                </p>
                <div class="grid grid-cols-2 gap-1.5">
                    @foreach([
                        ['fade',            'Fade'],
                        ['fade-up',         'Fade Up'],
                        ['fade-down',       'Fade Down'],
                        ['fade-left',       'Fade Left'],
                        ['fade-right',      'Fade Right'],
                        ['fade-up-right',   'Fade Up Right'],
                        ['fade-up-left',    'Fade Up Left'],
                        ['fade-down-right', 'Fade Down Right'],
                        ['fade-down-left',  'Fade Down Left'],
                    ] as [$key, $label])
                    <button type="button"
                            x-on:click="stageAnimation('{{ $key }}', '{{ $label }}')"
                            :class="staged === '{{ $key }}' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-indigo-300 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20'"
                            class="text-left px-2.5 py-1.5 rounded-lg border text-[11px] font-semibold transition-all">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- ── FLIP ──────────────────────────────────────────── --}}
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-amber-500 dark:text-amber-400 mb-2 px-1 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>Flip
                </p>
                <div class="grid grid-cols-2 gap-1.5">
                    @foreach([
                        ['flip-left',  'Flip Left'],
                        ['flip-right', 'Flip Right'],
                        ['flip-up',    'Flip Up'],
                        ['flip-down',  'Flip Down'],
                    ] as [$key, $label])
                    <button type="button"
                            x-on:click="stageAnimation('{{ $key }}', '{{ $label }}')"
                            :class="staged === '{{ $key }}' ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-amber-300 hover:bg-amber-50/50 dark:hover:bg-amber-900/20'"
                            class="text-left px-2.5 py-1.5 rounded-lg border text-[11px] font-semibold transition-all">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- ── SLIDE ─────────────────────────────────────────── --}}
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-500 dark:text-emerald-400 mb-2 px-1 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>Slide
                </p>
                <div class="grid grid-cols-2 gap-1.5">
                    @foreach([
                        ['slide-up',    'Slide Up'],
                        ['slide-down',  'Slide Down'],
                        ['slide-left',  'Slide Left'],
                        ['slide-right', 'Slide Right'],
                    ] as [$key, $label])
                    <button type="button"
                            x-on:click="stageAnimation('{{ $key }}', '{{ $label }}')"
                            :class="staged === '{{ $key }}' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-emerald-300 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/20'"
                            class="text-left px-2.5 py-1.5 rounded-lg border text-[11px] font-semibold transition-all">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- ── ZOOM ──────────────────────────────────────────── --}}
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-violet-500 dark:text-violet-400 mb-2 px-1 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-violet-500 inline-block"></span>Zoom
                </p>
                <div class="grid grid-cols-2 gap-1.5">
                    @foreach([
                        ['zoom-in',         'Zoom In'],
                        ['zoom-in-up',      'Zoom In Up'],
                        ['zoom-in-down',    'Zoom In Down'],
                        ['zoom-in-left',    'Zoom In Left'],
                        ['zoom-in-right',   'Zoom In Right'],
                        ['zoom-out',        'Zoom Out'],
                        ['zoom-out-up',     'Zoom Out Up'],
                        ['zoom-out-down',   'Zoom Out Down'],
                        ['zoom-out-left',   'Zoom Out Left'],
                        ['zoom-out-right',  'Zoom Out Right'],
                    ] as [$key, $label])
                    <button type="button"
                            x-on:click="stageAnimation('{{ $key }}', '{{ $label }}')"
                            :class="staged === '{{ $key }}' ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-violet-300 hover:bg-violet-50/50 dark:hover:bg-violet-900/20'"
                            class="text-left px-2.5 py-1.5 rounded-lg border text-[11px] font-semibold transition-all">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

        </div>{{-- /p-4 --}}
    </div>{{-- /flex-1 scrollable --}}

    {{-- ── Footer tip ──────────────────────────────────────────────── --}}
    <div class="p-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 shrink-0">
        <p class="text-[10px] text-slate-400 dark:text-slate-500 text-center font-medium">
            Animated blocks show a <span class="text-violet-500 font-bold">◆ purple outline</span> in the editor.
            Animations fire on scroll on the public page.
        </p>
    </div>

</div>
