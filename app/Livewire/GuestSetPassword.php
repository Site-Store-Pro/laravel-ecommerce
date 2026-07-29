<?php

namespace App\Livewire;

use App\Livewire\Actions\Logout;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.public')]
class GuestSetPassword extends Component
{
    #[Rule('required|string|min:8|confirmed')]
    public string $password = '';

    #[Rule('required|string')]
    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();

        if (!$user) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        // Non-guest users (registered / social) don't need this page.
        if (!$user->isGuest()) {
            $this->redirect(route('dashboard'), navigate: true);
            return;
        }

        // Security gate: the guest must have clicked the verification link in their email
        // BEFORE they are allowed to set a password. This ensures only the real inbox owner
        // can convert the account — guessing the email alone is not enough.
        if (!$user->hasVerifiedEmail()) {
            // Re-set the intended URL so the verification link still lands here.
            session(['url.intended' => route('guest.set-password')]);
            $this->redirect(route('verification.notice'), navigate: false);
        }
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();

        // Race-condition guard: still a guest AND has verified email.
        if (!$user->isGuest()) {
            $this->redirect(route('dashboard'), navigate: true);
            return;
        }
        if (!$user->hasVerifiedEmail()) {
            session(['url.intended' => route('guest.set-password')]);
            $this->redirect(route('verification.notice'), navigate: false);
            return;
        }

        // Set the hashed password — this also removes the [GUEST-USER] sentinel.
        $user->password = Hash::make($this->password);
        $user->save();

        // Redirect to dashboard — the account is now fully converted.
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: false);
    }

    public function render(): View
    {
        return view('livewire.guest-set-password');
    }
}
