<div class="py-12 sm:py-16 px-4 flex flex-col items-center justify-center min-h-[65vh]">
    <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
        
        <!-- Header Icon & Titles -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 mb-4 border border-indigo-100 dark:border-indigo-900/50 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                @if($context === 'checkout')
                    @label('two_factor.checkout_heading', 'Verify Your Purchase')
                @else
                    @label('two_factor.login_heading', 'Two-Factor Verification')
                @endif
            </h1>
            
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                @label('two_factor.subheading', 'We sent a 6-digit security verification code to')
                <strong class="font-semibold text-slate-700 dark:text-slate-200 block sm:inline mt-0.5 sm:mt-0">{{ $email }}</strong>
            </p>
        </div>

        <!-- Success Resend Notification -->
        @if($resendMessage)
            <div class="rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/60 p-4 text-sm text-emerald-800 dark:text-emerald-300 flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ $resendMessage }}</span>
            </div>
        @endif

        <!-- Error Notification -->
        @if($errorMessage && !$errors->has('code'))
            <div class="rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/60 p-4 text-sm text-rose-800 dark:text-rose-300 flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ $errorMessage }}</span>
            </div>
        @endif

        <!-- Verification Form -->
        <form wire:submit.prevent="verify" class="space-y-5">
            <div>
                <label for="code" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 text-center">
                    @label('two_factor.code_label', 'Enter 6-Digit Code')
                </label>
                
                <div class="relative max-w-[240px] mx-auto">
                    <input wire:model="code"
                           id="code"
                           type="text"
                           inputmode="numeric"
                           pattern="[0-9]*"
                           maxlength="6"
                           autofocus
                           autocomplete="one-time-code"
                           placeholder="••••••"
                           class="w-full text-center text-2xl font-bold tracking-[0.4em] py-3 px-4 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-300 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-inner" />
                </div>

                @error('code')
                    <div class="mt-2 text-center text-xs font-semibold text-rose-600 dark:text-rose-400">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-3 px-4 rounded-xl font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 dark:bg-indigo-600 dark:hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md shadow-indigo-600/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 cursor-pointer">
                <span wire:loading.remove wire:target="verify">
                    @if($context === 'checkout')
                        @label('two_factor.continue_checkout_btn', 'Verify & Continue to Payment →')
                    @else
                        @label('two_factor.verify_login_btn', 'Verify & Sign In →')
                    @endif
                </span>

                <span wire:loading wire:target="verify" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span>@label('two_factor.verifying', 'Verifying code…')</span>
                </span>
            </button>
        </form>

        <!-- Resend Code Actions with Alpine Timer -->
        <div x-data="{
                cooldown: @entangle('cooldownSeconds'),
                timer: null,
                init() {
                    this.startCountdown();
                    this.$watch('cooldown', (val) => {
                        if (val > 0 && !this.timer) {
                            this.startCountdown();
                        }
                    });
                },
                startCountdown() {
                    if (this.timer) clearInterval(this.timer);
                    if (this.cooldown > 0) {
                        this.timer = setInterval(() => {
                            if (this.cooldown > 0) {
                                this.cooldown--;
                            } else {
                                clearInterval(this.timer);
                                this.timer = null;
                            }
                        }, 1000);
                    }
                }
             }"
             class="pt-4 border-t border-slate-100 dark:border-slate-700/80 text-center space-y-3">
            
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    @label('two_factor.did_not_receive', 'Didn\'t receive the code?')
                </p>

                <template x-if="cooldown > 0">
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 mt-1 inline-block">
                        @label('two_factor.resend_in', 'Resend code in') <span x-text="cooldown" class="tabular-nums font-mono text-indigo-600 dark:text-indigo-400 font-bold"></span>s
                    </span>
                </template>

                <template x-if="cooldown <= 0">
                    <button type="button"
                            wire:click="resend"
                            wire:loading.attr="disabled"
                            class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 underline mt-1 transition cursor-pointer">
                        <span wire:loading.remove wire:target="resend">@label('two_factor.resend_button', 'Click here to resend code')</span>
                        <span wire:loading wire:target="resend">@label('two_factor.sending_code', 'Sending new code…')</span>
                    </button>
                </template>
            </div>

            <!-- Cancel / Back Link -->
            <div class="pt-2">
                @if($context === 'checkout')
                    <a href="{{ route('shop.checkout') }}" class="text-xs text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition inline-flex items-center gap-1">
                        ← @label('two_factor.return_to_checkout', 'Return to Checkout')
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition inline-flex items-center gap-1">
                        ← @label('two_factor.return_to_login', 'Back to Sign In')
                    </a>
                @endif
            </div>
        </div>

    </div>
</div>
