<?php

namespace App\Livewire;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminDashboard extends Component
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

    public function deleteTicket(int $ticketId): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $ticket = Ticket::findOrFail($ticketId);
        $ticket->delete();

        session()->flash('status', 'Ticket #' . $ticketId . ' deleted successfully.');
    }

    public function render(): View
    {
        $tickets = Ticket::query()
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
            ->paginate(25);

        $counts = Ticket::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $unassignedCount = Ticket::query()
            ->whereNull('assigned_to')
            ->where('status', '!=', 'closed')
            ->count();

        return view('livewire.admin-dashboard', [
            'tickets' => $tickets,
            'statuses' => TicketStatus::cases(),
            'counts' => $counts,
            'unassignedCount' => $unassignedCount,
        ]);
    }
}
