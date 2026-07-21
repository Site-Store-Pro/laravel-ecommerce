<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\HasDateRangeFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class ReportCartConversion extends Component
{
    use HasDateRangeFilters;

    public function mount(): void
    {
        $this->initializeHasDateRangeFilters();
    }

    public function render(): View
    {
        [$start, $end] = $this->getRangeDates();

        // 1. Total Distinct Cart Sessions
        $totalSessions = DB::table('shopping_cart_log')
            ->whereBetween('created_at', [$start, $end])
            ->where('cart_log_session', '!=', '')
            ->distinct('cart_log_session')
            ->count('cart_log_session');

        // 2. Abandoned Carts (Guest)
        $abandonedGuest = DB::table('shopping_cart_log')
            ->whereBetween('created_at', [$start, $end])
            ->where('order_id', 0)
            ->where('user_id', 0)
            ->where('cart_log_session', '!=', '')
            ->distinct('cart_log_session')
            ->count('cart_log_session');

        // 3. Abandoned Carts (Registered User)
        $abandonedRegistered = DB::table('shopping_cart_log')
            ->whereBetween('created_at', [$start, $end])
            ->where('order_id', 0)
            ->where('user_id', '>', 0)
            ->where('cart_log_session', '!=', '')
            ->distinct('cart_log_session')
            ->count('cart_log_session');

        // 4. Completed Carts
        $completedCarts = DB::table('orders')
            ->whereBetween('order_date', [$start, $end])
            ->count();

        // Adjust total sessions to include completed orders that didn't record a cart session ID in database
        $overallCartSessions = $totalSessions + $completedCarts;

        $abandonedTotal = $abandonedGuest + $abandonedRegistered;
        $conversionRate = $overallCartSessions > 0 ? round(($completedCarts / $overallCartSessions) * 100, 1) : 0;
        $abandonedRate = $overallCartSessions > 0 ? round(($abandonedTotal / $overallCartSessions) * 100, 1) : 0;

        return view('livewire.report-cart-conversion', [
            'overallCartSessions' => $overallCartSessions,
            'abandonedGuest' => $abandonedGuest,
            'abandonedRegistered' => $abandonedRegistered,
            'abandonedTotal' => $abandonedTotal,
            'completedCarts' => $completedCarts,
            'conversionRate' => $conversionRate,
            'abandonedRate' => $abandonedRate,
        ]);
    }
}
