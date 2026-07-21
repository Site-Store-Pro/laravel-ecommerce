<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminPendingOrders extends Component
{
    use WithPagination;

    public function render(): View
    {
        $pendingOrders = Order::with('user')
            ->whereIn('order_status', [1, 5, 6, 10])
            ->latest()
            ->paginate(25);

        return view('livewire.admin-pending-orders', [
            'pendingOrders' => $pendingOrders,
        ]);
    }
}
