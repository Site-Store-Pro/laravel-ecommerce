<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.cms-forms.index') }}" wire:navigate
           class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ $formId ? 'Edit Form: ' . $name : 'New Form' }}
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

    {{-- Top Navigation Tabs (when form is saved) --}}
    @if($formId)
        <div class="flex items-center gap-2 mb-8 border-b border-slate-200 dark:border-slate-700">
            <button type="button" wire:click="$set('activeTab', 'form')"
                    class="px-5 py-3 text-sm font-bold border-b-2 transition flex items-center gap-2 {{ $activeTab === 'form' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Form Builder & Settings
            </button>

            <button type="button" wire:click="$set('activeTab', 'translations')"
                    class="px-5 py-3 text-sm font-bold border-b-2 transition flex items-center gap-2 {{ $activeTab === 'translations' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                Form Translations
                @if($activeLanguages->isNotEmpty())
                    <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold">
                        {{ $activeLanguages->count() }} Languages
                    </span>
                @endif
            </button>
        </div>
    @endif

    {{-- ═════════════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 1: FORM BUILDER & SETTINGS                                            --}}
    {{-- ═════════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'form')
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
                        'checkbox_group'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                    ];
                    $typePath  = $typeIcons[$field['type']] ?? $typeIcons['input'];
                    $typeLabel = match($field['type']) {
                        'input'          => 'Text Input',
                        'textarea'       => 'Textarea',
                        'select'         => 'Dropdown',
                        'radio'          => 'Radio Group',
                        'checkbox'       => 'Checkbox',
                        'checkbox_group' => 'Checkbox Group',
                        default          => 'Input',
                    };
                @endphp

                <div wire:key="field-{{ $i }}"
                     class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition duration-150">

                    {{-- Field row header --}}
                    <div class="flex items-center gap-3 p-4 bg-slate-50/50 cursor-pointer select-none"
                         wire:click="toggleEditField({{ $i }})">

                        {{-- Type icon badge --}}
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typePath }}"/>
                            </svg>
                        </div>

                        {{-- Label preview --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-sm truncate">
                                    {{ $field['label'] ?: '(Untitled field)' }}
                                </span>
                                @if($field['is_required'])
                                    <span class="text-xs text-red-500 font-bold">*</span>
                                @endif
                                @if(!empty($field['field_role']))
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-indigo-100 text-indigo-700">
                                        {{ ucfirst($field['field_role']) }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs text-slate-400">{{ $typeLabel }}</span>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex items-center gap-1 shrink-0" wire:click.stop>
                            {{-- Move Up --}}
                            <button type="button" wire:click="moveFieldUp({{ $i }})"
                                    @if($i === 0) disabled @endif
                                    class="p-1.5 text-slate-400 hover:text-slate-700 disabled:opacity-30 disabled:cursor-not-allowed rounded-lg hover:bg-slate-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                </svg>
                            </button>

                            {{-- Move Down --}}
                            <button type="button" wire:click="moveFieldDown({{ $i }})"
                                    @if($i === count($fields) - 1) disabled @endif
                                    class="p-1.5 text-slate-400 hover:text-slate-700 disabled:opacity-30 disabled:cursor-not-allowed rounded-lg hover:bg-slate-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Delete --}}
                            <button type="button" wire:click="removeField({{ $i }})"
                                    wire:confirm="Remove this field?"
                                    class="p-1.5 text-red-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>

                            {{-- Toggle chevron --}}
                            <div class="p-1.5 text-slate-400 cursor-pointer" wire:click="toggleEditField({{ $i }})">
                                <svg class="w-4 h-4 transition-transform duration-150 {{ $isOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Expanded field editor --}}
                    @if($isOpen)
                        <div class="p-6 border-t border-slate-100 space-y-5 bg-white">

                            {{-- Row 1: Label + Type --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Field Label <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.live="fields.{{ $i }}.label" placeholder="e.g. Your Name"
                                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Field Type</label>
                                    <select wire:model.live="fields.{{ $i }}.type"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 cursor-pointer">
                                        <option value="input">Text Input</option>
                                        <option value="textarea">Textarea (Multi-line)</option>
                                        <option value="select">Dropdown (Select)</option>
                                        <option value="radio">Radio Buttons</option>
                                        <option value="checkbox">Single Checkbox</option>
                                        <option value="checkbox_group">Checkbox Group</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Row 2: Instructions --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Helper Instructions <span class="text-slate-400 font-normal">(optional)</span></label>
                                <input type="text" wire:model.blur="fields.{{ $i }}.instructions" placeholder="e.g. Please enter your full legal name"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                            </div>

                            {{-- Row 3: Required toggle + validation type + custom error message --}}
                            <div class="p-4 bg-slate-50 rounded-2xl space-y-4">
                                <div class="flex items-center gap-3">
                                    <button type="button" wire:click="$toggle('fields.{{ $i }}.is_required')"
                                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors duration-200 {{ ($field['is_required'] ?? false) ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform duration-200 {{ ($field['is_required'] ?? false) ? 'translate-x-4.5' : 'translate-x-0.5' }}"></span>
                                    </button>
                                    <span class="text-xs font-bold text-slate-700">Required field</span>
                                </div>

                                @if($field['is_required'] ?? false)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-200">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Validation Rule</label>
                                            <select wire:model.blur="fields.{{ $i }}.required_type"
                                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-400">
                                                <option value="non_blank">Non-blank (any value)</option>
                                                <option value="email">Valid Email Address</option>
                                                <option value="numeric">Numeric only</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Custom Error Message <span class="text-slate-400 font-normal">(optional)</span></label>
                                            <input type="text" wire:model.blur="fields.{{ $i }}.required_error_message"
                                                   placeholder="e.g. Please enter a valid email address."
                                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-400">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Row 4: Field Role (for auto opt-in mapping) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Field Role <span class="text-slate-400 font-normal">(for mailing list opt-in)</span></label>
                                <select wire:model.blur="fields.{{ $i }}.field_role"
                                        class="w-full sm:w-64 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-400">
                                    <option value="">None (Standard Field)</option>
                                    <option value="name">Name Field (maps to Subscriber Name)</option>
                                    <option value="email">Email Field (maps to Subscriber Email)</option>
                                </select>
                            </div>

                            {{-- Row 5: Options editor (for select, radio, checkbox_group) --}}
                            @if(in_array($field['type'], ['select', 'radio', 'checkbox_group']))
                                <div class="p-4 bg-slate-50 rounded-2xl space-y-3">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-bold text-slate-700">Choice Options</label>
                                        <button type="button" wire:click="addOption({{ $i }})"
                                                class="inline-flex items-center gap-1 px-3 py-1 bg-white border border-slate-200 text-indigo-600 text-xs font-bold rounded-xl shadow-xs hover:border-indigo-300 transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Option
                                        </button>
                                    </div>

                                    @if(empty($field['options']))
                                        <p class="text-xs text-slate-400 italic">No options added yet. Click "Add Option" above.</p>
                                    @endif

                                    <div class="space-y-2">
                                        @foreach($field['options'] ?? [] as $oi => $opt)
                                            <div wire:key="opt-{{ $i }}-{{ $oi }}" class="flex items-center gap-2">
                                                <input type="text"
                                                       wire:model.blur="fields.{{ $i }}.options.{{ $oi }}"
                                                       placeholder="Option {{ $oi + 1 }}"
                                                       class="flex-1 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-400">
                                                <button type="button" wire:click="removeOption({{ $i }}, {{ $oi }})"
                                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Row 6: HTML Above Field (TinyMCE) --}}
                            <div wire:ignore>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    HTML Above Field <span class="text-slate-400 font-normal">(optional — headings, instructions, images)</span>
                                </label>
                                <textarea id="html-above-{{ $i }}"
                                          class="w-full">{!! $field['html_above'] ?? '' !!}</textarea>
                            </div>

                        </div>
                    @endif

                </div>
            @endforeach

            {{-- Save floating/bottom bar --}}
            <div class="pt-4 flex justify-end">
                <button type="button" wire:click="save" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white font-bold rounded-2xl shadow-md transition duration-150">
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
    @endif

    {{-- ═════════════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 2: FORM TRANSLATIONS                                                  --}}
    {{-- ═════════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'translations' && $formId)
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 space-y-6">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> CMS Form Translations
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Translate form title, submit button, confirmation message, and all field labels/options.</p>
                </div>

                @if($tlLangId > 0)
                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="aiTlForm" wire:loading.attr="disabled" wire:target="aiTlForm"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-violet-50 hover:bg-violet-100 text-violet-700 border border-violet-200 rounded-xl text-xs font-bold transition">
                            <span wire:loading wire:target="aiTlForm" class="animate-spin inline-block w-3.5 h-3.5 border-2 border-violet-400 border-t-transparent rounded-full"></span>
                            <svg class="w-4 h-4" wire:loading.remove wire:target="aiTlForm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            ✦ AI Translate All (OpenAI)
                        </button>

                        <button type="button" wire:click="saveTlForm" wire:loading.attr="disabled" wire:target="saveTlForm"
                                class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow transition">
                            <span wire:loading wire:target="saveTlForm" class="animate-spin inline-block w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full"></span>
                            Save Translations
                        </button>
                    </div>
                @endif
            </div>

            {{-- Language Pills --}}
            <div class="flex flex-wrap gap-2">
                @foreach($activeLanguages as $lang)
                    @php 
                        $tRecord = \App\Models\CmsFormTranslation::where('cms_form_id', $formId)->where('language_id', $lang->id)->first(); 
                    @endphp
                    <button type="button" wire:click="selectTlLang({{ $lang->id }})"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-bold border transition
                                   {{ $tlLangId === $lang->id ? 'bg-indigo-600 text-white border-indigo-600 shadow' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:bg-indigo-50' }}">
                        <span class="text-base">{{ $lang->flag_emoji }}</span>
                        {{ $lang->name }}
                        @if($tRecord)
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $tRecord->translation_status === 'reviewed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $tRecord->translation_status === 'reviewed' ? '✓' : 'AI' }}
                            </span>
                        @else
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-500">—</span>
                        @endif
                    </button>
                @endforeach
            </div>

            @if($tlLangId > 0)
                <div class="space-y-6 pt-2">
                    {{-- Status bar --}}
                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-xs font-bold text-slate-700">Status:</span>
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold
                            {{ $tlStatus === 'reviewed' ? 'bg-emerald-100 text-emerald-800' : ($tlStatus === 'ai_translated' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                            {{ $tlStatus === 'reviewed' ? 'Reviewed' : ($tlStatus === 'ai_translated' ? 'AI Translated' : 'Pending') }}
                        </span>
                        @if($tlTranslatedAt)
                            <span class="text-xs text-slate-400">Last updated: {{ $tlTranslatedAt }}</span>
                        @endif
                    </div>

                    {{-- Form Core Settings Translation --}}
                    <div class="p-6 bg-slate-50/50 rounded-3xl border border-slate-200 space-y-4">
                        <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Form Level Translations</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Form Name <span class="text-slate-400 font-normal">(Default: "{{ $name }}")</span>
                                </label>
                                <input type="text" wire:model.defer="tlFormBuffer.name" placeholder="Translated Form Name"
                                       class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Submit Button Label <span class="text-slate-400 font-normal">(Default: "{{ $submit_button_label }}")</span>
                                </label>
                                <input type="text" wire:model.defer="tlFormBuffer.submit_button_label" placeholder="e.g. Enviar Mensaje"
                                       class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Confirmation Message <span class="text-slate-400 font-normal">(Default: "{{ strip_tags($confirmation_message) }}")</span>
                            </label>
                            <textarea wire:model.defer="tlFormBuffer.confirmation_message" rows="3" placeholder="Translated confirmation message (HTML supported)..."
                                      class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400"></textarea>
                        </div>
                    </div>

                    {{-- Form Fields Translations --}}
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Form Field Translations</h3>

                        @foreach($fields as $i => $field)
                            @php $fid = $field['id']; @endphp
                            @if($fid)
                                <div wire:key="tl-field-{{ $fid }}" class="p-6 bg-white rounded-3xl border border-slate-200 shadow-xs space-y-4">
                                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">{{ $i + 1 }}</span>
                                            <span class="font-bold text-slate-800 text-sm">{{ $field['label'] }}</span>
                                            <span class="text-xs text-slate-400 font-mono">({{ $field['type'] }})</span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Field Label <span class="text-slate-400 font-normal">(Default: "{{ $field['label'] }}")</span>
                                            </label>
                                            <input type="text" wire:model.defer="tlFieldsBuffer.{{ $fid }}.label" placeholder="Translated Field Label"
                                                   class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Helper Instructions <span class="text-slate-400 font-normal">(Default: "{{ $field['instructions'] }}")</span>
                                            </label>
                                            <input type="text" wire:model.defer="tlFieldsBuffer.{{ $fid }}.instructions" placeholder="Translated Helper Instructions"
                                                   class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                        </div>
                                    </div>

                                    @if($field['is_required'])
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Validation Error Message <span class="text-slate-400 font-normal">(Default: "{{ $field['required_error_message'] ?: 'Required field' }}")</span>
                                            </label>
                                            <input type="text" wire:model.defer="tlFieldsBuffer.{{ $fid }}.required_error_message" placeholder="Translated Error Message"
                                                   class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                        </div>
                                    @endif

                                    @if(!empty($field['options']) && is_array($field['options']))
                                        <div class="p-4 bg-slate-50 rounded-2xl space-y-3">
                                            <label class="block text-xs font-bold text-slate-700">Choice Options Translations</label>
                                            <div class="space-y-2">
                                                @foreach($field['options'] as $oi => $opt)
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-xs text-slate-500 w-1/3 truncate font-medium">{{ $opt }}:</span>
                                                        <input type="text"
                                                               wire:model.defer="tlFieldsBuffer.{{ $fid }}.options.{{ $oi }}"
                                                               placeholder="Translated option choice"
                                                               class="flex-1 px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-400">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty(strip_tags($field['html_above'] ?? '')))
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">HTML Above Field (Translated)</label>
                                            <textarea wire:model.defer="tlFieldsBuffer.{{ $fid }}.html_above" rows="3"
                                                      class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:outline-none focus:border-indigo-400"></textarea>
                                        </div>
                                    @endif

                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Save Button at Bottom --}}
                    <div class="pt-4 flex justify-end">
                        <button type="button" wire:click="saveTlForm" wire:loading.attr="disabled" wire:target="saveTlForm"
                                class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow transition">
                            <span wire:loading wire:target="saveTlForm" class="animate-spin inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full"></span>
                            Save Translations
                        </button>
                    </div>

                </div>
            @else
                <div class="py-12 text-center text-slate-400 text-sm">
                    Select a language above to view, edit, or AI-translate this form.
                </div>
            @endif

        </div>
    @endif

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
