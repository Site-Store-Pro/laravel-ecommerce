<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\HasDateRangeFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class ReportProductPerformance extends Component
{
    use HasDateRangeFilters;

    public string $performanceMode = 'highest'; // 'highest' or 'lowest'

    public function mount(): void
    {
        $this->initializeHasDateRangeFilters();
    }

    public function setPerformanceMode(string $mode): void
    {
        $this->performanceMode = $mode;
    }

    public function render(): View
    {
        [$start, $end] = $this->getRangeDates();

        $query = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('products', 'products.title', '=', 'order_details.item_name')
            ->whereBetween('orders.order_date', [$start, $end])
            ->select('order_details.item_name', 'products.id as product_id', DB::raw('sum(order_details.item_qty) as total_qty'), DB::raw('sum(order_details.item_qty * order_details.final_price) as total_revenue'))
            ->groupBy('order_details.item_name', 'products.id');

        if ($this->performanceMode === 'highest') {
            $products = $query->orderBy('total_qty', 'desc')->take(5)->get();
        } else {
            $products = $query->orderBy('total_qty', 'asc')->take(5)->get();
        }

        return view('livewire.report-product-performance', [
            'products' => $products,
        ]);
    }
}
