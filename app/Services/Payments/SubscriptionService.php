<?php

namespace App\Services\Payments;

use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Services\Payments\PaymentProcessorManager;
use App\Services\Payments\Processors\PaddleProcessor;
use App\Services\Payments\Processors\PayPalProcessor;
use App\Services\Payments\Processors\StripeProcessor;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    public function __construct(
        private readonly PaymentProcessorManager $paymentManager
    ) {}

    /**
     * Dynamically determine provider and cancel subscription at gateway and locally.
     *
     * @throws \Throwable
     */
    public function cancelSubscription(OrderDetail $orderDetail, string $reason = 'Cancelled by customer'): bool
    {
        $provider = $this->determineProvider($orderDetail);
        $subscriptionId = $this->determineSubscriptionId($orderDetail);

        if (empty($subscriptionId)) {
            Log::warning("[SubscriptionService] No subscription ID found for OrderDetail #{$orderDetail->id}. Marking cancelled locally.");
            $yesterday = now()->subDay()->endOfDay();
            $orderDetail->update([
                'active_subscription' => 0,
                'subscription_status' => 'cancelled',
                'download_expiration' => $yesterday,
            ]);
            \App\Models\ContentAccessToken::where('order_detail_id', $orderDetail->id)
                ->update(['expires_at' => $yesterday]);
            return true;
        }

        Log::info("[SubscriptionService] Cancelling subscription {$subscriptionId} via provider '{$provider}' for OrderDetail #{$orderDetail->id}.");

        // Dynamically invoke the correct provider API
        match (strtolower($provider)) {
            'stripe' => $this->cancelStripe($subscriptionId),
            'paddle' => $this->cancelPaddle($subscriptionId),
            'paypal' => $this->cancelPayPal($subscriptionId, $reason),
            default  => Log::warning("[SubscriptionService] Unknown subscription provider '{$provider}' for OrderDetail #{$orderDetail->id}. Skipping remote API call."),
        };

        $yesterday = now()->subDay()->endOfDay();

        // Update database status and set download_expiration to yesterday
        $orderDetail->update([
            'active_subscription'   => 0,
            'subscription_status'   => 'cancelled',
            'subscription_provider' => $provider,
            'download_expiration'   => $yesterday,
        ]);

        // Expire associated content access tokens to yesterday
        \App\Models\ContentAccessToken::where('order_detail_id', $orderDetail->id)
            ->update([
                'expires_at' => $yesterday,
            ]);

        return true;
    }

    /**
     * Determine the payment provider for an OrderDetail.
     */
    public function determineProvider(OrderDetail $orderDetail): string
    {
        if (!empty($orderDetail->subscription_provider)) {
            return strtolower(trim($orderDetail->subscription_provider));
        }

        $subId = $orderDetail->subscription_plan_id;
        if (!empty($subId)) {
            if (str_starts_with($subId, 'sub_') || str_starts_with($subId, 'si_') || str_starts_with($subId, 'seti_')) {
                return 'stripe';
            }
            if (str_starts_with($subId, 'I-') || str_starts_with($subId, 'P-')) {
                return 'paypal';
            }
            if (str_starts_with($subId, 'sub_01') || str_starts_with($subId, 'pri_') || str_starts_with($subId, 'txn_')) {
                return 'paddle';
            }
        }

        // Inspect order payment records
        $payment = OrderPayment::where('order_id', $orderDetail->order_id)->first();
        if ($payment) {
            $method = strtolower($payment->payment_method ?? '');
            if (str_contains($method, 'paypal')) {
                return 'paypal';
            }
            if (str_contains($method, 'paddle')) {
                return 'paddle';
            }
            if (str_contains($method, 'stripe') || str_contains($method, 'credit') || str_contains($method, 'card')) {
                return 'stripe';
            }

            $authCode = $payment->authorization_code ?? '';
            if (str_starts_with($authCode, 'I-')) {
                return 'paypal';
            }
            if (str_starts_with($authCode, 'sub_') || str_starts_with($authCode, 'pi_')) {
                return 'stripe';
            }
            if (str_starts_with($authCode, 'txn_')) {
                return 'paddle';
            }
        }

        // Check variant config
        $variant = $orderDetail->variant;
        if ($variant) {
            if (!empty($variant->paypal_sandbox_plan_id) || !empty($variant->paypal_live_plan_id)) {
                return 'paypal';
            }
            if (!empty($variant->stripe_sandbox_price_id) || !empty($variant->stripe_live_price_id) || $variant->create_new_stripe_product) {
                return 'stripe';
            }
            if (!empty($variant->paddle_sandbox_price_id) || !empty($variant->paddle_live_price_id) || !empty($variant->paddle_interval)) {
                return 'paddle';
            }
        }

        return 'unknown';
    }

    /**
     * Extract the gateway subscription ID.
     */
    public function determineSubscriptionId(OrderDetail $orderDetail): ?string
    {
        $planId = $orderDetail->subscription_plan_id;

        // 1. If already a valid gateway subscription identifier
        if (!empty($planId) && (str_starts_with($planId, 'sub_') || str_starts_with($planId, 'I-') || str_starts_with($planId, 'P-'))) {
            return $planId;
        }

        // 2. Check OrderPayment authorization_code or processor_response
        $payment = OrderPayment::where('order_id', $orderDetail->order_id)
            ->where(function ($q) {
                $q->where('authorization_code', 'like', 'sub_%')
                  ->orWhere('authorization_code', 'like', 'I-%')
                  ->orWhere('authorization_code', 'like', 'txn_%')
                  ->orWhere('processor_response', 'like', '%Subscription:%');
            })
            ->first();

        if ($payment) {
            $authCode = $payment->authorization_code ?? '';
            if (str_starts_with($authCode, 'sub_') || str_starts_with($authCode, 'I-')) {
                return $authCode;
            }

            // Parse "Subscription: sub_xxx" from processor_response
            if (preg_match('/Subscription:\s*(sub_[a-zA-Z0-9_]+|I-[a-zA-Z0-9]+)/i', $payment->processor_response ?? '', $matches)) {
                return $matches[1];
            }
        }

        // 3. Fall back to existing subscription_plan_id (e.g. pm_xxx, pi_xxx) which the processor can resolve
        if (!empty($planId)) {
            return $planId;
        }

        // 4. Fall back to user stripe_customer_id if available
        $user = $orderDetail->order?->user;
        if ($user && !empty($user->stripe_customer_id)) {
            return $user->stripe_customer_id;
        }

        return null;
    }

    protected function cancelStripe(string $subscriptionId): void
    {
        $driver = $this->paymentManager->resolve(1);
        if ($driver instanceof StripeProcessor) {
            $driver->cancelSubscription($subscriptionId);
        } else {
            $sandbox = $this->paymentManager->activeProcessorIsSandbox(1);
            (new StripeProcessor(sandbox: $sandbox))->cancelSubscription($subscriptionId);
        }
    }

    protected function cancelPaddle(string $subscriptionId): void
    {
        $driver = $this->paymentManager->resolve(2);
        if ($driver instanceof PaddleProcessor) {
            $driver->cancelSubscription($subscriptionId);
        } else {
            $sandbox = $this->paymentManager->activeProcessorIsSandbox(2);
            (new PaddleProcessor(sandbox: $sandbox))->cancelSubscription($subscriptionId);
        }
    }

    protected function cancelPayPal(string $subscriptionId, string $reason): void
    {
        $driver = $this->paymentManager->resolve(3);
        if ($driver instanceof PayPalProcessor) {
            $driver->cancelSubscription($subscriptionId, $reason);
        } else {
            $sandbox = $this->paymentManager->activeProcessorIsSandbox(3);
            (new PayPalProcessor(sandbox: $sandbox))->cancelSubscription($subscriptionId, $reason);
        }
    }
}
