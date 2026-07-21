<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminUsers extends Component
{
    use WithPagination;

    public string $search = '';

    public string $roleFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($inner) {
                    $inner->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, fn ($query) => $query->where('role_id', $this->roleFilter))
            ->withCount(['tickets', 'orders'])
            ->latest()
            ->paginate(25);

        $roleCountsRaw = User::query()
            ->selectRaw('role_id, count(*) as count')
            ->groupBy('role_id')
            ->pluck('count', 'role_id')
            ->all();

        $roleCounts = [
            'user'            => $roleCountsRaw[UserRole::User->value] ?? 0,
            'wholesale'       => $roleCountsRaw[UserRole::Wholesale->value] ?? 0,
            'admin'           => $roleCountsRaw[UserRole::Admin->value] ?? 0,
            'order_processor' => $roleCountsRaw[UserRole::OrderProcessor->value] ?? 0,
            'ticket_manager'  => $roleCountsRaw[UserRole::TicketManager->value] ?? 0,
        ];

        return view('livewire.admin-users', [
            'users'      => $users,
            'roles'      => UserRole::cases(),
            'roleCounts' => $roleCounts,
        ]);
    }
}
