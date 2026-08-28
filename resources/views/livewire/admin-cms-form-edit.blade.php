<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.cms-forms.index') }}" wire:navigate
           class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ $formId ? 'Edit Form' : 'New Form' }}
            </h1>
            @if($formId)
                <p class="text-xs text-slate-400 font-mono mt-0.5">Shortcode: [cms-form id={{ $formId }}]</p>
            @endif
        </div>
    </div>

    {{-- Status flash --}}
    @if(session()->has('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 text-sm font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-8 items-start">

        {{-- ── LEFT: Form Settings ─────────────────────────────────────────── --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Basic Settings --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">Form Settings</h2>

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Form Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="name" placeholder="e.g. Contact Us"
                           class="w-full px-4 py-2.5 bg-slate-50 border @error('name') border-red-400 @else border-slate-200 @enderror rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Slug <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.blur="slug" placeholder="contact-us"
                           class="w-full px-4 py-2.5 bg-slate-50 border @error('slug') border-red-400 @else border-slate-200 @enderror rounded-2xl text-sm font-mono text-slate-800 focus:outline-none focus:border-indigo-400">
                    @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Submit Button Label --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Submit Button Label <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.blur="submit_button_label" placeholder="Submit"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                </div>

                {{-- Active --}}
                <div class="flex items-center gap-3">
                    <button type="button" wire:click="$toggle('is_active')"
                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors duration-200 {{ $is_active ? 'bg-indigo-600' : 'bg-slate-200' }}">
                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $is_active ? 'translate-x-4.5' : 'translate-x-0.5' }}"></span>
                    </button>
                    <span class="text-sm font-semibold text-slate-700">{{ $is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>

            {{-- Confirmation & Redirect --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">After Submission</h2>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Confirmation Message</label>
                    <p class="text-[10px] text-slate-400 mb-2">Displayed on the page after a successful submit.</p>
                    <textarea wire:model.blur="confirmation_message" rows="3"
                              placeholder="Thank you! Your submission has been received."
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Redirect URL <span class="text-slate-400 font-normal">(optional)</span></label>
                    <p class="text-[10px] text-slate-400 mb-2">If set, user is redirected here after submitting.</p>
                    <input type="url" wire:model.blur="redirect_url" placeholder="https://example.com/thank-you"
                           class="w-full px-4 py-2.5 bg-slate-50 border @error('redirect_url') border-red-400 @else border-slate-200 @enderror rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                    @error('redirect_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Email Notification --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">Email Notification</h2>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Send To Email <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="email" wire:model.blur="email_to" placeholder="admin@support.local"
                           class="w-full px-4 py-2.5 bg-slate-50 border @error('email_to') border-red-400 @else border-slate-200 @enderror rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                    @error('email_to') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Subject <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="text" wire:model.blur="email_subject" placeholder="New form submission: {{ $name ?: 'Form Name' }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                </div>
            </div>

            {{-- Auto Opt-in --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">Mailing List Opt-in</h2>

                <div class="flex items-start gap-3">
                    <button type="button" wire:click="$toggle('auto_optin')"
                            class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 items-center rounded-full transition-colors duration-200 {{ $auto_optin ? 'bg-indigo-600' : 'bg-slate-200' }}">
                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $auto_optin ? 'translate-x-4.5' : 'translate-x-0.5' }}"></span>
                    </button>
                    <div>
                        <span class="text-sm font-semibold text-slate-700">Auto Opt-in</span>
                        <p class="text-[10px] text-slate-400 mt-0.5 leading-relaxed">When enabled, the submitter's email (and name, if tagged) are automatically forwarded to the configured mailing-list provider without requiring an opt-in checkbox on the form. Mark which input field holds the email and name using the <strong>Field Role</strong> selector on each field below.</p>
                    </div>
                </div>

                @if($auto_optin)
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Provider</label>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['mailchimp' => 'Mailchimp', 'constant_contact' => 'Constant Contact', 'klaviyo' => 'Klaviyo'] as $pval => $plbl)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="optin_provider" value="{{ $pval }}" class="sr-only">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border transition duration-150
                                        {{ $optin_provider === $pval ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-indigo-300' }}">
                                        {{ $plbl }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">List / Audience ID <span class="text-red-500">*</span></label>
                        <p class="text-[10px] text-slate-400 mb-2">The list or audience ID from your provider's dashboard (not the API key).</p>
                        <input type="text" wire:model.blur="optin_list_id" placeholder="e.g. abc123def456"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-mono text-slate-800 focus:outline-none focus:border-indigo-400">
                    </div>

                    <div class="p-3 bg-amber-50 border border-amber-100 rounded-2xl text-xs text-amber-800 leading-relaxed">
                        <strong>Provider API keys</strong> are configured in your <code class="bg-amber-100 px-1 rounded">.env</code> file:
                        @if($optin_provider === 'mailchimp')
                            <code class="block mt-1 text-[10px]">MAILCHIMP_API_KEY=&hellip;</code>
                            <code class="block text-[10px]">MAILCHIMP_SERVER_PREFIX=us1</code>
                        @elseif($optin_provider === 'constant_contact')
                            <code class="block mt-1 text-[10px]">CONSTANT_CONTACT_API_KEY=&hellip;</code>
                        @elseif($optin_provider === 'klaviyo')
                            <code class="block mt-1 text-[10px]">KLAVIYO_API_KEY=&hellip;</code>
                        @else
                            Select a provider above to see the required key.
                        @endif
                    </div>
                @endif
            </div>

            {{-- Custom CSS --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-3">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">Custom CSS <span class="text-slate-400 font-normal text-xs">(optional)</span></h2>
                <p class="text-[10px] text-slate-400">Scoped to this form's container. Use <code class="bg-slate-100 px-1 rounded">.cms-form-wrap</code> as the root selector.</p>
                <textarea wire:model.blur="custom_css" rows="6"
                          placeholder=".cms-form-wrap { background: #f9fafb; padding: 2rem; border-radius: 1rem; }"
                          class="w-full px-4 py-3 bg-slate-900 text-emerald-300 font-mono text-xs border border-slate-700 rounded-2xl focus:outline-none focus:border-indigo-500 resize-y"></textarea>
            </div>

        </div>

        {{-- ── RIGHT: Field Builder ────────────────────────────────────────── --}}
        <div class="xl:col-span-3 space-y-4">

            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Form Fields</h2>
                <button type="button" wire:click="addField"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-2xl shadow transition duration-150">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Field
                </button>
            </div>

            @if(empty($fields))
                <div class="bg-white rounded-3xl border border-dashed border-slate-200 p-12 text-center">
                    <div class="text-slate-300 mb-3">
                        <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm font-semibold">No fields yet</p>
                    <p class="text-slate-400 text-xs mt-1">Click "Add Field" to start building your form.</p>
                </div>
            @endif

            @foreach($fields as $i => $field)
                @php
                    $isOpen    = ($editingFieldIndex === $i);
                    $typeIcons = [
                        'input'           => 'M4 6h16M4 12h8m-8 6h16',
                        'textarea'        => 'M4 6h16M4 10h16M4 14h10',
                        'select'          => 'M19 9l-7 7-7-7',
                        'radio'           => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'checkbox'        => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'checkbox_group'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                    ];
                    $typePath  = $typeIcons[$field['type']] ?? $typeIcons['input'];
                    $typeLabel = match($field['type']) {
                        'input'          => 'Text Input',
                        'textarea'       => 'Textarea',
                        'select'         => 'Dropdown',
                        'radio'          => 'Radio Group',
                        'checkbox'       => 'Checkbox',
                        'checkbox_group' => 'Checkbox Group',
                        default          => ucfirst($field['type']),
                    };
                @endphp
                <div class="bg-white rounded-3xl border {{ $isOpen ? 'border-indigo-200 shadow-md' : 'border-slate-100 shadow-sm' }} overflow-hidden transition-all duration-200">

                    {{-- Field header --}}
                    <div class="flex items-center gap-3 px-5 py-4 cursor-pointer select-none"
                         wire:click="toggleEditField({{ $i }})">
                        {{-- Type icon --}}
                        <div class="h-8 w-8 flex items-center justify-center rounded-xl {{ $isOpen ? 'bg-indigo-100' : 'bg-slate-100' }} shrink-0">
                            <svg class="w-4 h-4 {{ $isOpen ? 'text-indigo-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typePath }}"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-800 text-sm truncate">
                                {{ $field['label'] ?: '(Untitled field)' }}
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $typeLabel }}</span>
                                @if($field['is_required'])
                                    <span class="text-[9px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-md">Required</span>
                                @endif
                            </div>
                        </div>
                        {{-- Move + remove buttons --}}
                        <div class="flex items-center gap-1 shrink-0" x-on:click.stop>
                            <button type="button" wire:click="moveFieldUp({{ $i }})" @disabled($i === 0)
                                    class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg disabled:opacity-30 transition duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                </svg>
                            </button>
                            <button type="button" wire:click="moveFieldDown({{ $i }})" @disabled($i === count($fields) - 1)
                                    class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg disabled:opacity-30 transition duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <button type="button"
                                    wire:click="removeField({{ $i }})"
                                    wire:confirm="Remove this field?"
                                    class="p-1.5 text-red-400 hover:text-red-700 hover:bg-red-50 rounded-lg transition duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        {{-- Chevron --}}
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 {{ $isOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    {{-- Field body (expanded) --}}
                    @if($isOpen)
                        <div class="border-t border-slate-100 px-5 py-5 space-y-5">

                            {{-- Type selector --}}
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-2">Field Type</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(['input' => 'Text Input', 'textarea' => 'Textarea', 'select' => 'Dropdown', 'radio' => 'Radio Group', 'checkbox' => 'Checkbox', 'checkbox_group' => 'Checkbox Group'] as $val => $lbl)
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model.live="fields.{{ $i }}.type" value="{{ $val }}" class="sr-only">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border transition duration-150
                                                {{ $field['type'] === $val ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-indigo-300' }}">
                                                {{ $lbl }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Label --}}
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1.5">Label <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.blur="fields.{{ $i }}.label" placeholder="e.g. Your Name"
                                       class="w-full px-4 py-2.5 bg-slate-50 border @error('fields.'.$i.'.label') border-red-400 @else border-slate-200 @enderror rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                @error('fields.'.$i.'.label') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Instructions --}}
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1.5">Instructions <span class="text-slate-400 font-normal">(small text below label)</span></label>
                                <input type="text" wire:model.blur="fields.{{ $i }}.instructions" placeholder="e.g. Enter your full legal name"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                            </div>

                            {{-- HTML Above --}}
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1.5">HTML Above Field <span class="text-slate-400 font-normal">(optional — rendered above the field)</span></label>
                                @php $editorId = 'field_html_above_' . $i; @endphp
                                <div wire:ignore>
                                    <textarea id="{{ $editorId }}"
                                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400"
                                              rows="4">{{ $field['html_above'] }}</textarea>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        initFieldEditor('{{ $editorId }}', {{ $i }});
                                    });
                                    // If already loaded (Livewire re-render), init immediately
                                    if (typeof tinymce !== 'undefined') {
                                        initFieldEditor('{{ $editorId }}', {{ $i }});
                                    }
                                </script>
                            </div>

                            {{-- Options (for select / radio / checkbox_group) --}}
                            @if(in_array($field['type'], ['select', 'radio', 'checkbox_group']))
                                <div>
                                    <label class="text-xs font-bold text-slate-700 block mb-2">Options</label>
                                    <div class="space-y-2">
                                        @foreach(($field['options'] ?? []) as $oi => $opt)
                                            <div class="flex items-center gap-2">
                                                <input type="text"
                                                       wire:model.blur="fields.{{ $i }}.options.{{ $oi }}"
                                                       placeholder="Option {{ $oi + 1 }}"
                                                       class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                                <button type="button" wire:click="removeOption({{ $i }}, {{ $oi }})"
                                                        class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-150">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" wire:click="addOption({{ $i }})"
                                            class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition duration-150">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add Option
                                    </button>
                                </div>
                            @endif

                            {{-- Field Role (for opt-in) --}}
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-2">Field Role <span class="text-slate-400 font-normal">(for mailing list opt-in)</span></label>
                                <div class="flex gap-2 flex-wrap">
                                    @foreach(['' => 'None', 'name' => 'Subscriber Name', 'email' => 'Subscriber Email'] as $rval => $rlbl)
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model.live="fields.{{ $i }}.field_role" value="{{ $rval }}" class="sr-only">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border transition duration-150
                                                {{ ($field['field_role'] ?? '') === $rval ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-indigo-300' }}">
                                                {{ $rlbl }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5">Tag a field so the auto opt-in feature knows which value to forward to the mailing-list provider. Only one field per form should carry each role.</p>
                            </div>

                            {{-- Required settings --}}
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
                                <div class="flex items-center gap-3">
                                    <button type="button" wire:click="$set('fields.{{ $i }}.is_required', {{ $field['is_required'] ? 'false' : 'true' }})"
                                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors duration-200 {{ $field['is_required'] ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $field['is_required'] ? 'translate-x-4.5' : 'translate-x-0.5' }}"></span>
                                    </button>
                                    <span class="text-sm font-semibold text-slate-700">Required</span>
                                </div>

                                @if($field['is_required'])
                                    <div>
                                        <label class="text-xs font-bold text-slate-700 block mb-2">Validation Rule</label>
                                        <div class="flex gap-2 flex-wrap">
                                            @foreach(['non_blank' => 'Not Empty', 'email' => 'Valid Email', 'numeric' => 'Numeric Only'] as $rval => $rlbl)
                                                <label class="cursor-pointer">
                                                    <input type="radio" wire:model.live="fields.{{ $i }}.required_type" value="{{ $rval }}" class="sr-only">
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border transition duration-150
                                                        {{ ($field['required_type'] ?? 'non_blank') === $rval ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-200 hover:border-indigo-300' }}">
                                                        {{ $rlbl }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-700 block mb-1.5">Custom Error Message <span class="text-slate-400 font-normal">(optional)</span></label>
                                        <input type="text" wire:model.blur="fields.{{ $i }}.required_error_message"
                                               placeholder="This field is required."
                                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endif
                </div>
            @endforeach

            {{-- Save --}}
            <div class="flex items-center justify-between pt-4">
                @if($formId)
                    <a href="{{ route('admin.cms-forms.submissions', $formId) }}" wire:navigate
                       class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 transition duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        View Submissions
                    </a>
                @else
                    <div></div>
                @endif
                <button wire:click="save" wire:loading.attr="disabled"
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-md transition duration-150 flex items-center gap-2">
                    <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    <span wire:loading.remove wire:target="save">Save Form</span>
                    <span wire:loading wire:target="save">Saving…</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- TinyMCE for html_above fields --}}
<script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
<script>
    window.initFieldEditor = function(editorId, fieldIndex) {
        // Destroy any existing instance first
        if (tinymce.get(editorId)) {
            tinymce.get(editorId).remove();
        }
        tinymce.init({
            selector: '#' + editorId,
            license_key: 'gpl',
            height: 180,
            menubar: false,
            plugins: 'link lists image code',
            toolbar: 'bold italic underline | bullist numlist | link | code',
            promotion: false,
            branding: false,
            setup: function(editor) {
                editor.on('change blur', function() {
                    // Sync content back to Livewire
                    @this.setFieldHtmlAbove(fieldIndex, editor.getContent());
                });
            }
        });
    };
</script>
