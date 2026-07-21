<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Livewire\Forms\UserForm;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminCreateUser extends Component
{
    public UserForm $form;

    public function save(): void
    {
        $user = $this->form->store();

        session()->flash('status', "User {$user->name} created successfully.");

        $this->redirectRoute('admin.users', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin-create-user', [
            'roles' => UserRole::cases(),
        ]);
    }
}
