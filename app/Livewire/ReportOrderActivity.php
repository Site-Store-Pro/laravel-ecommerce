<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\HasDateRangeFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class ReportOrderActivity extends Component
{
    use HasDateRangeFilters;

    public function mount(): void
    {
        $this->initializeHasDateRangeFilters();
    }

    public function render(): View
    {
        [$start, $end] = $this->getRangeDates();

        // Get daily order counts and revenue sums
        $activity = DB::table('orders')
            ->whereBetween('order_date', [$start, $end])
            ->selectRaw('DATE(order_date) as date, count(*) as count, sum(order_total) as revenue')
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('date', 'asc')
            ->get();

        // Fill in missing dates to prevent gaps in charts
        $startDateObj = Carbon::parse($start);
        $endDateObj = Carbon::parse($end);
        $daysDiff = $startDateObj->diffInDays($endDateObj);
        
        $chartData = [];
        $totalRevenue = 0.0;
        $totalOrders = 0;

        // Group activity by key
        $activityMap = [];
        foreach ($activity as $act) {
            $activityMap[$act->date] = [
                'count' => (int) $act->count,
                'revenue' => (float) $act->revenue,
            ];
            $totalRevenue += (float) $act->revenue;
            $totalOrders += (int) $act->count;
        }

        // Fill days sequentially
        // For larger date ranges, let's skip day-by-day and aggregate weekly if daysDiff > 90 to keep the chart clean, 
        // but since we render an SVG path, it can handle hundreds of points smoothly!
        for ($i = 0; $i <= $daysDiff; $i++) {
            $dateStr = $startDateObj->copy()->addDays($i)->toDateString();
            $val = $activityMap[$dateStr] ?? ['count' => 0, 'revenue' => 0.0];
            $chartData[] = [
                'label' => Carbon::parse($dateStr)->format('M d'),
                'count' => $val['count'],
                'revenue' => $val['revenue'],
            ];
        }

        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0.0;

        return view('livewire.report-order-activity', [
            'chartData' => $chartData,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'avgOrderValue' => $avgOrderValue,
        ]);
    }
}
