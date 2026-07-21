<?php

namespace App\Livewire\TeamMember;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Tickets extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $tickets = Ticket::query()
            ->where('assigned_to', auth()->id())
            ->with(['user', 'assignee'])
            ->withCount('replies')
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->when($this->search, function ($query) {
                $query->where(function ($inner) {
                    $inner->where('title', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', fn ($userQuery) => $userQuery
                            ->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('email', 'like', '%'.$this->search.'%'));
                });
            })
            ->latest()
            ->paginate(2);

        $counts = Ticket::query()
            ->where('assigned_to', auth()->id())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        return view('livewire.team_member.tickets', [
            'tickets' => $tickets,
            'statuses' => TicketStatus::cases(),
            'counts' => $counts,
        ]);
    }
}
