<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class AdminDashboardHome extends Component
{
    public bool $confirmingDemoPurge = false;

    public function mount(): void
    {
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrderProcessor()), 403, 'Unauthorized dashboard access.');
    }

    public function getHasDemoContentProperty(): bool
    {
        return \App\Services\DemoPurgeService::hasDemoContent();
    }

    public function purgeDemoContent(): void
    {
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->role_id == 3), 403);

        \App\Services\DemoPurgeService::purgeDemoContent();

        $this->confirmingDemoPurge = false;
        $this->dispatch('toast', message: 'All demo store content has been permanently deleted.', type: 'success');
    }

    public function render(): View
    {
        // Calculate high-level summary metrics
        $totalSales = DB::table('order_payments')
            ->where('payment_status', 1)
            ->sum('payment_amount');

        $totalOrdersCount = DB::table('orders')->count();

        $pendingOrdersCount = DB::table('orders')
            ->whereIn('order_status', [1, 5, 6, 10])
            ->count();

        $customersCount = DB::table('users')
            ->whereIn('role_id', [\App\Enums\UserRole::User->value, \App\Enums\UserRole::Wholesale->value])
            ->count();

        $recentOrders = \App\Models\Order::with('user')
            ->withCount('details')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.admin-dashboard-home', [
            'totalSales' => $totalSales,
            'totalOrdersCount' => $totalOrdersCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'customersCount' => $customersCount,
            'recentOrders' => $recentOrders,
        ]);
    }
}
