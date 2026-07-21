<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderRefund;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminEcommerceDashboard extends Component
{
    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403, 'Unauthorized e-commerce admin access.');
    }

    public function render(): View
    {
        $totalSales = OrderPayment::where('payment_status', 1)->sum('payment_amount');
        $totalOrdersCount = Order::count();
        $refundedCount = OrderRefund::count();
        $refundedAmount = OrderPayment::where('payment_status', 2)->sum('payment_amount'); // or sum refund amount

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('livewire.admin-ecommerce-dashboard', [
            'totalSales' => $totalSales,
            'totalOrdersCount' => $totalOrdersCount,
            'refundedCount' => $refundedCount,
            'refundedAmount' => $refundedAmount,
            'recentOrders' => $recentOrders,
        ]);
    }
}
