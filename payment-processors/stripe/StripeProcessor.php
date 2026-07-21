<?php

/**
 * ╔═══════════════════════════════════════════════════════════════════════════╗
 * ║  Stripe Extension Template — payment-processors/stripe/                   ║
 * ╚═══════════════════════════════════════════════════════════════════════════╝
 *
 * This file is an EXTENSION TEMPLATE. It is NOT active by default.
 *
 * HOW TO ACTIVATE
 * ───────────────
 * Save (or rename/copy) this file as:
 *
 *   payment-processors/stripe/StripeProcessorExtension.php
 *
 * The platform auto-detects that file and uses this class instead of the
 * built-in StripeProcessor — no changes to config/payment_processors.php needed.
 *
 * HOW IT WORKS
 * ────────────
 * This class EXTENDS the built-in StripeProcessor, so all existing behaviour
 * is inherited automatically. Override only the methods you want to change.
 *
 * Built-in class:   app/Services/Payments/Processors/StripeProcessor.php
 * Namespace:        PaymentProcessors\Stripe   (must stay exactly this)
 * Class name:       StripeProcessorExtension   (must stay exactly this)
 */

namespace PaymentProcessors\Stripe;

use App\Services\Payments\Processors\StripeProcessor as BaseStripeProcessor;
use App\Services\Payments\PaymentResult;

class StripeProcessorExtension extends BaseStripeProcessor
{
    // ─────────────────────────────────────────────────────────────────────────
    // Override Examples — uncomment and customise what you need
    // ─────────────────────────────────────────────────────────────────────────

    // EXAMPLE 1: Add custom metadata / logging after PaymentIntent creation
    //
    // public function createPaymentIntent(float $amount, string $currency = 'usd'): array
    // {
    //     $result = parent::createPaymentIntent($amount, $currency);
    //     \Illuminate\Support\Facades\Log::info('[Stripe] PaymentIntent: ' . $result['payment_intent_id']);
    //     return $result;
    // }

    // EXAMPLE 2: Override display name shown in order records
    //
    // public function getName(): string
    // {
    //     return 'My Store Payments' . ($this->isSandbox() ? ' (Test)' : '');
    // }

    // EXAMPLE 3: Override client() for advanced SDK configuration
    //
    // protected function client(): \Stripe\StripeClient
    // {
    //     return new \Stripe\StripeClient([
    //         'api_key'             => $this->isSandbox()
    //                                      ? env('STRIPE_SANDBOX_SECRET_KEY')
    //                                      : env('STRIPE_SECRET_KEY'),
    //         'stripe_version'      => '2024-06-20',
    //         'max_network_retries' => 2,
    //     ]);
    // }

    // EXAMPLE 4: Add post-payment logic (analytics, events, etc.)
    //
    // public function charge(float $amount, string $currency, array $payload): PaymentResult
    // {
    //     $result = parent::charge($amount, $currency, $payload);
    //     if ($result->success) {
    //         // fire event, call analytics API, etc.
    //     }
    //     return $result;
    // }

    // ─────────────────────────────────────────────────────────────────────────
    // Inherited methods (no need to re-implement unless overriding):
    //   charge()               — verifies PaymentIntent, returns PaymentResult
    //   isSandbox()            — true when sandbox/test mode
    //   getName()              — 'Stripe' or 'Stripe (Sandbox)'
    //   getPublishableKey()    — publishable key for Stripe.js
    //   createPaymentIntent()  — creates PaymentIntent for one-time checkout
    //   createSubscription()   — creates Subscription for recurring billing
    //   client()  [protected]  — returns configured StripeClient
    // ─────────────────────────────────────────────────────────────────────────
}
