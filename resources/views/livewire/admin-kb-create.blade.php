<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.kb.index') }}" wire:navigate
               class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">Create KB Article</h2>
                <p class="text-sm text-slate-500 mt-0.5">Publish a new helpful article or guide for customers</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        slugEdited: false,
        updateSlug(titleVal) {
            if (!this.slugEdited) {
                let slug = titleVal.toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s\-_]/g, '')
                    .replace(/[-\s]+/g, '-');
                $wire.set('form.seo_link', slug);
            }
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-slate-200/70 shadow-sm rounded-2xl p-8">
                <form wire:submit="save" class="space-y-6">

                    {{-- Title --}}
                    <div>
                        <x-input-label for="title" :value="__('Article Title')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                        <x-text-input wire:model.live="form.title" id="title" type="text"
                                      x-on:input="updateSlug($event.target.value)"
                                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                      placeholder="e.g. How to Reset Your Password" autofocus />
                        <x-input-error :messages="$errors->get('form.title')" class="mt-1.5 text-xs" />
                    </div>

                    {{-- SEO Link / Slug --}}
                    <div>
                        <x-input-label for="seo_link" :value="__('SEO Link / Slug')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                        <div class="mt-1 flex rounded-xl shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-xs font-medium">
                                {{ url('/kb') }}/
                            </span>
                            <input wire:model="form.seo_link" id="seo_link" type="text"
                                   x-on:input="slugEdited = true"
                                   class="flex-1 block w-full min-w-0 rounded-none rounded-r-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                   placeholder="how-to-reset-your-password" />
                        </div>
                        <p class="text-slate-400 text-[10px] mt-1">This will be the URL address of the article. Generated automatically from the title unless customized.</p>
                        <x-input-error :messages="$errors->get('form.seo_link')" class="mt-1.5 text-xs" />
                    </div>

                    {{-- Meta Description --}}
                    <div>
                        <x-input-label for="meta_description" :value="__('Meta Description')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                        <textarea wire:model="form.meta_description" id="meta_description" rows="2"
                                  class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                  placeholder="Provide a short search-engine description (recommended for SEO)..."></textarea>
                        <x-input-error :messages="$errors->get('form.meta_description')" class="mt-1.5 text-xs" />
                    </div>

                    {{-- Category & Status --}}
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="category_id" :value="__('Category (Optional)')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                            <select wire:model="form.category_id" id="category_id"
                                    class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm">
                                <option value="">— No Category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('form.category_id')" class="mt-1.5 text-xs" />
                        </div>

                        <div>
                            <x-input-label :value="__('Status')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-2" />
                            <div class="flex gap-4">
                                <label class="flex-1 flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer transition-all
                                    {{ (int)$form->article_active === 1 ? 'border-emerald-300 bg-emerald-50 text-emerald-800 font-semibold' : 'border-slate-200 hover:bg-slate-50 text-slate-600' }}">
                                    <input wire:model.live="form.article_active" type="radio" name="article_active" value="1"
                                           class="text-emerald-600 border-slate-300 focus:ring-emerald-500" />
                                    <span>Active (Public)</span>
                                </label>
                                <label class="flex-1 flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer transition-all
                                    {{ (int)$form->article_active === 0 ? 'border-slate-400 bg-slate-100 text-slate-700 font-semibold' : 'border-slate-200 hover:bg-slate-50 text-slate-600' }}">
                                    <input wire:model.live="form.article_active" type="radio" name="article_active" value="0"
                                           class="text-slate-600 border-slate-300 focus:ring-slate-500" />
                                    <span>Inactive (Draft)</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('form.article_active')" class="mt-1.5 text-xs" />
                        </div>
                    </div>

                    {{-- Show Date Toggle, Sort Order & Rating --}}
                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div>
                                <div class="text-sm font-semibold text-slate-700">Show publication date</div>
                                <div class="text-xs text-slate-400 mt-0.5">Display the added/modified date at the top of the public article page.</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input wire:model.live="form.show_date" type="checkbox" value="1" class="sr-only peer" {{ $form->show_date ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        
                        <div>
                            <x-input-label for="sort_order" :value="__('Sort Order')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                            <x-text-input wire:model="form.sort_order" id="sort_order" type="number"
                                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                          placeholder="0" />
                            <p class="text-slate-400 text-[10px] mt-1">Lower numbers appear first.</p>
                            <x-input-error :messages="$errors->get('form.sort_order')" class="mt-1.5 text-xs" />
                        </div>

                        <div>
                            <x-input-label for="kb_rating" :value="__('KB Rating')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                            <x-text-input wire:model="form.kb_rating" id="kb_rating" type="number"
                                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                          placeholder="0" />
                            <p class="text-slate-400 text-[10px] mt-1">Initial rating value.</p>
                            <x-input-error :messages="$errors->get('form.kb_rating')" class="mt-1.5 text-xs" />
                        </div>
                    </div>

                    {{-- Article Content --}}
                    <div>
                        <div class="mb-4">
                            <x-input-label for="article_content" :value="__('Article Content (Markdown / HTML)')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-2" />
                            
                            @if ($showAiButton)
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 mb-4 space-y-3 animate-fade-in">
                                    <div>
                                        <x-input-label for="aiPrompt" :value="__('AI Instruction Prompt')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1" />
                                        <input type="text" wire:model="aiPrompt" id="aiPrompt"
                                               class="block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                               placeholder="e.g. Please rewrite this kb article to be SEO optimized and concise" />
                                        <p class="text-slate-400 text-[10px] mt-1.5 leading-relaxed">
                                            The 'Generate with OPENAI' button will send your prompt and what you have provided in the KB article content to OpenAI and return AI-generated content.
                                        </p>
                                        <x-input-error :messages="$errors->get('ai_content_error')" class="mt-2 text-xs" />
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" wire:click="generateAiContent" wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 border border-transparent rounded-lg transition-all shadow-sm">
                                            <span wire:loading.remove wire:target="generateAiContent" class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                Generate with OPENAI
                                            </span>
                                            <span wire:loading wire:target="generateAiContent" class="flex items-center gap-1.5">
                                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Processing...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if (!empty($aiResponse))
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 mb-4 space-y-2 animate-fade-in"
                                 x-data="{
                                     copyToEditor() {
                                         let content = @js($aiResponse);
                                         let editor = tinymce.get('article_content');
                                         if (editor) {
                                             editor.setContent(content);
                                             editor.triggerSave();
                                         }
                                         $wire.set('form.article_content', content);
                                     }
                                 }">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        AI Suggested Content
                                    </span>
                                    <button type="button" @click="copyToEditor()" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors border border-indigo-150 shadow-sm">
                                        Copy to Editor
                                    </button>
                                </div>
                                <textarea readonly rows="6" class="block w-full rounded-xl border-slate-200 bg-white text-sm text-slate-600 shadow-sm focus:ring-0 focus:border-slate-200">{{ $aiResponse }}</textarea>
                            </div>
                        @endif

                        <div wire:ignore x-data x-init="
                            tinymce.init({
                                selector: '#article_content',
                                license_key: 'gpl',
                                promotion: false,
                                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px } .prose, .prose-slate { max-width: none !important; } ul { list-style-type: disc !important; padding-left: 1.5rem !important; margin-bottom: 1rem; } ol { list-style-type: decimal !important; padding-left: 1.5rem !important; margin-bottom: 1rem; } li { margin-bottom: 0.5rem; }',
                                plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons help',
                                toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                                    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                                    'forecolor backcolor emoticons | help',
                                setup: function (editor) {
                                    editor.on('change', function () {
                                        $wire.set('form.article_content', editor.getContent());
                                    });
                                }
                            });
                        ">
                            <textarea wire:model="form.article_content" id="article_content" rows="12"
                                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono shadow-sm"
                                      placeholder="Write article content here... (HTML tags and markdown format are fully supported)"></textarea>
                        </div>
                        <x-input-error :messages="$errors->get('form.article_content')" class="mt-1.5 text-xs" />
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <a href="{{ route('admin.kb.index') }}" wire:navigate
                           class="text-sm text-slate-500 hover:text-slate-700 font-medium transition-colors">
                            Cancel
                        </a>
                        <x-primary-button wire:loading.attr="disabled"
                                          class="rounded-xl px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500">
                            <span wire:loading.remove wire:target="save">Publish Article</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Publishing...
                            </span>
                        </x-primary-button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
</div>
