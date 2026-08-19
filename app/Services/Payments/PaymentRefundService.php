<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderRefund;
use App\Models\ProductInventory;
use App\Services\Payments\Contracts\PaymentProcessorInterface;
use App\Services\Payments\Processors\PaddleProcessor;
use App\Services\Payments\Processors\PayPalProcessor;
use App\Services\Payments\Processors\StripeProcessor;
use App\Services\Payments\Processors\TestProcessor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class PaymentRefundService
{
    public function __construct(
        protected PaymentProcessorManager $processorManager
    ) {}

    /**
     * Process a partial or full refund for a specific OrderPayment record.
     *
     * @param  OrderPayment $payment       The payment to refund
     * @param  float        $amount        The refund amount
     * @param  string|null  $reason        Admin refund reason / note
     * @param  bool         $postToGateway Whether to dispatch the refund via provider API
     * @return OrderRefund
     *
     * @throws InvalidArgumentException|RuntimeException
     */
    public function refundPayment(
        OrderPayment $payment,
        float $amount,
        ?string $reason = null,
        bool $postToGateway = true
    ): OrderRefund {
        $amount = round($amount, 2);
        $remainingRefundable = round($payment->remaining_refundable, 2);

        if ($amount <= 0) {
            throw new InvalidArgumentException('Refund amount must be greater than zero.');
        }

        if ($amount > $remainingRefundable) {
            throw new InvalidArgumentException(
                "Refund amount (\${$amount}) exceeds remaining refundable amount (\${$remainingRefundable}) for this payment."
            );
        }

        $order = $payment->order;
        if (!$order) {
            throw new RuntimeException('Order not found for the given payment.');
        }

        $authCode = 'RFND-' . strtoupper(Str::random(8));
        $processorResponse = 'Manual/Offline refund of $' . number_format($amount, 2);

        // Dispatch to payment gateway API if requested and applicable
        if ($postToGateway) {
            $processor = $this->resolveProcessorForPayment($payment);

            if ($processor && !($processor instanceof TestProcessor)) {
                $transactionId = trim((string) ($payment->authorization_code ?: ''));

                if (empty($transactionId) && !empty($payment->processor_response)) {
                    // Try to extract transaction / payment intent from processor response
                    if (preg_match('/(pi_[a-zA-Z0-9_]+|txn_[a-zA-Z0-9_]+|sub_[a-zA-Z0-9_]+|I-[A-Z0-9]+)/i', $payment->processor_response, $matches)) {
                        $transactionId = $matches[1];
                    }
                }

                if (!empty($transactionId)) {
                    $result = $processor->refund($transactionId, $amount, $reason);

                    if (!$result->success) {
                        Log::error("[PaymentRefundService] Gateway refund failed for payment #{$payment->id}: " . $result->errorMessage);
                        throw new RuntimeException($result->errorMessage ?: 'Payment gateway refund failed.');
                    }

                    $authCode = $result->authorizationCode ?: $authCode;
                    $processorResponse = $result->processorName . ' API Refund: ' . ($result->authorizationCode ?: 'Success')
                        . ($reason ? ' (Reason: ' . $reason . ')' : '');
                } else {
                    $processorResponse .= ' (No gateway transaction ID found on record)';
                }
            } else {
                $processorResponse = 'Simulated refund of $' . number_format($amount, 2) . ($reason ? ' - ' . $reason : '');
            }
        } else {
            $processorResponse .= ' [Offline Ledger Entry]' . ($reason ? ' - ' . $reason : '');
        }

        return DB::transaction(function () use ($payment, $order, $amount, $authCode, $processorResponse) {
            // 1. Create the OrderRefund record
            $refund = OrderRefund::create([
                'order_id'           => $order->id,
                'order_payment_id'   => $payment->id,
                'amount'             => $amount,
                'refund_date'        => now(),
                'authorization_code' => $authCode,
                'processor_response' => $processorResponse,
            ]);

            // 2. Update the OrderPayment status
            $newRemaining = max(0.0, (float) $payment->payment_amount - (float) $payment->fresh()->refunds->sum('amount'));
            if ($newRemaining <= 0.005) {
                $payment->payment_status = 2; // Fully Refunded
            } else {
                $payment->payment_status = 3; // Partially Refunded
            }
            $payment->save();

            // 3. Update the overall Order status
            $totalRefunded = (float) $order->fresh()->refunds->sum('amount');
            if (abs($totalRefunded - (float) $order->order_total) < 0.01 || $totalRefunded >= (float) $order->order_total) {
                $order->order_status = 3; // Order Fully Refunded
            }
            $order->save();

            // 4. Inventory restocking on first refund
            if ($order->refunds->count() === 1) {
                foreach ($order->details as $detail) {
                    if ($detail->inventory_id > 0) {
                        $inventory = ProductInventory::where('variant_id', $detail->inventory_id)->first();
                        if ($inventory) {
                            $inventory->quantity_available += (int) $detail->item_qty;
                            $inventory->save();
                        }
                    }
                }
            }

            return $refund;
        });
    }

    /**
     * Resolve the appropriate PaymentProcessorInterface for a given OrderPayment.
     */
    public function resolveProcessorForPayment(OrderPayment $payment): ?PaymentProcessorInterface
    {
        $method = strtolower(trim((string) $payment->payment_method));
        $authCode = trim((string) $payment->authorization_code);
        $processorResponse = strtolower(trim((string) $payment->processor_response));

        // Detect Stripe
        if (
            str_contains($method, 'stripe') ||
            str_starts_with($authCode, 'pi_') ||
            str_starts_with($authCode, 'ch_') ||
            str_starts_with($authCode, 'pm_') ||
            str_contains($processorResponse, 'stripe')
        ) {
            $isSandbox = $this->processorManager->activeProcessorIsSandbox(1) || str_contains($method, 'sandbox');
            return new StripeProcessor($isSandbox);
        }

        // Detect Paddle
        if (
            str_contains($method, 'paddle') ||
            str_starts_with($authCode, 'txn_') ||
            str_starts_with($authCode, 'ctm_') ||
            str_contains($processorResponse, 'paddle')
        ) {
            $isSandbox = $this->processorManager->activeProcessorIsSandbox(2) || str_contains($method, 'sandbox');
            return new PaddleProcessor($isSandbox);
        }

        // Detect PayPal
        if (
            str_contains($method, 'paypal') ||
            str_starts_with($authCode, 'i-') ||
            str_starts_with($authCode, 'pay-') ||
            str_contains($processorResponse, 'paypal')
        ) {
            $isSandbox = $this->processorManager->activeProcessorIsSandbox(3) || str_contains($method, 'sandbox');
            return new PayPalProcessor($isSandbox);
        }

        // Default or Fallback to active processor
        try {
            return $this->processorManager->resolveActive();
        } catch (\Throwable $e) {
            return new TestProcessor();
        }
    }
}
