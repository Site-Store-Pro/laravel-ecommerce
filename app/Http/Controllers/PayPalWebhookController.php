<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\Payments\Processors\PayPalProcessor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PayPalWebhookController
 *
 * Receives and processes webhook notifications from PayPal for one-time orders
 * and recurring subscriptions.
 *
 * Webhook URL to register in PayPal Developer Dashboard:
 *   https://yourdomain.com/webhooks/paypal
 *
 * Events handled:
 *   - BILLING.SUBSCRIPTION.ACTIVATED    ? Confirms subscription & flags order as active
 *   - BILLING.SUBSCRIPTION.CREATED      ? Logged / confirmed
 *   - BILLING.SUBSCRIPTION.CANCELLED    ? Logged / flags subscription status
 *   - BILLING.SUBSCRIPTION.SUSPENDED    ? Logged / flags subscription status
 *   - BILLING.SUBSCRIPTION.EXPIRED      ? Logged
 *   - PAYMENT.SALE.COMPLETED            ? Records initial/recurring subscription payments in order_payments
 *   - PAYMENT.CAPTURE.COMPLETED         ? Confirms one-time order payment
 *   - CHECKOUT.ORDER.APPROVED           ? Logged / fallback confirmation
 */
class PayPalWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->json()->all();
        $eventType = $payload['event_type'] ?? null;
        $resource = $payload['resource'] ?? [];

        if (empty($eventType)) {
            Log::warning('[PayPal Webhook] Received empty or invalid payload.');
            return response('Invalid payload', 400);
        }

        Log::info('[PayPal Webhook] Received event: ' . $eventType, [
            'id' => $payload['id'] ?? null,
            'summary' => $payload['summary'] ?? null,
        ]);

        // Optional webhook signature verification via PayPal REST API if PAYPAL_WEBHOOK_ID configured
        $webhookId = config('services.paypal.webhook_id', env('PAYPAL_WEBHOOK_ID'));
        if (!empty($webhookId)) {
            if (!$this->verifySignature($request, $webhookId)) {
                Log::warning('[PayPal Webhook] Signature verification failed.');
                return response('Invalid signature', 400);
            }
        }

        match ($eventType) {
            'BILLING.SUBSCRIPTION.ACTIVATED',
            'BILLING.SUBSCRIPTION.CREATED'      => $this->handleSubscriptionActivated($resource),
            'BILLING.SUBSCRIPTION.CANCELLED',
            'BILLING.SUBSCRIPTION.SUSPENDED',
            'BILLING.SUBSCRIPTION.EXPIRED'      => $this->handleSubscriptionStatusChanged($eventType, $resource),
            'PAYMENT.SALE.COMPLETED'            => $this->handleSaleCompleted($resource),
            'PAYMENT.CAPTURE.COMPLETED'         => $this->handleCaptureCompleted($resource),
            'CHECKOUT.ORDER.APPROVED'           => $this->handleOrderApproved($resource),
            default                             => Log::info('[PayPal Webhook] Unhandled event type: ' . $eventType),
        };

        return response('OK', 200);
    }

    /**
     * Handle BILLING.SUBSCRIPTION.ACTIVATED / CREATED
     */
    private function handleSubscriptionActivated(array $resource): void
    {
        $subscriptionId = $resource['id'] ?? null;
        if (!$subscriptionId) {
            return;
        }

        Log::info("[PayPal Webhook] Subscription activated: {$subscriptionId}");

        // Find matching payment record
        $payment = DB::table('order_payments')
            ->where('authorization_code', $subscriptionId)
            ->orWhere('processor_response', 'like', "%{$subscriptionId}%")
            ->first();

        if ($payment) {
            $order = DB::table('orders')->where('id', $payment->order_id)->first();
            if ($order && $order->order_status < 1) {
                DB::table('orders')
                    ->where('id', $payment->order_id)
                    ->update(['order_status' => 1, 'updated_at' => now()]);
                Log::info("[PayPal Webhook] Order #{$payment->order_id} confirmed active via subscription activation.");
            }

            DB::table('order_details')
                ->where('order_id', $payment->order_id)
                ->where('subscription', 1)
                ->update([
                    'active_subscription' => 1,
                    'subscription_status' => 'active',
                    'updated_at'          => now(),
                ]);
        }
    }

    /**
     * Handle BILLING.SUBSCRIPTION.CANCELLED / SUSPENDED / EXPIRED
     */
    private function handleSubscriptionStatusChanged(string $eventType, array $resource): void
    {
        $subscriptionId = $resource['id'] ?? null;
        $status = $resource['status'] ?? $eventType;

        Log::warning("[PayPal Webhook] Subscription {$subscriptionId} status changed to {$status} (Event: {$eventType}).");

        if (!$subscriptionId) {
            return;
        }

        $yesterday = now()->subDay()->endOfDay();
        $cancelledQuery = DB::table('order_details')
            ->where(function ($q) use ($subscriptionId) {
                $q->where('subscription_plan_id', $subscriptionId)
                  ->orWhere(function ($q2) use ($subscriptionId) {
                      $q2->where('subscription', 1)
                         ->whereExists(function ($subQ) use ($subscriptionId) {
                             $subQ->select(DB::raw(1))
                                  ->from('order_payments')
                                  ->whereColumn('order_payments.order_id', 'order_details.order_id')
                                  ->where(function ($p) use ($subscriptionId) {
                                      $p->where('authorization_code', $subscriptionId)
                                        ->orWhere('processor_response', 'like', "%{$subscriptionId}%");
                                  });
                         });
                  });
            });

        $cancelledIds = (clone $cancelledQuery)->pluck('id');

        $cancelledQuery->update([
            'active_subscription' => 0,
            'subscription_status' => 'cancelled',
            'download_expiration' => $yesterday,
            'updated_at'          => now(),
        ]);

        if ($cancelledIds->isNotEmpty()) {
            DB::table('content_access_tokens')
                ->whereIn('order_detail_id', $cancelledIds)
                ->update(['expires_at' => $yesterday, 'updated_at' => now()]);
        }

        Log::info("[PayPal Webhook] Marked subscription {$subscriptionId} as cancelled in order_details and expired access.");
    }

    /**
     * Handle PAYMENT.SALE.COMPLETED (Recurring billing cycle payment)
     */
    private function handleSaleCompleted(array $resource): void
    {
        $saleId         = $resource['id'] ?? null;
        $subscriptionId = $resource['billing_agreement_id'] ?? null;
        $amount         = (float) ($resource['amount']['total'] ?? 0.00);
        $currency       = $resource['amount']['currency'] ?? 'USD';
        $state          = strtolower($resource['state'] ?? '');

        if (!$saleId || $state !== 'completed') {
            Log::info("[PayPal Webhook] SALE.COMPLETED ignored — state is '{$state}' or missing sale ID.");
            return;
        }

        Log::info("[PayPal Webhook] Sale completed: {$saleId} for subscription {$subscriptionId} amount: {$amount} {$currency}");

        // Idempotency: skip if this sale transaction ID is already recorded
        $alreadyRecorded = DB::table('order_payments')
            ->where('authorization_code', $saleId)
            ->orWhere('processor_response', 'like', "%{$saleId}%")
            ->exists();

        if ($alreadyRecorded) {
            Log::info("[PayPal Webhook] Sale {$saleId} already recorded in order_payments. Skipping.");
            return;
        }

        // If this sale belongs to a subscription, find the parent order
        if ($subscriptionId) {
            $parentPayment = DB::table('order_payments')
                ->where('authorization_code', $subscriptionId)
                ->orWhere('processor_response', 'like', "%{$subscriptionId}%")
                ->orderBy('id', 'asc')
                ->first();

            if ($parentPayment) {
                // Record renewal payment in order_payments
                OrderPayment::create([
                    'order_id'           => $parentPayment->order_id,
                    'payment_date'       => now(),
                    'payment_amount'     => $amount,
                    'payment_method'     => 'PayPal Payments',
                    'payment_status'     => 1, // Paid
                    'authorization_code' => $saleId,
                    'processor_response' => "PayPal Subscription Renewal | Agreement: {$subscriptionId} | Sale: {$saleId}",
                ]);

                Log::info("[PayPal Webhook] Logged subscription renewal payment for Order #{$parentPayment->order_id} (Sale: {$saleId}).");
            } else {
                Log::warning("[PayPal Webhook] Could not find parent order for subscription agreement: {$subscriptionId}");
            }
        }
    }

    /**
     * Handle PAYMENT.CAPTURE.COMPLETED (One-time payment capture)
     */
    private function handleCaptureCompleted(array $resource): void
    {
        $captureId = $resource['id'] ?? null;
        if (!$captureId) {
            return;
        }

        $payment = DB::table('order_payments')
            ->where('authorization_code', $captureId)
            ->orWhere('processor_response', 'like', "%{$captureId}%")
            ->first();

        if ($payment) {
            $order = DB::table('orders')->where('id', $payment->order_id)->first();
            if ($order && $order->order_status < 1) {
                DB::table('orders')
                    ->where('id', $payment->order_id)
                    ->update(['order_status' => 1, 'updated_at' => now()]);
                Log::info("[PayPal Webhook] Order #{$payment->order_id} confirmed via capture.completed.");
            }
        }
    }

    /**
     * Handle CHECKOUT.ORDER.APPROVED
     */
    private function handleOrderApproved(array $resource): void
    {
        $orderId = $resource['id'] ?? null;
        Log::info("[PayPal Webhook] Order approved: {$orderId}");
    }

    /**
     * Verify webhook signature with PayPal API
     */
    private function verifySignature(Request $request, string $webhookId): bool
    {
        try {
            $processor = new PayPalProcessor();
            $accessToken = $processor->getAccessToken();
            $baseUrl = $processor->getBaseUrl();

            $verificationPayload = [
                'auth_algo'         => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url'          => $request->header('PAYPAL-CERT-URL'),
                'transmission_id'   => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig'  => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id'        => $webhookId,
                'webhook_event'     => $request->json()->all(),
            ];

            $response = Http::withToken($accessToken)
                ->post("$baseUrl/v1/notifications/verify-webhook-signature", $verificationPayload);

            if ($response->successful()) {
                $status = $response->json('verification_status');
                return strtoupper($status) === 'SUCCESS';
            }
        } catch (\Throwable $e) {
            Log::error('[PayPal Webhook] Verification exception: ' . $e->getMessage());
        }

        return false;
    }
}
