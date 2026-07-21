<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminUserShow extends Component
{
    public User $user;

    public function mount(User $user): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403, 'Unauthorized admin access.');
        $this->user = $user;
    }

    public function render(): View
    {
        $tickets = Ticket::where('user_id', $this->user->id)->latest()->get();
        $orders = Order::where('order_user_id', $this->user->id)->latest()->get();

        return view('livewire.admin-user-show', [
            'tickets' => $tickets,
            'orders' => $orders
        ]);
    }
}
