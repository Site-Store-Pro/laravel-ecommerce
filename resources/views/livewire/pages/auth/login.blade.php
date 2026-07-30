<?php

use App\Livewire\Forms\LoginForm;
use App\Services\RecaptchaService;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    public LoginForm $form;

    public string $recaptchaToken = '';

    /**
     * Handle an incoming authentication request.
     */
    public function login(RecaptchaService $recaptcha): void
    {
        $this->validate();

        if (! $recaptcha->verify($this->recaptchaToken, 'login')) {
            $this->addError('form.email', 'Security check failed. Please try again.');
            return;
        }

        $this->form->authenticate();

        Session::regenerate();

        // Guest users are auto-logged in by LoginForm and a redirect flash key is set.
        // Send them to set-password rather than the dashboard.
        if (Session::has('guest_redirect')) {
            $this->redirect(Session::pull('guest_redirect'), navigate: false);
            return;
        }

        $this->redirectIntended(default: route('dashboard'), navigate: true);
    }
}; ?>

<div class="py-12 sm:py-16 px-4 flex flex-col items-center justify-center min-h-[65vh]">
    <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
    <!-- Page heading -->
    <div class="mb-7">
        <h1 class="text-xl font-bold text-slate-900">@label('auth.login_heading', 'Welcome back')</h1>
        <p class="text-sm text-slate-500 mt-1">@label('auth.login_message', 'Sign in to your account to continue')</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-5 rounded-lg bg-indigo-50 border border-indigo-200 px-4 py-3 text-sm text-indigo-700">
            {{ session('status') }}
        </div>
    @endif

    <form
        x-data="{
            submitForm() {
                if (typeof grecaptcha === 'undefined' || !window.recaptchaSiteKey) {
                    $wire.login(); return;
                }
                grecaptcha.ready(() => {
                    grecaptcha.execute(window.recaptchaSiteKey, { action: 'login' }).then(token => {
                        $wire.recaptchaToken = token;
                        $wire.login();
                    });
                });
            }
        }"
        @submit.prevent="submitForm"
    >
        <input type="hidden" wire:model="recaptchaToken">

        <!-- Email Address -->
        <div>
            <label for="email" class="auth-label">@label('auth.email', 'Email Address')</label>

            <input wire:model="form.email"
                   id="email"
                   type="email"
                   name="email"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="@label('auth.email_placeholder', 'you@example.com')"
                   class="auth-input block w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none" />

             @error('form.email')
                <div class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-800 shadow-sm">
                    <span class="text-red-500">⚠</span>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="auth-label" style="margin-bottom:0;">@label('auth.password', 'Password')</label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link text-xs">
                        @label('auth.forgot_password', 'Forgot password?')
                    </a>
                @endif
            </div>

            <input wire:model="form.password"
                   id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="••••••••"
                   class="auth-input block w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none" />

            @error('form.password')
                <div class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-800 shadow-sm">
                    <span class="text-red-500">⚠</span>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-2.5">
            <input wire:model="form.remember"
                   id="remember"
                   type="checkbox"
                   name="remember"
                   class="checkbox-custom rounded">

            <label for="remember" class="text-sm text-slate-500 cursor-pointer select-none">
                @label('auth.remember_me', 'Keep me signed in')
            </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="auth-btn w-full mt-2">
            <span wire:loading.remove wire:target="login">@label('auth.sign_in', 'Sign In')</span>

            <span wire:loading wire:target="login" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                @label('auth.signing_in', 'Signing in…')
            </span>
        </button>
    </form>
@php
    $enabledProviders = array_filter([
        'google' => config('services.google.client_id'),
        'facebook' => config('services.facebook.client_id'),
        'github' => config('services.github.client_id'),
    ]);
    $cols = count($enabledProviders);
@endphp

@if($cols > 0)
    <!-- Divider -->
    <div class="auth-divider my-6" style="padding: 15px 0;">@label('auth.or_continue_with', 'or continue with')</div>

    <!-- Social Buttons -->
    <div class="grid {{ $cols === 3 ? 'grid-cols-3' : ($cols === 2 ? 'grid-cols-2' : 'grid-cols-1') }} gap-3">
        @if(config('services.google.client_id'))
        <a href="{{ route('social.redirect', 'google') }}" class="social-btn">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Google
        </a>
        @endif
        @if(config('services.facebook.client_id'))
        <a href="{{ route('social.redirect', 'facebook') }}" class="social-btn">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="#1877F2">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Facebook
        </a>
        @endif
        @if(config('services.github.client_id'))
        <a href="{{ route('social.redirect', 'github') }}" class="social-btn">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.9-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0012 2z"/>
            </svg>
            GitHub
        </a>
        @endif
    </div>
@endif
    <!-- Register link -->
    <p class="auth-footer">
        @label('auth.no_account', 'Don\'t have an account?')
        <a href="{{ route('register') }}" class="auth-link font-medium ml-1">@label('auth.create_one', 'Create one free')</a>
    </p>
    </div>
</div>