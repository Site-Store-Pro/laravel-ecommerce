<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\HasDateRangeFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class ReportCustomerSpend extends Component
{
    use HasDateRangeFilters;

    public string $viewMode = 'highest'; // 'highest' or 'lowest'

    public function mount(): void
    {
        $this->initializeHasDateRangeFilters();
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
    }

    public function render(): View
    {
        [$start, $end] = $this->getRangeDates();

        $query = DB::table('users')
            ->join('orders', 'orders.order_user_id', '=', 'users.id')
            ->whereBetween('orders.order_date', [$start, $end])
            ->whereIn('users.role_id', [\App\Enums\UserRole::User->value, \App\Enums\UserRole::Wholesale->value]) // Customers only
            ->select('users.id', 'users.name', 'users.email', DB::raw('count(orders.id) as orders_count'), DB::raw('sum(orders.order_total) as total_spend'))
            ->groupBy('users.id', 'users.name', 'users.email');

        if ($this->viewMode === 'highest') {
            $customers = $query->orderBy('total_spend', 'desc')->take(5)->get();
        } else {
            $customers = $query->orderBy('total_spend', 'asc')->take(5)->get();
        }

        return view('livewire.report-customer-spend', [
            'customers' => $customers,
        ]);
    }
}
