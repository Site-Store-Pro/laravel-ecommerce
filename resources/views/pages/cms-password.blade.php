<x-public-layout>
    @section('title', 'Page Locked - ' . $page->title)

    <div class="min-h-[60vh] flex items-center justify-center px-6 py-12">
        <div class="max-w-md w-full text-center space-y-5">

            {{-- Card --}}
            <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-xl shadow-slate-100/50">

                {{-- Lock Icon --}}
                <div class="mx-auto w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <h1 class="text-2xl font-extrabold text-slate-900 mb-2">@label('cms.access_required', 'Access Required')</h1>

                @if($dualGate)
                    {{-- Dual gate — explain both options --}}
                    <p class="text-slate-500 text-sm mb-5 leading-relaxed">
                        The page <span class="font-semibold text-slate-800">"{{ $page->title }}"</span> is restricted.<br>
                        You can unlock it by entering the access code below, or by logging into your account if you have already purchased the required product.
                    </p>

                    {{-- "OR login" alert --}}
                    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 mb-5 text-left">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z"/>
                        </svg>
                        <p class="text-sm text-amber-800 leading-snug">
                            <strong>@label('cms.already_purchased', 'Already purchased this content?')</strong>
                            @label('cms.login_for_access', 'Log in to your account and you will be granted access automatically.')
                        </p>
                    </div>
                @else
                    <p class="text-slate-500 text-sm mb-5 leading-relaxed">
                        The page <span class="font-semibold text-slate-800">"{{ $page->title }}"</span> is restricted. Please enter the required access code to view its content.
                    </p>
                @endif

                {{-- Access code form with reCAPTCHA v3 --}}
                <form action="{{ route('page.unlock', $page->id) }}" method="POST"
                      x-data="{
                          submitForm(form) {
                              if (typeof grecaptcha === 'undefined' || !window.recaptchaSiteKey) {
                                  form.submit();
                                  return;
                              }
                              grecaptcha.ready(() => {
                                  grecaptcha.execute(window.recaptchaSiteKey, { action: 'page_unlock' })
                                      .then(token => {
                                          form.querySelector('[name=_recaptcha_token]').value = token;
                                          form.submit();
                                      });
                              });
                          }
                      }"
                      @submit.prevent="submitForm($el)"
                      class="space-y-4">
                    @csrf

                    {{-- reCAPTCHA v3 token (injected by JS before submit) --}}
                    <input type="hidden" name="_recaptcha_token" value="">

                    <div>
                        <label for="code" class="sr-only">@label('cms.access_code', 'Access Code')</label>
                        <input type="password" name="code" id="code" required placeholder="@label('cms.access_code_placeholder', 'Enter access code')"
                               class="w-full px-5 py-3 border border-slate-200 rounded-2xl text-center text-lg tracking-widest font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:tracking-normal placeholder:font-normal" />

                        @error('code')
                            <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-6 py-3 rounded-2xl shadow-md shadow-indigo-100 hover:shadow-lg hover:shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 018 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                        @label('cms.unlock_content', 'Unlock Content')
                    </button>
                </form>

                @if($dualGate)
                    {{-- Divider --}}
                    <div class="relative my-5">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-100"></div>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="bg-white px-3 text-slate-400 font-semibold tracking-wider">@label('cms.or', 'or')</span>
                        </div>
                    </div>

                    {{-- Login button --}}
                    <a href="{{ route('login') }}"
                       class="w-full flex items-center justify-center gap-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold px-6 py-3 rounded-2xl transition-all text-sm">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        @label('cms.login_to_verify', 'Log in to verify purchase')
                    </a>
                @endif

            </div>{{-- /card --}}

        </div>
    </div>
</x-public-layout>
