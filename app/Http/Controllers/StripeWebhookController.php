<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * StripeWebhookController
 *
 * Receives and verifies signed webhook events from Stripe.
 * Register your endpoint in the Stripe Dashboard:
 *   Developers → Webhooks → Add endpoint → https://yourdomain.com/webhooks/stripe
 *
 * Required .env variable:
 *   STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxx
 *
 * This route is CSRF-exempt via the global `webhooks/*` exception in bootstrap/app.php.
 *
 * Events handled:
 *   - payment_intent.succeeded         → confirms order payment
 *   - payment_intent.payment_failed    → logs failure, flags order if found
 *   - charge.refunded                  → marks order as refunded (status 3)
 *   - customer.created                 → stores stripe_customer_id on user record
 *   - customer.subscription.created    → logged (future subscription support)
 *   - customer.subscription.updated    → logged (future subscription support)
 *   - customer.subscription.deleted    → logged (future subscription support)
 *   - invoice.payment_succeeded        → logged (future subscription support)
 *   - invoice.payment_failed           → logged (future subscription support)
 */
class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = env('STRIPE_WEBHOOK_SECRET', '');

        if (empty($secret)) {
            Log::error('[Stripe Webhook] STRIPE_WEBHOOK_SECRET is not set in .env');
            return response('Webhook secret not configured.', 500);
        }

        // ── Verify signature ─────────────────────────────────────────────────
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('[Stripe Webhook] Signature verification failed: ' . $e->getMessage());
            return response('Invalid signature.', 400);
        } catch (\UnexpectedValueException $e) {
            Log::warning('[Stripe Webhook] Invalid payload: ' . $e->getMessage());
            return response('Invalid payload.', 400);
        }

        Log::info('[Stripe Webhook] Received event: ' . $event->type, [
            'event_id' => $event->id,
        ]);

        // ── Dispatch to handler ───────────────────────────────────────────────
        match ($event->type) {
            'payment_intent.succeeded'          => $this->handlePaymentIntentSucceeded($event->data->object),
            'payment_intent.payment_failed'     => $this->handlePaymentIntentFailed($event->data->object),
            'charge.refunded'                   => $this->handleChargeRefunded($event->data->object),
            'customer.created'                  => $this->handleCustomerCreated($event->data->object),
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted'     => $this->handleSubscriptionEvent($event->type, $event->data->object),
            'invoice.payment_succeeded',
            'invoice.payment_failed'            => $this->handleInvoiceEvent($event->type, $event->data->object),
            default                             => Log::info('[Stripe Webhook] Unhandled event type: ' . $event->type),
        };

        return response('OK', 200);
    }

    // ── Event Handlers ────────────────────────────────────────────────────────

    /**
     * payment_intent.succeeded
     *
     * Stripe has confirmed the payment. Find the matching order by the
     * PaymentIntent ID stored in order_payments.authorization_code and
     * ensure the order status is set to Open/Pending (1).
     */
    private function handlePaymentIntentSucceeded(object $intent): void
    {
        $intentId = $intent->id ?? null;
        if (!$intentId) {
            return;
        }

        $payment = DB::table('order_payments')
            ->where('authorization_code', $intentId)
            ->orWhere('transaction_id', $intentId)
            ->first();

        if (!$payment) {
            Log::info('[Stripe Webhook] payment_intent.succeeded — no matching order_payments record for: ' . $intentId);
            return;
        }

        // Ensure order is at least at Open/Pending status (1)
        $order = DB::table('orders')->where('id', $payment->order_id)->first();
        if ($order && $order->order_status < 1) {
            DB::table('orders')
                ->where('id', $payment->order_id)
                ->update(['order_status' => 1, 'updated_at' => now()]);

            Log::info('[Stripe Webhook] Order #' . $payment->order_id . ' confirmed via webhook.');
        }

        // Store Stripe customer ID on user if available
        if (!empty($intent->customer)) {
            $this->storeStripeCustomerId($payment->order_id, $intent->customer);
        }
    }

    /**
     * payment_intent.payment_failed
     *
     * Log the failure. The order may or may not have been placed yet —
     * the OrderReview Livewire component handles in-session failures.
     */
    private function handlePaymentIntentFailed(object $intent): void
    {
        $error = $intent->last_payment_error->message ?? 'Unknown error';
        Log::warning('[Stripe Webhook] payment_intent.payment_failed', [
            'intent_id' => $intent->id ?? 'N/A',
            'error'     => $error,
        ]);
    }

    /**
     * charge.refunded
     *
     * A charge was fully or partially refunded in the Stripe Dashboard.
     * Mark the matching order as Refunded (status 3).
     */
    private function handleChargeRefunded(object $charge): void
    {
        $intentId = $charge->payment_intent ?? null;
        if (!$intentId) {
            return;
        }

        $payment = DB::table('order_payments')
            ->where('authorization_code', $intentId)
            ->orWhere('transaction_id', $intentId)
            ->first();

        if ($payment) {
            DB::table('orders')
                ->where('id', $payment->order_id)
                ->update(['order_status' => 3, 'updated_at' => now()]);

            Log::info('[Stripe Webhook] Order #' . $payment->order_id . ' marked as Refunded via webhook.');
        }
    }

    /**
     * customer.created
     *
     * Stripe created a customer object (often during first payment).
     * Match by email and store the stripe_customer_id on the users record.
     */
    private function handleCustomerCreated(object $customer): void
    {
        $email      = $customer->email ?? null;
        $customerId = $customer->id    ?? null;

        if (!$email || !$customerId) {
            return;
        }

        $updated = DB::table('users')
            ->where('email', $email)
            ->whereNull('stripe_customer_id')
            ->update(['stripe_customer_id' => $customerId, 'updated_at' => now()]);

        if ($updated) {
            Log::info('[Stripe Webhook] Stored stripe_customer_id for: ' . $email);
        }
    }

    /**
     * customer.subscription.* events
     *
     * Logged for future subscription support. Extend this method to
     * activate/deactivate user subscription entitlements.
     */
    private function handleSubscriptionEvent(string $type, object $subscription): void
    {
        Log::info('[Stripe Webhook] Subscription event: ' . $type, [
            'subscription_id' => $subscription->id      ?? 'N/A',
            'customer_id'     => $subscription->customer ?? 'N/A',
            'status'          => $subscription->status   ?? 'N/A',
        ]);
    }

    /**
     * invoice.payment_succeeded / invoice.payment_failed
     *
     * Logged for future subscription billing support.
     */
    private function handleInvoiceEvent(string $type, object $invoice): void
    {
        Log::info('[Stripe Webhook] Invoice event: ' . $type, [
            'invoice_id'  => $invoice->id       ?? 'N/A',
            'customer_id' => $invoice->customer  ?? 'N/A',
            'amount_due'  => $invoice->amount_due ?? 0,
            'status'      => $invoice->status    ?? 'N/A',
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Look up user by order and store the Stripe customer ID.
     */
    private function storeStripeCustomerId(int $orderId, string $stripeCustomerId): void
    {
        $order = DB::table('orders')->where('id', $orderId)->first();
        if ($order && $order->user_id) {
            DB::table('users')
                ->where('id', $order->user_id)
                ->whereNull('stripe_customer_id')
                ->update(['stripe_customer_id' => $stripeCustomerId, 'updated_at' => now()]);
        }
    }
}
