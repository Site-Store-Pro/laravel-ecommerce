<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    public string $email = '';

    public function mount(): void
    {
        if (! session()->has('social_registration')) {
            $this->redirect(route('login'), navigate: true);
        }
    }

    public function submit(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ]);

        $socialData = session('social_registration');
        if (! $socialData) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        // Create the user, leaving email_verified_at as null so they must verify
        $user = User::create([
            'name' => $socialData['name'],
            'email' => $this->email,
            'password' => Hash::make(Str::random(32)),
            'provider' => $socialData['provider'],
            'provider_id' => $socialData['provider_id'],
        ]);

        event(new Registered($user));

        Auth::login($user, remember: true);

        session()->forget('social_registration');

        // Associate unassigned cart items with newly registered social user
        \App\Services\CartSessionService::associateCartOnLogin($user->id);

        $isCustomerRole = in_array((int) ($user->role_id instanceof \App\Enums\UserRole ? $user->role_id->value : $user->role_id), [1, 2], true);

        if ($isCustomerRole && \App\Services\CartSessionService::getCartCount() > 0) {
            $this->redirect(route('shop.checkout'), navigate: true);
            return;
        }

        // Redirect to the dashboard; the 'verified' middleware will guide them to the verification page
        $this->redirect(route('dashboard'), navigate: true);
    }
}; ?>

<div class="py-12 sm:py-16 px-4 flex flex-col items-center justify-center min-h-[65vh]">
    <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
    <!-- Page heading -->
    <div class="mb-7">
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">@label('auth.collect_email_heading', 'Provide your email address')</h1>
        <p class="text-sm text-slate-400 mt-1">@label('auth.collect_email_message', 'We couldn\'t retrieve an email from your provider. Please enter one to complete your registration.')</p>
    </div>

    <form wire:submit="submit">
        <!-- Email Address -->
        <div class="mb-5">
            <label for="email" class="auth-label">@label('auth.email', 'Email Address')</label>
            <input wire:model="email"
                   id="email"
                   type="email"
                   required
                   autofocus
                   placeholder="@label('auth.email_placeholder', 'you@example.com')"
                   class="auth-input block w-full rounded-lg px-4 py-2.5 text-sm focus:outline-none" />

             @error('email')
                <div class="mt-2 rounded-lg border-2 border-red-500 bg-black px-3 py-2 text-sm font-semibold text-white shadow-lg">
                    <span class="text-red-400">⚠</span>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="auth-btn w-full">
            <span wire:loading.remove wire:target="submit">@label('auth.complete_registration', 'Complete Registration')</span>
            <span wire:loading wire:target="submit" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                @label('auth.saving_email', 'Saving email…')
            </span>
        </button>
    </form>

    <!-- Footer / Cancel -->
    <p class="auth-footer mt-6">
        <a href="{{ route('login') }}" class="auth-link font-medium" wire:navigate>@label('auth.cancel_go_back', 'Cancel and go back')</a>
    </p>
    </div>
</div>
