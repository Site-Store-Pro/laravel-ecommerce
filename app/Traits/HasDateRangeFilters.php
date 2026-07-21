<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasDateRangeFilters
{
    public string $dateRange = '30'; // '30', '60', '90', '120', 'YTD', 'custom'
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function initializeHasDateRangeFilters(): void
    {
        $this->applyRange();
    }

    public function setRange(string $range): void
    {
        $this->dateRange = $range;
        $this->applyRange();
    }

    protected function applyRange(): void
    {
        if ($this->dateRange === 'YTD') {
            $this->startDate = Carbon::now()->startOfYear()->toDateString();
            $this->endDate = Carbon::now()->toDateString();
        } elseif ($this->dateRange !== 'custom') {
            $days = (int) $this->dateRange;
            $this->startDate = Carbon::now()->subDays($days)->toDateString();
            $this->endDate = Carbon::now()->toDateString();
        }
    }

    protected function getRangeDates(): array
    {
        $start = Carbon::parse($this->startDate ?? Carbon::now()->subDays(30)->toDateString())->startOfDay();
        $end = Carbon::parse($this->endDate ?? Carbon::now()->toDateString())->endOfDay();
        return [$start, $end];
    }
}
