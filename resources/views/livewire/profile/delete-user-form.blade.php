<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $user = Auth::user();
        if ($user && ($user->isAdmin() || $user->isOrderProcessor())) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'password' => [__('Administrators and Order Processors cannot delete their own accounts.')],
            ]);
        }

        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap($user, $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
            @label('profile.delete_account', 'Delete Account')
        </h2>

        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    @if (Auth::user()?->isAdmin() || Auth::user()?->isOrderProcessor())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border-l-4 border-rose-500 dark:border-rose-400 text-rose-700 dark:text-rose-300 text-sm font-semibold rounded-r-xl">
            {{ __('Administrators and Order Processors are not permitted to delete their own accounts.') }}
        </div>
    @else
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >@label('profile.delete_account', 'Delete Account')</x-danger-button>
    @endif

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">

            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                @label('profile.delete_confirm_heading', 'Are you sure you want to delete your account?')
            </h2>

            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" class="sr-only">@label('auth.password', 'Password')</x-input-label>

                <x-text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ siteLabel('auth.password', 'Password') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    @label('profile.cancel', 'Cancel')
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    @label('profile.delete_account', 'Delete Account')
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
