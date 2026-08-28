<?php

use App\Models\User;
use App\Services\RecaptchaService;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    public string $email = '';

    public string $recaptchaToken = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(RecaptchaService $recaptcha): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        if (! $recaptcha->verify($this->recaptchaToken, 'reset_password')) {
            $this->addError('email', 'Security check failed. Please try again.');
            return;
        }

        // Block password resets for social-provider accounts
        $user = User::where('email', $this->email)->first();

        if ($user && !empty($user->provider)) {
            session()->flash(
                'social_error',
                'This account uses ' . ucfirst($user->provider) . ' login. Please sign in using your social provider instead.'
            );

            return;
        }

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>
<div class="py-12 sm:py-16 px-4 flex flex-col items-center justify-center min-h-[65vh]">
    <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
    <!-- Page heading -->
    <div class="mb-7">
        <h1 class="text-xl font-bold text-slate-900">@label('auth.forgot_heading', 'Reset your password')</h1>
        <p class="text-sm text-slate-505 mt-1">
            @label('auth.forgot_message', 'Enter your email and we\'ll send you a secure reset link.')
        </p>
    </div>

    <!-- Social Login Alert -->
    @if (session('social_error'))
        <div class="mb-6 rounded-lg border px-4 py-3 text-sm flex items-center gap-2"
             style="background:#fef2f2; border-color:#fee2e2; color:#991b1b;">

            <svg class="w-4 h-4 shrink-0"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 9v2m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20h15.6a2 2 0 001.73-2.64l-7.82-13.5a2 2 0 00-3.42 0z"/>
            </svg>

            {{ session('social_error') }}
        </div>
    @endif


    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 rounded-lg border px-4 py-3 text-sm flex items-center gap-2"
             style="background:#ecfdf5; border-color:#d1fae5; color:#065f46;">
            <svg class="w-4 h-4 shrink-0"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>

            {{ session('status') }}
        </div>
    @endif


    <form
        x-data="{
            submitForm() {
                if (typeof grecaptcha === 'undefined' || !window.recaptchaSiteKey) {
                    $wire.sendPasswordResetLink();
                    return;
                }

                grecaptcha.ready(() => {
                    grecaptcha.execute(window.recaptchaSiteKey, { action: 'reset_password' })
                        .then(token => {
                            $wire.recaptchaToken = token;
                            $wire.sendPasswordResetLink();
                        });
                });
            }
        }"
        @submit.prevent="submitForm"
    >

        <input type="hidden" wire:model="recaptchaToken">

        <div>
            <label for="email" class="auth-label">@label('auth.email', 'Email Address')</label>

            <input wire:model="email"
                   id="email"
                   type="email"
                   name="email"
                   required
                   autofocus
                   autocomplete="email"
                   placeholder="@label('auth.email_placeholder', 'you@example.com')"
                   class="auth-input block w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none" />

            @error('email')
                <p class="mt-1.5 text-xs text-rose-600 font-semibold">
                    {{ $message }}
                </p>
            @enderror
        </div>


        <button type="submit" class="auth-btn primary-btn btn-theme-primary w-full">

            <span wire:loading.remove wire:target="sendPasswordResetLink">
                @label('auth.send_reset', 'Send Reset Link')
            </span>

            <span wire:loading wire:target="sendPasswordResetLink"
                  class="flex items-center justify-center gap-2">

                <svg class="animate-spin h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24">

                    <circle class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4">
                    </circle>

                    <path class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                    </path>

                </svg>

                @label('auth.sending', 'Sending…')
            </span>

        </button>

    </form>


    <p class="auth-footer">
        @label('auth.remember_password', 'Remember your password?')
        <a href="{{ route('login') }}"
           class="auth-link font-medium ml-1">
            @label('auth.sign_in_link', 'Sign in')
        </a>
    </p>

    </div>
</div>