<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * PaddleWebhookController
 *
 * Receives and verifies signed webhook events from Paddle Billing.
 * Register your endpoint in the Paddle Dashboard:
 *   Developer Tools → Notifications → New Destination
 *   URL: https://yourdomain.com/webhooks/paddle
 *
 * Required .env variable:
 *   PADDLE_WEBHOOK_SECRET=pdl_ntf_xxxxxxxxxxxxxxxx
 *
 * This route is CSRF-exempt via the global `webhooks/*` exception in bootstrap/app.php.
 *
 * Events handled:
 *   - transaction.completed        → confirms order payment
 *   - transaction.payment_failed   → logs failure
 *   - customer.created             → stores paddle_customer_id on user record
 *   - subscription.created         → logged (future subscription support)
 *   - subscription.updated         → logged (future subscription support)
 *   - subscription.canceled        → logged (future subscription support)
 *
 * Paddle Signature Format (Paddle-Signature header):
 *   ts=<unix_timestamp>;h1=<hmac_sha256_hex>
 *   HMAC is computed as: HMAC-SHA256(key=secret, data="ts:payload")
 */
class PaddleWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Paddle-Signature', '');
        $secret    = env('PADDLE_WEBHOOK_SECRET', '');

        if (empty($secret)) {
            Log::error('[Paddle Webhook] PADDLE_WEBHOOK_SECRET is not set in .env');
            return response('Webhook secret not configured.', 500);
        }

        // ── Verify Paddle signature ───────────────────────────────────────────
        if (!$this->verifySignature($payload, $sigHeader, $secret)) {
            Log::warning('[Paddle Webhook] Signature verification failed.');
            return response('Invalid signature.', 400);
        }

        $data = json_decode($payload, true);
        if (!$data || !isset($data['event_type'])) {
            Log::warning('[Paddle Webhook] Invalid or empty payload.');
            return response('Invalid payload.', 400);
        }

        $eventType = $data['event_type'];
        $eventData = $data['data'] ?? [];

        Log::info('[Paddle Webhook] Received event: ' . $eventType, [
            'notification_id' => $data['notification_id'] ?? 'N/A',
        ]);

        // ── Dispatch to handler ───────────────────────────────────────────────
        match ($eventType) {
            'transaction.completed'       => $this->handleTransactionCompleted($eventData),
            'transaction.payment_failed'  => $this->handleTransactionFailed($eventData),
            'customer.created'            => $this->handleCustomerCreated($eventData),
            'subscription.created',
            'subscription.updated',
            'subscription.canceled'       => $this->handleSubscriptionEvent($eventType, $eventData),
            default                       => Log::info('[Paddle Webhook] Unhandled event type: ' . $eventType),
        };

        return response('OK', 200);
    }

    // ── Event Handlers ────────────────────────────────────────────────────────

    /**
     * transaction.completed
     *
     * Paddle has completed the transaction. Find the matching order by the
     * transaction ID stored in order_payments.authorization_code and confirm it.
     */
    private function handleTransactionCompleted(array $data): void
    {
        $transactionId = $data['id'] ?? null;
        if (!$transactionId) {
            return;
        }

        $payment = DB::table('order_payments')
            ->where('authorization_code', $transactionId)
            ->first();

        if (!$payment) {
            Log::info('[Paddle Webhook] transaction.completed — no matching order_payments record for: ' . $transactionId);
            return;
        }

        // Ensure order is at least at Open/Pending status (1)
        $order = DB::table('orders')->where('id', $payment->order_id)->first();
        if ($order && $order->order_status < 1) {
            DB::table('orders')
                ->where('id', $payment->order_id)
                ->update(['order_status' => 1, 'updated_at' => now()]);

            Log::info('[Paddle Webhook] Order #' . $payment->order_id . ' confirmed via webhook.');
        }

        // Store Paddle customer ID if provided
        $customerId = $data['customer_id'] ?? null;
        if ($customerId && $order && $order->order_user_id) {
            DB::table('users')
                ->where('id', $order->order_user_id)
                ->whereNull('paddle_customer_id')
                ->update(['paddle_customer_id' => $customerId, 'updated_at' => now()]);
        }
    }

    /**
     * transaction.payment_failed
     *
     * Log the failure for review. The user-facing failure is handled
     * by the Livewire OrderReview component during the active session.
     */
    private function handleTransactionFailed(array $data): void
    {
        Log::warning('[Paddle Webhook] transaction.payment_failed', [
            'transaction_id' => $data['id']          ?? 'N/A',
            'customer_id'    => $data['customer_id']  ?? 'N/A',
            'error_code'     => $data['payments'][0]['error_code'] ?? 'N/A',
        ]);
    }

    /**
     * customer.created
     *
     * Paddle has created a customer record. Match by email and store
     * the paddle_customer_id on the users record for future charges.
     */
    private function handleCustomerCreated(array $data): void
    {
        $email      = $data['email'] ?? null;
        $customerId = $data['id']    ?? null;

        if (!$email || !$customerId) {
            return;
        }

        $updated = DB::table('users')
            ->where('email', $email)
            ->whereNull('paddle_customer_id')
            ->update(['paddle_customer_id' => $customerId, 'updated_at' => now()]);

        if ($updated) {
            Log::info('[Paddle Webhook] Stored paddle_customer_id for: ' . $email);
        }
    }

    /**
     * subscription.created / subscription.updated / subscription.canceled
     *
     * Logged for future subscription support. Extend this method to
     * activate/deactivate user subscription entitlements.
     */
    private function handleSubscriptionEvent(string $type, array $data): void
    {
        $subId  = $data['id'] ?? null;
        $status = strtolower($data['status'] ?? '');

        Log::info('[Paddle Webhook] Subscription event: ' . $type, [
            'subscription_id' => $subId,
            'customer_id'     => $data['customer_id']  ?? 'N/A',
            'status'          => $status,
        ]);

        if (!$subId) {
            return;
        }

        if (in_array($status, ['canceled', 'past_due', 'paused'], true)) {
            $yesterday = now()->subDay()->endOfDay();
            $cancelledQuery = DB::table('order_details')
                ->where(function ($q) use ($subId) {
                    $q->where('subscription_plan_id', $subId)
                      ->orWhere(function ($q2) use ($subId) {
                          $q2->where('subscription', 1)
                             ->whereExists(function ($subQ) use ($subId) {
                                 $subQ->select(DB::raw(1))
                                      ->from('order_payments')
                                      ->whereColumn('order_payments.order_id', 'order_details.order_id')
                                      ->where(function ($p) use ($subId) {
                                          $p->where('authorization_code', $subId)
                                            ->orWhere('processor_response', 'like', "%{$subId}%");
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
            Log::info("[Paddle Webhook] Marked subscription {$subId} as cancelled in order_details and expired access.");
        } elseif ($status === 'active') {
            DB::table('order_details')
                ->where('subscription_plan_id', $subId)
                ->update([
                    'active_subscription' => 1,
                    'subscription_status' => 'active',
                    'updated_at'          => now(),
                ]);
        }
    }

    // ── Signature Verification ────────────────────────────────────────────────

    /**
     * Verify the Paddle webhook signature.
     *
     * Paddle-Signature header format: ts=<timestamp>;h1=<hex_signature>
     * The signed string is: "<timestamp>:<raw_body>"
     * Signed with HMAC-SHA256 using PADDLE_WEBHOOK_SECRET as the key.
     *
     * @see https://developer.paddle.com/webhooks/signature-verification
     */
    private function verifySignature(string $payload, string $sigHeader, string $secret): bool
    {
        if (empty($sigHeader)) {
            return false;
        }

        // Parse ts= and h1= from the header
        $parts = [];
        foreach (explode(';', $sigHeader) as $part) {
            [$key, $value] = array_pad(explode('=', $part, 2), 2, '');
            $parts[trim($key)] = trim($value);
        }

        $timestamp = $parts['ts'] ?? null;
        $signature = $parts['h1'] ?? null;

        if (!$timestamp || !$signature) {
            return false;
        }

        // Reject webhooks older than 5 minutes to prevent replay attacks
        if (abs(time() - (int) $timestamp) > 300) {
            Log::warning('[Paddle Webhook] Timestamp too old — possible replay attack.');
            return false;
        }

        $signedString   = $timestamp . ':' . $payload;
        $expectedHmac   = hash_hmac('sha256', $signedString, $secret);

        return hash_equals($expectedHmac, $signature);
    }
}
