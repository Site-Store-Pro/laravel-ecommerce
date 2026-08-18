<?php

namespace App\Livewire;

use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Services\Payments\SubscriptionService;
use App\Traits\HasDateRangeFilters;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportSubscriptions extends Component
{
    use WithPagination, HasDateRangeFilters;

    public string $statusFilter = 'all'; // 'all', 'active', 'cancelled'
    public string $providerFilter = 'all'; // 'all', 'stripe', 'paddle', 'paypal'
    public string $search = '';
    public int $perPage = 15;
    public ?int $expandedDetailId = null;

    public function mount(): void
    {
        $this->initializeHasDateRangeFilters();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingProviderFilter(): void
    {
        $this->resetPage();
    }

    public function togglePayments(int $detailId): void
    {
        if ($this->expandedDetailId === $detailId) {
            $this->expandedDetailId = null;
        } else {
            $this->expandedDetailId = $detailId;
        }
    }

    public function cancelSubscription(int $orderDetailId, SubscriptionService $subscriptionService): void
    {
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrderProcessor()), 403);

        $detail = OrderDetail::with(['order', 'variant'])->find($orderDetailId);
        if (!$detail) {
            session()->flash('error', 'Subscription record not found.');
            return;
        }

        try {
            $subscriptionService->cancelSubscription($detail, 'Cancelled by admin staff from Subscriptions Report');
            session()->flash('status', 'Subscription has been cancelled successfully.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("ReportSubscriptions cancelSubscription error: " . $e->getMessage());
            session()->flash('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    protected function getBaseQuery(): Builder
    {
        [$start, $end] = $this->getRangeDates();

        return OrderDetail::query()
            ->with(['order.user', 'order.payments', 'variant.product'])
            ->where(function ($q) {
                $q->where('subscription', 1)
                  ->orWhere('active_subscription', 1)
                  ->orWhereNotNull('subscription_plan_id')
                  ->orWhereNotNull('subscription_provider');
            })
            ->whereHas('order', function ($q) use ($start, $end) {
                $q->whereBetween('order_date', [$start, $end]);
            })
            ->when($this->statusFilter === 'active', function ($q) {
                $q->where('active_subscription', 1);
            })
            ->when($this->statusFilter === 'cancelled', function ($q) {
                $q->where('active_subscription', 0);
            })
            ->when($this->providerFilter !== 'all', function ($q) {
                $q->where('subscription_provider', $this->providerFilter);
            })
            ->when(!empty(trim($this->search)), function ($q) {
                $term = '%' . trim($this->search) . '%';
                $q->where(function ($subQ) use ($term) {
                    $subQ->where('item_name', 'like', $term)
                         ->orWhere('subscription_plan_id', 'like', $term)
                         ->orWhereHas('order', function ($ordQ) use ($term) {
                             $ordQ->where('order_invoice_no', 'like', $term)
                                  ->orWhereHas('user', function ($userQ) use ($term) {
                                      $userQ->where('name', 'like', $term)
                                            ->orWhere('email', 'like', $term);
                                  });
                         });
                });
            })
            ->latest('id');
    }

    public function exportCsv(): StreamedResponse
    {
        $records = $this->getBaseQuery()->get();
        $filename = 'subscriptions_report_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Order ID',
                'Invoice No',
                'Order Date',
                'Customer Name',
                'Customer Email',
                'Product / Plan',
                'Billing Provider',
                'Subscription ID / Agreement',
                'Recurring Amount',
                'Active Status',
                'Total Payments Recorded',
                'Total Paid',
            ]);

            foreach ($records as $item) {
                $payments = $this->getPaymentsForItem($item);
                $totalPaid = $payments->sum('payment_amount');

                fputcsv($handle, [
                    $item->order_id,
                    $item->order?->order_invoice_no ?? 'N/A',
                    $item->order?->order_date ? $item->order->order_date->format('Y-m-d H:i:s') : 'N/A',
                    $item->order?->user?->name ?? 'Guest User',
                    $item->order?->user?->email ?? '-',
                    $item->item_name,
                    strtoupper($item->subscription_provider ?? 'N/A'),
                    $item->subscription_plan_id ?? '-',
                    number_format((float)$item->final_price, 2),
                    $item->active_subscription ? 'ACTIVE' : 'CANCELLED',
                    $payments->count(),
                    number_format((float)$totalPaid, 2),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportXlsx(): StreamedResponse
    {
        $records = $this->getBaseQuery()->get();
        $filename = 'subscriptions_report_' . now()->format('Y-m-d_His') . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Subscriptions');

        $headers = [
            'Order ID', 'Invoice No', 'Order Date', 'Customer Name', 'Customer Email',
            'Product / Plan', 'Billing Provider', 'Subscription ID', 'Recurring Amount',
            'Active Status', 'Total Payments Recorded', 'Total Paid'
        ];
        $sheet->fromArray($headers, null, 'A1');

        $rowNum = 2;
        foreach ($records as $item) {
            $payments = $this->getPaymentsForItem($item);
            $totalPaid = $payments->sum('payment_amount');

            $sheet->fromArray([
                $item->order_id,
                $item->order?->order_invoice_no ?? 'N/A',
                $item->order?->order_date ? $item->order->order_date->format('Y-m-d H:i:s') : 'N/A',
                $item->order?->user?->name ?? 'Guest User',
                $item->order?->user?->email ?? '-',
                $item->item_name,
                strtoupper($item->subscription_provider ?? 'N/A'),
                $item->subscription_plan_id ?? '-',
                (float)$item->final_price,
                $item->active_subscription ? 'ACTIVE' : 'CANCELLED',
                $payments->count(),
                (float)$totalPaid,
            ], null, 'A' . $rowNum);
            $rowNum++;
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function getPaymentsForItem(OrderDetail $item)
    {
        $order = $item->order;
        if (!$order) {
            return collect();
        }

        $subId = $item->subscription_plan_id;

        return OrderPayment::where(function ($q) use ($order, $subId) {
                $q->where('order_id', $order->id);
                if (!empty($subId)) {
                    $q->orWhere('authorization_code', $subId)
                      ->orWhere('processor_response', 'like', "%{$subId}%");
                }
            })
            ->latest('id')
            ->get();
    }

    public function render(): View
    {
        $subscriptions = $this->getBaseQuery()->paginate($this->perPage);

        // Calculate KPI summaries
        [$start, $end] = $this->getRangeDates();
        $statsQuery = OrderDetail::query()
            ->where(function ($q) {
                $q->where('subscription', 1)
                  ->orWhere('active_subscription', 1)
                  ->orWhereNotNull('subscription_plan_id');
            })
            ->whereHas('order', function ($q) use ($start, $end) {
                $q->whereBetween('order_date', [$start, $end]);
            });

        $totalCount     = (clone $statsQuery)->count();
        $activeCount    = (clone $statsQuery)->where('active_subscription', 1)->count();
        $cancelledCount = (clone $statsQuery)->where('active_subscription', 0)->count();
        $activeMonthlyValue = (clone $statsQuery)->where('active_subscription', 1)->sum('final_price');

        return view('livewire.report-subscriptions', [
            'subscriptions'      => $subscriptions,
            'totalCount'         => $totalCount,
            'activeCount'        => $activeCount,
            'cancelledCount'     => $cancelledCount,
            'activeMonthlyValue' => $activeMonthlyValue,
        ]);
    }
}
