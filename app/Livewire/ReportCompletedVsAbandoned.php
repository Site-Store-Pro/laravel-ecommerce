<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\HasDateRangeFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class ReportCompletedVsAbandoned extends Component
{
    use HasDateRangeFilters;

    public function mount(): void
    {
        $this->initializeHasDateRangeFilters();
    }

    public function render(): View
    {
        [$start, $end] = $this->getRangeDates();

        // 1. Completed Orders Count
        $completedCount = DB::table('orders')
            ->whereBetween('order_date', [$start, $end])
            ->count();

        // 2. Abandoned Carts Count
        $abandonedCount = DB::table('shopping_cart_log')
            ->whereBetween('created_at', [$start, $end])
            ->where('order_id', 0)
            ->where('cart_log_session', '!=', '')
            ->distinct('cart_log_session')
            ->count('cart_log_session');

        $totalCarts = $completedCount + $abandonedCount;
        $conversionRate = $totalCarts > 0 ? round(($completedCount / $totalCarts) * 100, 1) : 0;

        return view('livewire.report-completed-vs-abandoned', [
            'completedCount' => $completedCount,
            'abandonedCount' => $abandonedCount,
            'totalCarts' => $totalCarts,
            'conversionRate' => $conversionRate,
        ]);
    }
}
