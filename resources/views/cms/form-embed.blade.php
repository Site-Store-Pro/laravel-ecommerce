{{--
    CMS Form Embed — rendered by ShortcodeProcessor when [cms-form id=N] is found.
    Uses Alpine.js for state management and fetch() for AJAX submission.
    No Livewire dependency — works inside any page.
--}}
@php
    $formUid          = 'cms-form-' . $form->id . '-' . Str::random(6);
    $recaptchaSiteKey = config('services.recaptcha.site_key', '');
@endphp

{{-- Scoped custom CSS --}}
@if($form->custom_css)
    <style>
        #{{ $formUid }} .cms-form-wrap {
            {{ $form->custom_css }}
        }
    </style>
@endif

<div id="{{ $formUid }}"
     x-data="cmsFormEmbed_{{ $form->id }}('{{ $form->slug }}', '{{ $form->id }}')"
     x-init="init()">

    <div class="cms-form-wrap">

        {{-- ── Form ───────────────────────────────────────────────────────── --}}
        <form x-show="!submitted" x-cloak @submit.prevent="submitForm()" class="cms-embed-form space-y-6" novalidate>

            @foreach($form->fields->sortBy('sort_order') as $field)
                @php $fid = (string) $field->id; @endphp
                <div class="cms-form-field" id="field-wrap-{{ $formUid }}-{{ $fid }}">

                    {{-- HTML above field --}}
                    @if($field->html_above)
                        <div class="cms-field-html-above mb-2">{!! $field->html_above !!}</div>
                    @endif

                    {{-- Label --}}
                    <label class="cms-field-label block text-sm font-semibold text-slate-800 mb-1"
                           for="field-{{ $formUid }}-{{ $fid }}">
                        {{ $field->label }}
                        @if($field->is_required)
                            <span class="text-red-500 ml-0.5">*</span>
                        @endif
                    </label>

                    {{-- Instructions --}}
                    @if($field->instructions)
                        <p class="cms-field-instructions text-xs text-slate-500 mb-2">{{ $field->instructions }}</p>
                    @endif

                    {{-- ── Input ── --}}
                    @if($field->type === 'input')
                        <input type="text"
                               id="field-{{ $formUid }}-{{ $fid }}"
                               x-model="values['{{ $fid }}']"
                               :class="errors['{{ $fid }}'] ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50'"
                               class="cms-field-input w-full px-4 py-2.5 border rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 transition duration-150">

                    {{-- ── Textarea ── --}}
                    @elseif($field->type === 'textarea')
                        <textarea id="field-{{ $formUid }}-{{ $fid }}"
                                  x-model="values['{{ $fid }}']"
                                  rows="4"
                                  :class="errors['{{ $fid }}'] ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50'"
                                  class="cms-field-textarea w-full px-4 py-2.5 border rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 transition duration-150 resize-y"></textarea>

                    {{-- ── Select / Dropdown ── --}}
                    @elseif($field->type === 'select')
                        <select id="field-{{ $formUid }}-{{ $fid }}"
                                x-model="values['{{ $fid }}']"
                                :class="errors['{{ $fid }}'] ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50'"
                                class="cms-field-select w-full px-4 py-2.5 border rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 transition duration-150 cursor-pointer">
                            <option value="">— Select an option —</option>
                            @foreach($field->options ?? [] as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>

                    {{-- ── Radio Group ── --}}
                    @elseif($field->type === 'radio')
                        <div class="cms-field-radio-group space-y-2" role="radiogroup">
                            @foreach($field->options ?? [] as $opt)
                                <label class="cms-field-radio-label flex items-center gap-3 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-300 transition duration-150 text-sm text-slate-700">
                                    <input type="radio"
                                           name="field-{{ $formUid }}-{{ $fid }}"
                                           value="{{ $opt }}"
                                           x-model="values['{{ $fid }}']"
                                           class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <span>{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>

                    {{-- ── Single Checkbox ── --}}
                    @elseif($field->type === 'checkbox')
                        <label class="cms-field-checkbox-label flex items-center gap-3 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-300 transition duration-150 text-sm text-slate-700">
                            <input type="checkbox"
                                   id="field-{{ $formUid }}-{{ $fid }}"
                                   x-model="values['{{ $fid }}']"
                                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                            <span>{{ $field->label }}</span>
                        </label>

                    {{-- ── Checkbox Group ── --}}
                    @elseif($field->type === 'checkbox_group')
                        <div class="cms-field-checkbox-group space-y-2">
                            @foreach($field->options ?? [] as $oi => $opt)
                                <label class="cms-field-checkbox-label flex items-center gap-3 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-300 transition duration-150 text-sm text-slate-700">
                                    <input type="checkbox"
                                           value="{{ $opt }}"
                                           x-model="values['{{ $fid }}']"
                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <span>{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    {{-- Per-field error message --}}
                    <p x-show="errors['{{ $fid }}']"
                       x-text="errors['{{ $fid }}']"
                       x-cloak
                       class="cms-field-error mt-1.5 text-xs text-red-600 font-semibold"></p>

                </div>
            @endforeach

            {{-- General error (network/server) --}}
            <div x-show="generalError" x-cloak
                 class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-700 text-sm font-semibold flex items-start gap-2">
                <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-text="generalError"></span>
            </div>

            {{-- Submit button --}}
            <div>
                <button type="submit"
                        :disabled="submitting"
                        class="cms-form-submit inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white font-bold rounded-2xl shadow-md transition duration-150">
                    <svg x-show="submitting" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    <span x-text="submitting ? 'Submitting…' : '{{ addslashes($form->submit_button_label) }}'"></span>
                </button>
            </div>
        </form>

        {{-- ── Confirmation message ────────────────────────────────────────── --}}
        <div x-show="submitted" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="cms-form-confirmation p-6 bg-emerald-50 border border-emerald-100 rounded-3xl text-emerald-800">
            @if($form->confirmation_message)
                {!! $form->confirmation_message !!}
            @else
                <p class="font-semibold">Thank you! Your submission has been received.</p>
            @endif
        </div>

    </div>
</div>

<script>
function cmsFormEmbed_{{ $form->id }}(slug, formId) {
    return {
        slug,
        formId,
        values: {},
        errors: {},
        generalError: '',
        submitting: false,
        submitted: false,
        recaptchaToken: '',

        init() {
            // Initialise checkbox_group values as arrays
            @foreach($form->fields->where('type', 'checkbox_group') as $field)
            this.values['{{ $field->id }}'] = [];
            @endforeach
        },

        async getRecaptchaToken() {
            @if($recaptchaSiteKey)
            return new Promise((resolve) => {
                if (typeof grecaptcha === 'undefined' || !window.recaptchaSiteKey) {
                    resolve('');
                    return;
                }
                grecaptcha.ready(() => {
                    grecaptcha.execute(window.recaptchaSiteKey, { action: 'cms_form' })
                        .then(token => resolve(token))
                        .catch(() => resolve(''));
                });
            });
            @else
            return Promise.resolve('');
            @endif
        },

        async submitForm() {
            this.submitting   = true;
            this.errors       = {};  
            this.generalError = '';

            // Obtain a fresh reCAPTCHA v3 token right before submitting
            const recaptchaToken = await this.getRecaptchaToken();

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const response  = await fetch(`/forms/${this.slug}/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'Accept':        'application/json',
                        'X-CSRF-TOKEN':  csrfToken,
                    },
                    body: JSON.stringify({
                        values:          this.values,
                        recaptcha_token: recaptchaToken,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    this.submitted = true;
                    @if($form->redirect_url)
                    setTimeout(() => { window.location.href = '{{ $form->redirect_url }}'; }, 2000);
                    @endif
                } else if (response.status === 422 && data.errors) {
                    // Surface the reCAPTCHA error as a general (non-field) error
                    if (data.errors['_recaptcha']) {
                        this.generalError = data.errors['_recaptcha'];
                    } else {
                        this.errors = data.errors;
                    }
                } else {
                    this.generalError = data.error ?? 'Something went wrong. Please try again.';
                }
            } catch (e) {
                this.generalError = 'Network error. Please check your connection and try again.';
            }

            this.submitting = false;
        }
    };
}
</script>

@if($recaptchaSiteKey)
{{-- Load reCAPTCHA v3 API if not already loaded by the page layout --}}
<script>
    (function() {
        // Expose site key for getRecaptchaToken() — safe to set multiple times
        window.recaptchaSiteKey = window.recaptchaSiteKey || '{{ $recaptchaSiteKey }}';

        // Only inject the script tag once per page, even if multiple forms are embedded
        if (typeof grecaptcha === 'undefined' && !document.querySelector('script[src*="recaptcha/api.js"]')) {
            var s = document.createElement('script');
            s.src = 'https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}';
            s.async = true;
            document.head.appendChild(s);
        }
    })();
</script>
@endif
