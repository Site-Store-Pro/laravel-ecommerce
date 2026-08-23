<?php

namespace App\Livewire;

use App\Models\ContentAccessToken;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\ProductInventory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminOrderDetails extends Component
{
    public int $orderId;
    public Order $order;
    public float $refundAmount = 0.00;

    // Status change with confirm step
    public int $pendingStatus = 0;
    public bool $showStatusConfirm = false;

    // Mark shipped form
    public bool $showShipForm = false;
    public string $shipDate = '';
    public string $trackingNumber = '';

    // Delete order confirm
    public bool $showDeleteConfirm = false;

    // ── Payment CRUD ─────────────────────────────────────────────────────────
    public bool $showPaymentModal = false;
    public ?int $editingPaymentId = null;
    public string $pmtDate = '';
    public string $pmtAmount = '';
    public string $pmtMethod = 'Manual';
    public int $pmtStatus = 1;
    public string $pmtAuthCode = '';
    public string $pmtNotes = '';

    // ── Payment Refund Modal ──────────────────────────────────────────────────
    public bool $showRefundModal = false;
    public ?int $refundingPaymentId = null;
    public string $refundPaymentAmount = '';
    public string $refundReason = '';
    public bool $refundPostToGateway = true;

    // ── Download Expiration Modal ─────────────────────────────────────────────
    public bool $showDownloadExpirationModal = false;
    public ?int $editingOrderDetailId = null;
    public string $editDownloadExpiration = '';
    public string $editingItemName = '';

    // ── Content Access Token Modal ────────────────────────────────────────────
    public bool $showContentTokenModal = false;
    public ?int $editingTokenId = null;
    public string $editTokenExpiration = '';
    public string $editingTokenUrl = '';

    public function mount(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403, 'Unauthorized staff access.');
        $this->orderId = $id;
        $this->loadOrder();
        $this->shipDate = now()->format('Y-m-d');
    }

    private function loadOrder(): void
    {
        $this->order = Order::with(['user', 'details.variant.product', 'details.contentAccessToken', 'payments.refunds', 'refunds.payment', 'statusList'])->findOrFail($this->orderId);
        
        $alreadyRefunded = (float) $this->order->refunds->sum('amount');
        $this->refundAmount = max(0.00, (float)$this->order->order_total - $alreadyRefunded);
    }

    // ---------- Status Change ----------

    public function setPendingStatus(int $statusCode): void
    {
        $this->pendingStatus = $statusCode;
        $this->showStatusConfirm = true;
    }

    public function cancelStatusChange(): void
    {
        $this->pendingStatus = 0;
        $this->showStatusConfirm = false;
    }

    public function applyStatusChange(): void
    {
        if ($this->pendingStatus === 0) {
            return;
        }
        $this->updateOrderStatus($this->pendingStatus);
        $this->showStatusConfirm = false;
        $this->pendingStatus = 0;
    }

    public function updateOrderStatus(int $statusCode): void
    {
        $oldStatus = $this->order->order_status;
        $newStatus = $statusCode;

        $this->order->order_status = $newStatus;

        if ($newStatus === 2 && $oldStatus !== 2) {
            $this->order->order_shipping_date = now();
            if (empty($this->order->order_shipping_tracking)) {
                $this->order->order_shipping_tracking = 'TRK' . strtoupper(Str::random(10));
            }
        }

        $this->order->save();

        if ($newStatus === 2 && $oldStatus !== 2) {
            $this->sendShipmentEmail();
        }

        session()->flash('status', 'Order status updated successfully.');
        $this->loadOrder();
    }

    // ---------- Mark Shipped ----------

    public function toggleShipForm(): void
    {
        $this->showShipForm = !$this->showShipForm;
        if ($this->showShipForm) {
            $this->shipDate = now()->format('Y-m-d');
            $this->trackingNumber = '';
        }
    }

    public function markShipped(): void
    {
        $this->validate([
            'shipDate' => 'required|date',
        ], [], [
            'shipDate' => 'Ship Date',
        ]);

        $this->order->order_status = 2; // Shipped
        $this->order->order_shipping_date = $this->shipDate;
        $this->order->order_shipping_tracking = !empty(trim($this->trackingNumber))
            ? trim($this->trackingNumber)
            : ('TRK' . strtoupper(Str::random(10)));
        $this->order->save();

        $this->sendShipmentEmail();
        $this->showShipForm = false;

        session()->flash('status', 'Order marked as shipped. Tracking: ' . $this->order->order_shipping_tracking);
        $this->loadOrder();
    }

    // ---------- Process Refund ----------

    // ---------- Payment Refund Modal Handlers ----------

    public function openRefundModal(int $paymentId): void
    {
        $payment = $this->order->payments->firstWhere('id', $paymentId);
        if (!$payment) {
            session()->flash('error', 'Payment record not found.');
            return;
        }

        $remaining = (float) $payment->remaining_refundable;
        if ($remaining <= 0) {
            session()->flash('error', 'This payment has already been fully refunded.');
            return;
        }

        $this->refundingPaymentId = $paymentId;
        // Default to the full remaining payment amount as requested
        $this->refundPaymentAmount = number_format($remaining, 2, '.', '');
        $this->refundReason = '';
        
        $method = strtolower(trim((string) $payment->payment_method));
        $authCode = trim((string) $payment->authorization_code);
        
        // Auto-check postToGateway for online gateway payments
        $this->refundPostToGateway = (
            str_contains($method, 'stripe') ||
            str_contains($method, 'paddle') ||
            str_contains($method, 'paypal') ||
            str_starts_with($authCode, 'pi_') ||
            str_starts_with($authCode, 'txn_') ||
            str_starts_with($authCode, 'i-')
        );

        $this->showRefundModal = true;
    }

    public function closeRefundModal(): void
    {
        $this->showRefundModal = false;
        $this->refundingPaymentId = null;
        $this->refundPaymentAmount = '';
        $this->refundReason = '';
    }

    public function processPaymentRefund(\App\Services\Payments\PaymentRefundService $refundService): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403);

        if (!$this->refundingPaymentId) {
            session()->flash('error', 'No payment selected for refund.');
            return;
        }

        $payment = $this->order->payments->firstWhere('id', $this->refundingPaymentId);
        if (!$payment) {
            session()->flash('error', 'Payment record not found.');
            return;
        }

        $remaining = (float) $payment->remaining_refundable;

        $this->validate([
            'refundPaymentAmount' => 'required|numeric|min:0.01|max:' . $remaining,
            'refundReason'        => 'nullable|string|max:500',
        ], [
            'refundPaymentAmount.max' => 'The refund amount cannot exceed the remaining refundable payment balance of $' . number_format($remaining, 2) . '.',
        ], [
            'refundPaymentAmount' => 'Refund Amount',
        ]);

        try {
            $amount = (float) $this->refundPaymentAmount;
            $refund = $refundService->refundPayment(
                payment:       $payment,
                amount:        $amount,
                reason:        trim($this->refundReason) ?: null,
                postToGateway: (bool) $this->refundPostToGateway
            );

            $this->closeRefundModal();
            $this->loadOrder();

            session()->flash('status', 'Refund of $' . number_format($amount, 2) . ' processed successfully.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AdminOrderDetails refund error for payment #{$this->refundingPaymentId}: " . $e->getMessage());
            session()->flash('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }

    // ---------- Delete Order ----------

    public function confirmDelete(): void
    {
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteConfirm = false;
    }

    public function deleteOrder(): void
    {
        // Restore inventory for each purchased item
        foreach ($this->order->details as $detail) {
            if ($detail->inventory_id > 0) {
                $inventory = ProductInventory::where('variant_id', $detail->inventory_id)->first();
                if ($inventory) {
                    $inventory->quantity_available += (int) $detail->item_qty;
                    $inventory->save();
                }
            }
        }

        // Delete the order (cascade deletes details, payments, refunds via FK constraints)
        $this->order->delete();

        session()->flash('status', 'Order deleted and inventory restored successfully.');
        $this->redirect(route('admin.ecommerce.orders'), navigate: true);
    }

    // ---------- Send Shipment Email ----------

    private function sendShipmentEmail(): void
    {
        $user = $this->order->user;
        if (!$user) {
            return;
        }

        try {
            $statusText = $this->order->statusList ? $this->order->statusList->customerdisplay : 'Shipped';
            
            $itemsHtml = '<div style="margin-top: 24px; font-family: sans-serif; color: #1e293b;">';
            
            // Order details summary box
            $itemsHtml .= '<div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 24px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
            $itemsHtml .= '<tr>';
            $itemsHtml .= '<td style="padding-bottom: 12px;"><span style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;">Order Status</span><strong style="color: #4f46e5; font-size: 14px;">' . e($statusText) . '</strong></td>';
            $itemsHtml .= '<td style="padding-bottom: 12px;" align="right"><span style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;">Order Date</span><strong style="color: #334155; font-size: 14px;">' . $this->order->order_date->format('F d, Y h:i A') . '</strong></td>';
            $itemsHtml .= '</tr>';
            $itemsHtml .= '</table>';
            $itemsHtml .= '</div>';

            // Items Ordered section
            $itemsHtml .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Items Ordered</h3>';
            $itemsHtml .= '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 24px; padding: 16px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">';
            
            foreach ($this->order->details as $item) {
                $itemTypeBadge = $item->download_item 
                    ? '<span style="background-color: #f0fdf4; color: #15803d; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bbf7d0; display: inline-block; margin-top: 4px;">Digital Download</span>'
                    : ($item->item_shippable ? '<span style="background-color: #e0f2fe; color: #0369a1; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bae6fd; display: inline-block; margin-top: 4px;">Shippable Item</span>' : '');

                $itemsHtml .= '<tr style="border-bottom: 1px solid #f1f5f9;">';
                $itemsHtml .= '<td style="padding: 12px 0; vertical-align: top;">';
                $itemsHtml .= '<strong style="color: #0f172a; font-size: 14px; display: block;">' . e($item->item_name) . '</strong>';
                $itemsHtml .= '<span style="color: #64748b; font-size: 12px; display: block; margin-top: 2px;">Quantity: ' . number_format($item->item_qty, 0) . '</span>';
                $itemsHtml .= $itemTypeBadge;
                // View Content button — secure UUID token link, supports guest users
                $itemProduct = $item->variant?->product ?? null;
                if ($itemProduct) {
                    $contentUrl   = Product::resolveCompletionUrl($itemProduct->completion_redirect);
                    $contentLabel = $itemProduct->completionRedirectLabel();
                    if ($contentUrl) {
                        $recipientEmail = $this->order->user?->email ?? '';
                        $accessToken = ContentAccessToken::generateOrRefresh($item, $contentUrl, $recipientEmail);
                        $tokenUrl    = route('content.access', $accessToken->token);
                        $itemsHtml .= '<div style="margin-top: 8px;">';
                        $itemsHtml .= '<a href="' . e($tokenUrl) . '" target="_blank" style="background-color: #7c3aed; color: #ffffff; font-size: 11px; font-weight: bold; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-block; border: 1px solid #6d28d9;">' . e($contentLabel) . '</a>';
                        $itemsHtml .= '</div>';
                    }
                }
                $itemsHtml .= '</td>';
                $itemsHtml .= '<td style="padding: 12px 0; vertical-align: top;" align="right">';
                $itemsHtml .= '<strong style="color: #0f172a; font-size: 14px;">$' . number_format($item->final_price * $item->item_qty, 2) . '</strong>';
                $itemsHtml .= '</td>';
                $itemsHtml .= '</tr>';
            }
            
            // Financial Summary Block
            $itemsHtml .= '<tr><td colspan="2" style="padding-top: 16px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
            
            $itemsHtml .= '<tr>';
            $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">Subtotal</td>';
            $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">$' . number_format($this->order->order_subtotal, 2) . '</td>';
            $itemsHtml .= '</tr>';

            if ($this->order->order_discounts > 0) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #16a34a; padding-bottom: 8px;">Promotional Discount</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #16a34a; padding-bottom: 8px;" align="right">-$' . number_format($this->order->order_discounts, 2) . '</td>';
                $itemsHtml .= '</tr>';
            }

            $itemsHtml .= '<tr>';
            $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">Tax</td>';
            $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">$' . number_format($this->order->order_taxes, 2) . '</td>';
            $itemsHtml .= '</tr>';

            $itemsHtml .= '<tr>';
            $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 12px;">Shipping</td>';
            $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 12px;" align="right">$' . number_format($this->order->order_shipping, 2) . '</td>';
            $itemsHtml .= '</tr>';

            $itemsHtml .= '<tr style="border-top: 1px solid #e2e8f0;">';
            $itemsHtml .= '<td style="font-size: 16px; font-weight: 800; color: #0f172a; padding-top: 12px;">Total Charged</td>';
            $itemsHtml .= '<td style="font-size: 16px; font-weight: 800; color: #0f172a; padding-top: 12px;" align="right">$' . number_format($this->order->order_total, 2) . '</td>';
            $itemsHtml .= '</tr>';
            
            $itemsHtml .= '</table>';
            $itemsHtml .= '</td></tr>';
            
            $itemsHtml .= '</table>';
            $itemsHtml .= '</div>';

            // Shipping Address section if required
            if ($this->order->order_shipping_method == 1) {
                $itemsHtml .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Shipping Address</h3>';
                $itemsHtml .= '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; font-size: 14px; color: #334155; line-height: 1.5; margin-bottom: 24px;">';
                $itemsHtml .= '<strong style="color: #0f172a; display: block; margin-bottom: 4px;">' . e($user->name) . '</strong>';
                if ($user->company) {
                    $itemsHtml .= '<span style="color: #64748b; display: block;">' . e($user->company) . '</span>';
                }
                $itemsHtml .= '<span style="display: block;">' . e($user->shipping_address1) . '</span>';
                if ($user->shipping_address2) {
                    $itemsHtml .= '<span style="display: block;">' . e($user->shipping_address2) . '</span>';
                }
                $itemsHtml .= '<span style="display: block;">' . e($user->shipping_city) . ', ' . e($user->shopping_postalcode) . '</span>';
                $itemsHtml .= '<strong style="display: block; margin-top: 4px; color: #475569;">' . e($user->shipping_country) . '</strong>';
                $itemsHtml .= '</div>';
            }
            
            $itemsHtml .= '</div>';

            $vars = [
                'order_id' => $this->order->order_invoice_no,
                'customer_name' => $user->name,
                'tracking_number' => $this->order->order_shipping_tracking ?: '-',
                'order_total' => '$' . number_format($this->order->order_total, 2),
                'order_subtotal' => '$' . number_format($this->order->order_subtotal, 2),
                'order_taxes' => '$' . number_format($this->order->order_taxes, 2),
                'order_shipping' => '$' . number_format($this->order->order_shipping, 2),
                'order_items_table' => $itemsHtml,
                'app_name' => config('app.name'),
                'year' => date('Y'),
            ];

            \App\Services\EmailTemplateService::sendEmail('order_shipment', $user->email, $user->name, $vars);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send shipment confirmation email: " . $e->getMessage());
        }
    }

    // ---------- Send Download Reminder ----------

    public function sendDownloadReminder(): void
    {
        $user = $this->order->user;
        if (!$user) {
            session()->flash('error', 'Cannot send download reminder: no customer account is linked to this order.');
            $this->showDownloadConfirm = false;
            return;
        }

        try {
            $statusText = $this->order->statusList ? $this->order->statusList->customerdisplay : 'Open';
            
            // Build full order stats block
            $itemsHtml = '<div style="margin-top: 24px; font-family: sans-serif; color: #1e293b;">';
            $itemsHtml .= '<div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 24px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
            $itemsHtml .= '<tr>';
            $itemsHtml .= '<td style="padding-bottom: 12px;"><span style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;">Order Status</span><strong style="color: #4f46e5; font-size: 14px;">' . e($statusText) . '</strong></td>';
            $itemsHtml .= '<td style="padding-bottom: 12px;" align="right"><span style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;">Order Date</span><strong style="color: #334155; font-size: 14px;">' . $this->order->order_date->format('F d, Y h:i A') . '</strong></td>';
            $itemsHtml .= '</tr>';
            $itemsHtml .= '</table>';
            $itemsHtml .= '</div>';

            $itemsHtml .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Items Ordered</h3>';
            $itemsHtml .= '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 24px; padding: 16px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">';
            
            foreach ($this->order->details as $item) {
                $itemTypeBadge = $item->download_item 
                    ? '<span style="background-color: #f0fdf4; color: #15803d; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bbf7d0; display: inline-block; margin-top: 4px;">Digital Download</span>'
                    : ($item->item_shippable ? '<span style="background-color: #e0f2fe; color: #0369a1; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bae6fd; display: inline-block; margin-top: 4px;">Shippable Item</span>' : '');

                $itemsHtml .= '<tr style="border-bottom: 1px solid #f1f5f9;">';
                $itemsHtml .= '<td style="padding: 12px 0; vertical-align: top;">';
                $itemsHtml .= '<strong style="color: #0f172a; font-size: 14px; display: block;">' . e($item->item_name) . '</strong>';
                $itemsHtml .= '<span style="color: #64748b; font-size: 12px; display: block; margin-top: 2px;">Quantity: ' . number_format($item->item_qty, 0) . '</span>';
                $itemsHtml .= $itemTypeBadge;
                // View Content button — secure UUID token link, supports guest users
                $itemProduct = $item->variant?->product ?? null;
                if ($itemProduct) {
                    $contentUrl   = Product::resolveCompletionUrl($itemProduct->completion_redirect);
                    $contentLabel = $itemProduct->completionRedirectLabel();
                    if ($contentUrl) {
                        $recipientEmail = $this->order->user?->email ?? '';
                        $accessToken = ContentAccessToken::generateOrRefresh($item, $contentUrl, $recipientEmail);
                        $tokenUrl    = route('content.access', $accessToken->token);
                        $itemsHtml .= '<div style="margin-top: 8px;">';
                        $itemsHtml .= '<a href="' . e($tokenUrl) . '" target="_blank" style="background-color: #7c3aed; color: #ffffff; font-size: 11px; font-weight: bold; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-block; border: 1px solid #6d28d9;">' . e($contentLabel) . '</a>';
                        $itemsHtml .= '</div>';
                    }
                }
                $itemsHtml .= '</td>';
                $itemsHtml .= '<td style="padding: 12px 0; vertical-align: top;" align="right">';
                $itemsHtml .= '<strong style="color: #0f172a; font-size: 14px;">$' . number_format($item->final_price * $item->item_qty, 2) . '</strong>';
                $itemsHtml .= '</td>';
                $itemsHtml .= '</tr>';
            }
            
            $itemsHtml .= '<tr><td colspan="2" style="padding-top: 16px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
            
            if (\App\Models\CmsSetting::isEnabled('checkout_show_subtotal', true)) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">Subtotal</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">$' . number_format($this->order->order_subtotal, 2) . '</td>';
                $itemsHtml .= '</tr>';
            }

            if ($this->order->order_discounts > 0) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #16a34a; padding-bottom: 8px;">Promotional Discount</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #16a34a; padding-bottom: 8px;" align="right">-$' . number_format($this->order->order_discounts, 2) . '</td>';
                $itemsHtml .= '</tr>';
            }

            if (\App\Models\CmsSetting::isEnabled('checkout_show_tax', true)) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">Tax</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">$' . number_format($this->order->order_taxes, 2) . '</td>';
                $itemsHtml .= '</tr>';
            }

            if (\App\Models\CmsSetting::isEnabled('checkout_show_shipping', true)) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 12px;">Shipping</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 12px;" align="right">$' . number_format($this->order->order_shipping, 2) . '</td>';
                $itemsHtml .= '</tr>';
            }

            $itemsHtml .= '<tr style="border-top: 1px solid #e2e8f0;">';
            $itemsHtml .= '<td style="font-size: 16px; font-weight: 800; color: #0f172a; padding-top: 12px;">Total Charged</td>';
            $itemsHtml .= '<td style="font-size: 16px; font-weight: 800; color: #0f172a; padding-top: 12px;" align="right">$' . number_format($this->order->order_total, 2) . '</td>';
            $itemsHtml .= '</tr>';
            $itemsHtml .= '</table>';
            $itemsHtml .= '</td></tr>';
            $itemsHtml .= '</table>';
            $itemsHtml .= '</div>';
            $itemsHtml .= '</div>';

            // Build links
            $linksHtml = '<div style="margin-top: 15px; font-family: sans-serif;">';
            foreach ($this->order->details as $item) {
                if ($item->download_item) {
                    $downloadUrl = route('products.download', [$item->id, $this->order->order_external_id]);
                    $linksHtml .= '<div style="margin-bottom: 12px; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px;">';
                    $linksHtml .= '<strong style="color: #166534; font-size: 14px; display: block; margin-bottom: 4px;">' . e($item->item_name) . '</strong>';
                    $linksHtml .= '<a href="' . $downloadUrl . '" style="background-color: #4f46e5; color: #ffffff; padding: 6px 12px; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: bold; display: inline-block; margin-top: 4px;">Download File</a>';
                    $linksHtml .= '</div>';
                }
            }
            $linksHtml .= '</div>';

            $vars = [
                'order_id' => $this->order->order_invoice_no,
                'customer_name' => $user->name,
                'order_total' => '$' . number_format($this->order->order_total, 2),
                'order_subtotal' => '$' . number_format($this->order->order_subtotal, 2),
                'order_taxes' => '$' . number_format($this->order->order_taxes, 2),
                'order_shipping' => '$' . number_format($this->order->order_shipping, 2),
                'download_links' => $linksHtml,
                'order_items_table' => $itemsHtml,
                'app_name' => config('app.name'),
                'year' => date('Y'),
            ];

            \App\Services\EmailTemplateService::sendEmail('download_reminder', $user->email, $user->name, $vars);
            session()->flash('status', 'Download reminder email sent successfully to ' . $user->email);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send download reminder email: " . $e->getMessage());
            session()->flash('error', 'Failed to send download reminder: ' . $e->getMessage());
        }
        $this->showDownloadConfirm = false;
    }

    // ---------- Send Duplicate Order Confirmation Email ----------

    public bool $showEmailConfirm = false;

    public function triggerEmailConfirm(): void
    {
        $this->showEmailConfirm = true;
    }

    public function cancelEmailSend(): void
    {
        $this->showEmailConfirm = false;
    }

    // ---------- Send Download Reminder Confirmation ----------

    public bool $showDownloadConfirm = false;

    public function triggerDownloadReminderConfirm(): void
    {
        $this->showDownloadConfirm = true;
    }

    public function cancelDownloadReminderSend(): void
    {
        $this->showDownloadConfirm = false;
    }

    public function sendDuplicateOrderConfirmation(): void
    {
        $user = $this->order->user;
        if (!$user) {
            session()->flash('error', 'Cannot send confirmation email: no customer account is linked to this order.');
            $this->showEmailConfirm = false;
            return;
        }

        try {
            $order = $this->order;
            $statusText = $order->statusList ? $order->statusList->customerdisplay : 'Payment Received - Order Being Processed.';
            
            $itemsHtml = '<div style="margin-top: 24px; font-family: sans-serif; color: #1e293b;">';
            
            // Order details summary box
            $itemsHtml .= '<div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 24px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
            $itemsHtml .= '<tr>';
            $itemsHtml .= '<td style="padding-bottom: 12px;"><span style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;">Order Status</span><strong style="color: #4f46e5; font-size: 14px;">' . e($statusText) . '</strong></td>';
            $itemsHtml .= '<td style="padding-bottom: 12px;" align="right"><span style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;">Order Date</span><strong style="color: #334155; font-size: 14px;">' . $order->order_date->format('F d, Y h:i A') . '</strong></td>';
            $itemsHtml .= '</tr>';
            $itemsHtml .= '</table>';
            $itemsHtml .= '</div>';

            // Items Ordered section
            $itemsHtml .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Items Ordered</h3>';
            $itemsHtml .= '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 24px; padding: 16px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">';
            
            foreach ($order->details as $item) {
                $itemTypeBadge = $item->download_item 
                    ? '<span style="background-color: #f0fdf4; color: #15803d; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bbf7d0; display: inline-block; margin-top: 4px;">Digital Download</span>'
                    : '<span style="background-color: #e0f2fe; color: #0369a1; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bae6fd; display: inline-block; margin-top: 4px;">Shippable Item</span>';

                $itemsHtml .= '<tr style="border-bottom: 1px solid #f1f5f9;">';
                $itemsHtml .= '<td style="padding: 12px 0; vertical-align: top;">';
                $itemsHtml .= '<strong style="color: #0f172a; font-size: 14px; display: block;">' . e($item->item_name) . '</strong>';
                $itemsHtml .= '<span style="color: #64748b; font-size: 12px; display: block; margin-top: 2px;">Quantity: ' . number_format($item->item_qty, 0) . '</span>';
                $itemsHtml .= $itemTypeBadge;
                if ($item->download_item) {
                    $downloadUrl = route('products.download', [$item->id, $order->order_external_id]);
                    $itemsHtml .= '<div style="margin-top: 8px;">';
                    $itemsHtml .= '<a href="' . e($downloadUrl) . '" target="_blank" style="background-color: #4f46e5; color: #ffffff; font-size: 11px; font-weight: bold; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-block; border: 1px solid #4338ca;">Download File</a>';
                    $itemsHtml .= '</div>';
                }
                // View Content button — secure UUID token link, supports guest users
                $itemProduct = $item->variant?->product ?? null;
                if ($itemProduct) {
                    $contentUrl   = Product::resolveCompletionUrl($itemProduct->completion_redirect);
                    $contentLabel = $itemProduct->completionRedirectLabel();
                    if ($contentUrl) {
                        $recipientEmail = $this->order->user?->email ?? '';
                        $accessToken = ContentAccessToken::generateOrRefresh($item, $contentUrl, $recipientEmail);
                        $tokenUrl    = route('content.access', $accessToken->token);
                        $itemsHtml .= '<div style="margin-top: 8px;">';
                        $itemsHtml .= '<a href="' . e($tokenUrl) . '" target="_blank" style="background-color: #7c3aed; color: #ffffff; font-size: 11px; font-weight: bold; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-block; border: 1px solid #6d28d9;">' . e($contentLabel) . '</a>';
                        $itemsHtml .= '</div>';
                    }
                }
                $itemsHtml .= '</td>';
                $itemsHtml .= '<td style="padding: 12px 0; vertical-align: top;" align="right">';
                $itemsHtml .= '<strong style="color: #0f172a; font-size: 14px; display: block;">' . \App\Services\CurrencyService::format($item->final_price * $item->item_qty) . '</strong>';
                if ($item->discount_price > 0) {
                    $itemsHtml .= '<span style="color: #94a3b8; font-size: 11px; text-decoration: line-through; display: block;">' . \App\Services\CurrencyService::format(($item->final_price + $item->discount_price) * $item->item_qty) . '</span>';
                }
                $itemsHtml .= '</td>';
                $itemsHtml .= '</tr>';
            }
            
            // Financial Summary Block
            $itemsHtml .= '<tr><td colspan="2" style="padding-top: 16px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
            
            if (\App\Models\CmsSetting::isEnabled('checkout_show_subtotal', true)) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">Subtotal</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . \App\Services\CurrencyService::format((float)$order->order_subtotal) . '</td>';
                $itemsHtml .= '</tr>';
            }

            if ($order->order_discounts > 0) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #16a34a; padding-bottom: 8px;">Promotional Discount</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #16a34a; padding-bottom: 8px;" align="right">-$' . number_format($order->order_discounts, 2) . '</td>';
                $itemsHtml .= '</tr>';
            }

            if (\App\Models\CmsSetting::isEnabled('checkout_show_tax', true)) {
                // Determine tax row label and display for the email
                $emailTaxLabel = \App\Services\CurrencyService::taxLabel($user->shipping_countrycode ?? 'US');
                $emailVatInclusive = \App\Services\CurrencyService::isVatInclusive();
                $emailCrossBorder  = \App\Services\CurrencyService::isCrossBorderExport($user->shipping_countrycode ?? 'US');

                if ($emailVatInclusive && !$emailCrossBorder) {
                    // VAT-inclusive domestic: show embedded VAT amount as an informational row
                    $emailVatRate   = \App\Services\CurrencyService::merchantVatRate();
                    $emailVatAmount = \App\Services\CurrencyService::extractVat((float)$order->order_subtotal, $emailVatRate);
                    $itemsHtml .= '<tr>';
                    $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">Includes ' . e($emailTaxLabel) . '</td>';
                    $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . \App\Services\CurrencyService::format($emailVatAmount) . '</td>';
                    $itemsHtml .= '</tr>';
                } elseif (!$emailVatInclusive || ($emailVatInclusive && $emailCrossBorder)) {
                    // US/CA merchant tax added on top, or cross-border export (VAT stripped, 0 tax)
                    $itemsHtml .= '<tr>';
                    $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">' . e($emailTaxLabel) . '</td>';
                    $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . \App\Services\CurrencyService::format((float)$order->order_taxes) . '</td>';
                    $itemsHtml .= '</tr>';
                }
            }

            if (\App\Models\CmsSetting::isEnabled('checkout_show_shipping', true)) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">Shipping (' . e($order->order_shipping_method_name ?? 'Standard') . ')</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . \App\Services\CurrencyService::format((float)$order->order_shipping) . '</td>';
                $itemsHtml .= '</tr>';
            }

            if ($order->order_handling > 0) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">Handling Surcharge</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . \App\Services\CurrencyService::format((float)$order->order_handling) . '</td>';
                $itemsHtml .= '</tr>';
            }

            $itemsHtml .= '<tr style="border-top: 1px solid #e2e8f0;">';
            $itemsHtml .= '<td style="font-size: 16px; font-weight: 800; color: #0f172a; padding-top: 12px;">Total Charged</td>';
            $itemsHtml .= '<td style="font-size: 16px; font-weight: 800; color: #0f172a; padding-top: 12px;" align="right">' . \App\Services\CurrencyService::format((float)$order->order_total) . '</td>';
            $itemsHtml .= '</tr>';
            
            $itemsHtml .= '</table>';
            $itemsHtml .= '</td></tr>';
            
            $itemsHtml .= '</table>';
            $itemsHtml .= '</div>';

            // Shipping Address section if required
            if ($order->order_shipping_method == 1 && $user) {
                $itemsHtml .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Shipping Address</h3>';
                $itemsHtml .= '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; font-size: 14px; color: #334155; line-height: 1.5; margin-bottom: 24px;">';
                $itemsHtml .= '<strong style="color: #0f172a; display: block; margin-bottom: 4px;">' . e($user->name) . '</strong>';
                if ($user->company) {
                    $itemsHtml .= '<span style="color: #64748b; display: block;">' . e($user->company) . '</span>';
                }
                $itemsHtml .= '<span style="display: block;">' . e($user->shipping_address1) . '</span>';
                if ($user->shipping_address2) {
                    $itemsHtml .= '<span style="display: block;">' . e($user->shipping_address2) . '</span>';
                }
                $statePart = $user->shipping_state ? ', ' . e($user->shipping_state) : '';
                $itemsHtml .= '<span style="display: block;">' . e($user->shipping_city) . $statePart . ' ' . e($user->shopping_postalcode) . '</span>';
                $itemsHtml .= '<strong style="display: block; margin-top: 4px; color: #475569;">' . e($user->shipping_country) . '</strong>';
                $itemsHtml .= '</div>';
            }

            if (!empty($order->order_comments)) {
                $itemsHtml .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Order Comments</h3>';
                $itemsHtml .= '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; font-size: 14px; color: #334155; line-height: 1.5; margin-bottom: 24px; white-space: pre-wrap;">';
                $itemsHtml .= e($order->order_comments);
                $itemsHtml .= '</div>';
            }
            
            $itemsHtml .= '</div>';

            $vars = [
                'order_id'          => $order->order_invoice_no,
                'customer_name'     => $user->name,
                'order_total'       => \App\Services\CurrencyService::format((float)$order->order_total),
                'order_subtotal'    => \App\Services\CurrencyService::format((float)$order->order_subtotal),
                'order_taxes'       => \App\Services\CurrencyService::format((float)$order->order_taxes),
                'order_shipping'    => \App\Services\CurrencyService::format((float)$order->order_shipping),
                'order_items_table' => $itemsHtml,
                'app_name'          => config('app.name'),
                'year'              => date('Y'),
            ];

            \App\Services\EmailTemplateService::sendEmail('order_confirmation', $user->email, $user->name, $vars);
            session()->flash('status', 'Duplicate order confirmation email sent successfully to ' . $user->email);
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
            \Illuminate\Support\Facades\Log::error("Failed to send duplicate order confirmation email: " . $e->getMessage());
            session()->flash('error', 'Failed to send duplicate order confirmation email: ' . $e->getMessage());
        }

        $this->showEmailConfirm = false;
    }

    // ── Payment CRUD ─────────────────────────────────────────────────────────

    public function openAddPayment(): void
    {
        $this->editingPaymentId = null;
        $this->pmtDate          = now()->format('Y-m-d');
        $this->pmtAmount        = '';
        $this->pmtMethod        = 'Manual';
        $this->pmtStatus        = 1;
        $this->pmtAuthCode      = '';
        $this->pmtNotes         = '';
        $this->showPaymentModal = true;
    }

    public function openEditPayment(int $id): void
    {
        $payment = OrderPayment::findOrFail($id);

        $this->editingPaymentId = $id;
        $this->pmtDate          = $payment->payment_date
            ? $payment->payment_date->format('Y-m-d')
            : now()->format('Y-m-d');
        $this->pmtAmount        = (string) $payment->payment_amount;
        $this->pmtMethod        = $payment->payment_method ?? 'Manual';
        $this->pmtStatus        = (int) $payment->payment_status;
        $this->pmtAuthCode      = $payment->authorization_code ?? '';
        $this->pmtNotes         = $payment->processor_response ?? '';
        $this->showPaymentModal = true;
    }

    public function savePayment(): void
    {
        $this->validate([
            'pmtDate'   => 'required|date',
            'pmtAmount' => 'required|numeric|min:0.01',
            'pmtMethod' => 'required|string|max:100',
            'pmtStatus' => 'required|integer|in:0,1',
        ], [], [
            'pmtDate'   => 'Payment Date',
            'pmtAmount' => 'Amount',
            'pmtMethod' => 'Method',
        ]);

        $data = [
            'order_id'             => $this->orderId,
            'payment_date'         => $this->pmtDate,
            'payment_amount'       => (float) $this->pmtAmount,
            'payment_method'       => $this->pmtMethod,
            'payment_status'       => $this->pmtStatus,
            'authorization_code'   => $this->pmtAuthCode ?: null,
            'processor_response'   => $this->pmtNotes ?: null,
        ];

        if ($this->editingPaymentId) {
            OrderPayment::findOrFail($this->editingPaymentId)->update($data);
            $msg = 'Payment updated successfully.';
        } else {
            OrderPayment::create($data);
            $msg = 'Payment added successfully.';
        }

        $this->showPaymentModal = false;
        $this->loadOrder();
        session()->flash('status', $msg);
    }

    public function deletePayment(int $id): void
    {
        OrderPayment::findOrFail($id)->delete();
        $this->loadOrder();
        session()->flash('status', 'Payment deleted successfully.');
    }

    public function cancelSubscription(int $orderDetailId, \App\Services\Payments\SubscriptionService $subscriptionService): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403);

        $detail = $this->order->details->firstWhere('id', $orderDetailId);
        if (!$detail) {
            session()->flash('error', 'Line item not found.');
            return;
        }

        try {
            $subscriptionService->cancelSubscription($detail, 'Cancelled by admin staff');
            $this->loadOrder();
            session()->flash('status', 'Subscription agreement has been successfully cancelled with the payment provider.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AdminOrderDetails cancelSubscription error for detail #{$orderDetailId}: " . $e->getMessage());
            session()->flash('error', 'Failed to cancel subscription with provider: ' . $e->getMessage());
        }
    }

    public function getBalanceDueProperty(): float
    {
        $totalPaid = (float) $this->order->payments->sum('payment_amount');
        return max(0.0, (float) $this->order->order_total - $totalPaid);
    }

    // ── Download Expiration Methods ───────────────────────────────────────────
    public function openDownloadExpirationModal(int $orderDetailId): void
    {
        $detail = \App\Models\OrderDetail::findOrFail($orderDetailId);
        $this->editingOrderDetailId = $orderDetailId;
        $this->editingItemName = $detail->item_name;
        $this->editDownloadExpiration = $detail->download_expiration ? $detail->download_expiration->format('Y-m-d\TH:i') : '';
        $this->showDownloadExpirationModal = true;
    }

    public function setDownloadExpirationShortcut(string $type): void
    {
        $this->editDownloadExpiration = match ($type) {
            'yesterday' => now()->subDay()->endOfDay()->format('Y-m-d\TH:i'),
            '30days'    => now()->addDays(30)->endOfDay()->format('Y-m-d\TH:i'),
            '90days'    => now()->addDays(90)->endOfDay()->format('Y-m-d\TH:i'),
            '1year'     => now()->addYear()->endOfDay()->format('Y-m-d\TH:i'),
            'lifetime'  => '',
            default     => $this->editDownloadExpiration,
        };
    }

    public function saveDownloadExpiration(): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403);
        $detail = \App\Models\OrderDetail::findOrFail($this->editingOrderDetailId);
        
        $newExp = !empty($this->editDownloadExpiration) ? \Illuminate\Support\Carbon::parse($this->editDownloadExpiration) : null;
        $detail->update(['download_expiration' => $newExp]);

        $this->showDownloadExpirationModal = false;
        $this->loadOrder();
        session()->flash('status', 'Download expiration updated successfully.');
    }

    public function closeDownloadExpirationModal(): void
    {
        $this->showDownloadExpirationModal = false;
    }

    // ── Content Access Token Methods ──────────────────────────────────────────
    public function openContentTokenModal(int $tokenId): void
    {
        $token = ContentAccessToken::findOrFail($tokenId);
        $this->editingTokenId = $tokenId;
        $this->editingTokenUrl = $token->redirect_url;
        $this->editTokenExpiration = $token->expires_at ? $token->expires_at->format('Y-m-d\TH:i') : '';
        $this->showContentTokenModal = true;
    }

    public function setTokenExpirationShortcut(string $type): void
    {
        $this->editTokenExpiration = match ($type) {
            'yesterday' => now()->subDay()->endOfDay()->format('Y-m-d\TH:i'),
            '30days'    => now()->addDays(30)->endOfDay()->format('Y-m-d\TH:i'),
            '90days'    => now()->addDays(90)->endOfDay()->format('Y-m-d\TH:i'),
            '1year'     => now()->addYear()->endOfDay()->format('Y-m-d\TH:i'),
            'lifetime'  => '',
            default     => $this->editTokenExpiration,
        };
    }

    public function saveTokenExpiration(): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403);
        $token = ContentAccessToken::findOrFail($this->editingTokenId);

        $newExp = !empty($this->editTokenExpiration) ? \Illuminate\Support\Carbon::parse($this->editTokenExpiration) : null;
        $token->update(['expires_at' => $newExp]);

        $this->showContentTokenModal = false;
        $this->loadOrder();
        session()->flash('status', 'Content access expiration updated successfully.');
    }

    public function closeContentTokenModal(): void
    {
        $this->showContentTokenModal = false;
    }

    public function regenerateContentToken(int $tokenId): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403);
        $token = ContentAccessToken::findOrFail($tokenId);
        $token->update([
            'token'      => (string) Str::uuid(),
            'expires_at' => now()->addDays(90)->endOfDay(),
        ]);
        $this->loadOrder();
        session()->flash('status', 'Content access token regenerated successfully with fresh 90-day expiry.');
    }

    public function render(): View
    {
        $statuses = \App\Models\OrderStatusList::where('Active', 1)
            ->where('orderstatuscode', '!=', 5)
            ->orderBy('sortorder', 'asc')
            ->get();

        $contentAccessTokens = ContentAccessToken::whereIn('order_detail_id', $this->order->details->pluck('id'))
            ->with(['product', 'orderDetail'])
            ->get();

        return view('livewire.admin-order-details', [
            'statuses'            => $statuses,
            'contentAccessTokens' => $contentAccessTokens,
        ]);
    }
}
