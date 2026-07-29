<?php

use App\Models\User;
use App\Services\RecaptchaService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $recaptchaToken = '';

    public function register(RecaptchaService $recaptcha): void
    {
        $this->resetErrorBag();

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class
            ],

            'password' => [
                'required',
                'string',
                'confirmed',
                Rules\Password::defaults()
            ],
        ]);

        if (! $recaptcha->verify($this->recaptchaToken, 'register')) {
            $this->addError(
                'email',
                'Security check failed. Please try again.'
            );

            return;
        }

        $validated['password'] = Hash::make(
            $validated['password']
        );

        event(
            new Registered(
                $user = User::create($validated)
            )
        );

        Auth::login($user);

        $this->redirect(
            route('dashboard'),
            navigate: true
        );
    }
};
?>

<div>

    <div class="mb-7">
        <h1 class="text-xl font-bold text-slate-900">
            @label('auth.register_heading', 'Create your account')
        </h1>
    </div>


    <form
        novalidate
        x-data="{
            submitForm() {

                if (
                    typeof grecaptcha === 'undefined'
                    || !window.recaptchaSiteKey
                ) {
                    $wire.register()
                    return
                }

                grecaptcha.ready(() => {

                    grecaptcha.execute(
                        window.recaptchaSiteKey,
                        { action: 'register' }
                    )
                    .then(token => {

                        $wire.set(
                            'recaptchaToken',
                            token
                        )

                        $wire.register()

                    })

                })

            }
        }"
        @submit.prevent="submitForm"
    >


        <input
            type="hidden"
            wire:model.live="recaptchaToken"
        >


        <!-- Name -->
        <div class="mb-4">

            <label for="name" class="auth-label">
                @label('auth.full_name', 'Full Name')
            </label>


            <input
                wire:model.live="name"
                id="name"
                type="text"
                autocomplete="name"
                placeholder="@label('auth.full_name_placeholder', 'Jane Smith')"
                class="auth-input block w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none"
            />


         @error('name')
    <div class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-800 shadow-sm">
        <span class="text-red-500">⚠</span>
        {{ $message }}
    </div>
@enderror

        </div>



        <!-- Email -->
        <div class="mb-4">

            <label for="email" class="auth-label">
                @label('auth.email', 'Email Address')
            </label>


            <input
                wire:model.live="email"
                id="email"
                type="email"
                autocomplete="username"
                placeholder="@label('auth.email_placeholder', 'you@example.com')"
                class="auth-input block w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none"
            />


          @error('email')
    <div class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-800 shadow-sm">
        <span class="text-red-500">⚠</span>
        {{ $message }}
    </div>
@enderror

        </div>



        <!-- Password -->
        <div class="mb-4">

            <label for="password" class="auth-label">
                @label('auth.password', 'Password')
            </label>


            <input
                wire:model.live="password"
                id="password"
                type="password"
                autocomplete="new-password"
                placeholder="@label('auth.password_placeholder', 'Strong Password Required (min 8 char)')"
                class="auth-input block w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none"
            />


        @error('password')
    <div class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-800 shadow-sm">
        <span class="text-red-500">⚠</span>
        {{ $message }}
    </div>
@enderror
        </div>



        <!-- Confirm Password -->
        <div class="mb-5">

            <label for="password_confirmation" class="auth-label">
                @label('auth.confirm_password', 'Confirm Password')
            </label>


            <input
                wire:model.live="password_confirmation"
                id="password_confirmation"
                type="password"
                autocomplete="new-password"
                placeholder="@label('auth.repeat_password', 'Repeat your password')"
                class="auth-input block w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none"
            />


          @error('password_confirmation')
    <div class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-800 shadow-sm">
        <span class="text-red-500">⚠</span>
        {{ $message }}
    </div>
@enderror
        </div>



        <!-- Submit -->
        <button type="submit" wire:loading.attr="disabled" class="auth-btn w-full mt-2">

            <span wire:loading.remove wire:target="register">
                @label('auth.create_account', 'Create Account')
            </span>


            <span wire:loading wire:target="register" class="flex items-center justify-center gap-2">

                <svg class="animate-spin h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24">

                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4">
                    </circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                    </path>

                </svg>

                @label('auth.creating_account', 'Creating account…')

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
    <div class="auth-divider my-6" style="padding: 15px 0;">@label('auth.or_register_with', 'or register with')</div>

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


    <p class="auth-footer">

        @label('auth.have_account', 'Already have an account?')

        <a href="{{ route('login') }}" class="auth-link font-medium ml-1">

            @label('auth.sign_in_link', 'Sign in')

        </a>

    </p>


</div>