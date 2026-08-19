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
            'invoice.paid',
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
            ->orWhere('processor_response', 'like', "%{$intentId}%")
            ->first();

        if (!$payment) {
            // If this payment intent is linked to a subscription invoice, handle it via invoice flow
            $invoiceId = $intent->invoice ?? null;
            if ($invoiceId) {
                Log::info('[Stripe Webhook] payment_intent.succeeded belongs to invoice ' . $invoiceId);
            } else {
                Log::info('[Stripe Webhook] payment_intent.succeeded — no matching order_payments record for: ' . $intentId);
            }
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
            ->orWhere('processor_response', 'like', "%{$intentId}%")
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
     * Logged for future subscription support.
     */
    private function handleSubscriptionEvent(string $type, object $subscription): void
    {
        $subId  = $subscription->id ?? null;
        $status = $subscription->status ?? 'N/A';

        Log::info('[Stripe Webhook] Subscription event: ' . $type, [
            'subscription_id' => $subId,
            'customer_id'     => $subscription->customer ?? 'N/A',
            'status'          => $status,
        ]);

        if (!$subId) {
            return;
        }

        if (in_array($status, ['canceled', 'incomplete_expired', 'unpaid'], true)) {
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
            Log::info("[Stripe Webhook] Marked subscription {$subId} as cancelled in order_details and expired access.");
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

    /**
     * invoice.payment_succeeded / invoice.paid / invoice.payment_failed
     *
     * Captures recurring subscription renewal payments while preventing duplicates.
     */
    private function handleInvoiceEvent(string $type, object|array $invoice): void
    {
        $invoiceObj      = is_array($invoice) ? (object) $invoice : $invoice;
        $invoiceId       = $invoiceObj->id ?? 'N/A';
        $subscriptionId  = $this->extractSubscriptionId($invoice);
        $billingReason   = $invoiceObj->billing_reason ?? null;
        $customerId      = $invoiceObj->customer ?? null;
        $customerEmail   = $invoiceObj->customer_email ?? null;
        $paymentIntentId = $invoiceObj->payment_intent ?? null;
        $amountPaid      = ($invoiceObj->amount_paid ?? 0) / 100;

        Log::info('[Stripe Webhook] Invoice event: ' . $type, [
            'invoice_id'      => $invoiceId,
            'subscription_id' => $subscriptionId,
            'customer_id'     => $customerId,
            'customer_email'  => $customerEmail,
            'billing_reason'  => $billingReason,
            'amount_paid'     => $amountPaid,
            'status'          => $invoiceObj->status ?? 'N/A',
        ]);

        if ($type === 'invoice.payment_failed') {
            Log::warning("[Stripe Webhook] Invoice payment failed for subscription {$subscriptionId} (Invoice: {$invoiceId}).");
            return;
        }

        if ($type === 'invoice.payment_succeeded' || $type === 'invoice.paid') {
            // 1. Idempotency check: ensure this specific invoice / payment intent has not already been recorded
            $alreadyRecorded = DB::table('order_payments')
                ->where('authorization_code', $invoiceId)
                ->orWhere('processor_response', 'like', "%{$invoiceId}%")
                ->when($paymentIntentId, function ($q) use ($paymentIntentId) {
                    $q->orWhere('authorization_code', $paymentIntentId)
                      ->orWhere('processor_response', 'like', "%{$paymentIntentId}%");
                })
                ->exists();

            if ($alreadyRecorded) {
                Log::info("[Stripe Webhook] Invoice {$invoiceId} / PaymentIntent {$paymentIntentId} already recorded in order_payments. Skipping.");
                return;
            }

            // 2. If it's an initial subscription creation invoice that was ALREADY recorded during checkout (by sub_ ID)
            if ($billingReason === 'subscription_create' && $subscriptionId) {
                $hasInitialCheckoutPayment = DB::table('order_payments')
                    ->where('authorization_code', $subscriptionId)
                    ->orWhere('processor_response', 'like', "%{$subscriptionId}%")
                    ->exists();

                if ($hasInitialCheckoutPayment) {
                    Log::info("[Stripe Webhook] Skipped initial subscription invoice {$invoiceId} (already handled at checkout for sub {$subscriptionId}).");
                    return;
                }
            }

            // 3. Record the subscription payment
            $this->recordSubscriptionRenewalPayment($invoice, $amountPaid);
        }
    }

    /**
     * Record a recurring subscription renewal payment from Stripe webhook.
     */
    private function recordSubscriptionRenewalPayment(object|array $invoice, float $amountPaid): void
    {
        $invoiceObj      = is_array($invoice) ? (object) $invoice : $invoice;
        $subscriptionId  = $this->extractSubscriptionId($invoice);
        $invoiceId       = $invoiceObj->id ?? null;
        $paymentIntentId = $invoiceObj->payment_intent ?? $invoiceId;
        $customerId      = $invoiceObj->customer ?? null;
        $customerEmail   = $invoiceObj->customer_email ?? null;

        $orderId = null;

        // 1. Match exact initial order by Subscription ID in order_payments
        if ($subscriptionId) {
            $initialPayment = DB::table('order_payments')
                ->where('authorization_code', $subscriptionId)
                ->orWhere('processor_response', 'like', "%{$subscriptionId}%")
                ->orderByDesc('id')
                ->first();

            if ($initialPayment) {
                $orderId = $initialPayment->order_id;
            }
        }

        // 2. Look up customer user by stripe_customer_id or email
        $user = null;
        if ($customerId) {
            $user = DB::table('users')->where('stripe_customer_id', $customerId)->first();
        }
        if (!$user && $customerEmail) {
            $user = DB::table('users')->where('email', $customerEmail)->first();
            if ($user && $customerId && empty($user->stripe_customer_id)) {
                DB::table('users')->where('id', $user->id)->update(['stripe_customer_id' => $customerId, 'updated_at' => now()]);
            }
        }

        // 3. Fallback: match by Price ID or Product ID and User
        if (!$orderId && $user) {
            $lines = [];
            if (isset($invoiceObj->lines->data)) {
                $lines = $invoiceObj->lines->data;
            } elseif (isset($invoiceObj->lines['data'])) {
                $lines = $invoiceObj->lines['data'];
            }

            $priceId = null;
            $productId = null;
            if (is_iterable($lines)) {
                foreach ($lines as $line) {
                    $priceId = $this->extractPriceId($line);
                    $productId = $this->extractProductId($line);
                    if ($priceId || $productId) break;
                }
            }

            if ($priceId || $productId) {
                $variant = \App\Models\ProductVariant::when($priceId, function ($q) use ($priceId) {
                        $q->where('stripe_live_price_id', $priceId)
                          ->orWhere('stripe_sandbox_price_id', $priceId);
                    })
                    ->first();

                if ($variant) {
                    $matchedOrder = DB::table('orders')
                        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->where('orders.order_user_id', $user->id)
                        ->where('order_details.inventory_id', $variant->id)
                        ->orderByDesc('orders.id')
                        ->select('orders.id')
                        ->first();

                    if ($matchedOrder) {
                        $orderId = $matchedOrder->id;
                    }
                }
            }

            // User's latest order fallback
            if (!$orderId) {
                $latestOrder = DB::table('orders')->where('order_user_id', $user->id)->orderByDesc('id')->first();
                $orderId = $latestOrder ? $latestOrder->id : null;
            }
        }

        // 4. Ultimate fallback: if no order exists, create a designated subscription renewal order
        if (!$orderId) {
            $userId = $user ? $user->id : 0;
            $invoiceNo = 'SUB-' . strtoupper(\Illuminate\Support\Str::random(8));

            $orderId = DB::table('orders')->insertGetId([
                'order_invoice_no' => $invoiceNo,
                'order_external_id'=> (string) \Illuminate\Support\Str::uuid(),
                'order_user_id'    => $userId,
                'order_status'     => 7, // Completed
                'order_date'       => now(),
                'order_total'      => $amountPaid,
                'order_subtotal'   => $amountPaid,
                'order_taxes'      => 0.00,
                'order_discounts'  => 0.00,
                'order_shipping'   => 0,
                'order_download'   => 0,
                'order_handling'   => 0.00,
                'order_comments'   => "Auto-generated subscription renewal from Stripe invoice {$invoiceId}" . ($subscriptionId ? " (Sub: {$subscriptionId})" : ''),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            Log::info("[Stripe Webhook] Created new renewal Order #{$orderId} for invoice {$invoiceId}.");
        }

        // Insert new OrderPayment record for this renewal cycle
        DB::table('order_payments')->insert([
            'order_id'           => $orderId,
            'payment_date'       => now(),
            'payment_amount'     => $amountPaid,
            'payment_method'     => 'Stripe (Subscription Renewal)',
            'payment_status'     => 1, // Paid
            'authorization_code' => $paymentIntentId ?: $invoiceId,
            'processor_response' => "Invoice: {$invoiceId}" . ($subscriptionId ? " | Sub: {$subscriptionId}" : ''),
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        Log::info("[Stripe Webhook] Successfully recorded renewal payment of \${$amountPaid} for Order #{$orderId} (Invoice: {$invoiceId}, Sub: {$subscriptionId}).");
    }

    /**
     * Extract the subscription ID from an invoice object or array across all Stripe API versions.
     */
    private function extractSubscriptionId(mixed $invoice): ?string
    {
        if (is_object($invoice)) {
            if (!empty($invoice->subscription) && is_string($invoice->subscription)) {
                return $invoice->subscription;
            }
            if (isset($invoice->parent->subscription_details->subscription) && is_string($invoice->parent->subscription_details->subscription)) {
                return $invoice->parent->subscription_details->subscription;
            }
            if (isset($invoice->lines->data) && is_iterable($invoice->lines->data)) {
                foreach ($invoice->lines->data as $line) {
                    if (isset($line->parent->subscription_item_details->subscription) && is_string($line->parent->subscription_item_details->subscription)) {
                        return $line->parent->subscription_item_details->subscription;
                    }
                    if (!empty($line->subscription) && is_string($line->subscription)) {
                        return $line->subscription;
                    }
                }
            }
        } elseif (is_array($invoice)) {
            if (!empty($invoice['subscription']) && is_string($invoice['subscription'])) {
                return $invoice['subscription'];
            }
            if (!empty($invoice['parent']['subscription_details']['subscription']) && is_string($invoice['parent']['subscription_details']['subscription'])) {
                return $invoice['parent']['subscription_details']['subscription'];
            }
            if (!empty($invoice['lines']['data']) && is_iterable($invoice['lines']['data'])) {
                foreach ($invoice['lines']['data'] as $line) {
                    if (!empty($line['parent']['subscription_item_details']['subscription'])) {
                        return $line['parent']['subscription_item_details']['subscription'];
                    }
                    if (!empty($line['subscription'])) {
                        return $line['subscription'];
                    }
                }
            }
        }

        return null;
    }

    /**
     * Extract the price/plan ID from an invoice line item across all Stripe API versions.
     */
    private function extractPriceId(mixed $line): ?string
    {
        if (is_object($line)) {
            return $line->pricing->price_details->price 
                ?? $line->price->id 
                ?? $line->plan->id 
                ?? null;
        } elseif (is_array($line)) {
            return $line['pricing']['price_details']['price'] 
                ?? $line['price']['id'] 
                ?? $line['plan']['id'] 
                ?? null;
        }
        return null;
    }

    /**
     * Extract the product ID from an invoice line item across all Stripe API versions.
     */
    private function extractProductId(mixed $line): ?string
    {
        if (is_object($line)) {
            return $line->pricing->price_details->product 
                ?? $line->price->product 
                ?? $line->plan->product 
                ?? null;
        } elseif (is_array($line)) {
            return $line['pricing']['price_details']['product'] 
                ?? $line['price']['product'] 
                ?? $line['plan']['product'] 
                ?? null;
        }
        return null;
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
