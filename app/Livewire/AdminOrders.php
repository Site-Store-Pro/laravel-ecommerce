<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminOrders extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403, 'Unauthorized staff access.');
    }

    public function render(): View
    {
        $orders = Order::with(['user', 'details', 'statusList'])
            ->where(function($query) {
                if ($this->search) {
                    $query->where('order_invoice_no', 'like', '%' . $this->search . '%')
                          ->orWhereHas('user', function($q) {
                              $q->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                          });
                }
            })
            ->latest()
            ->paginate(25);

        return view('livewire.admin-orders', [
            'orders' => $orders,
        ]);
    }
}
