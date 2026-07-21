<?php

/**
 * ╔═══════════════════════════════════════════════════════════════════════════╗
 * ║  Paddle Extension Template — payment-processors/paddle/                   ║
 * ╚═══════════════════════════════════════════════════════════════════════════╝
 *
 * This file is an EXTENSION TEMPLATE. It is NOT active by default.
 *
 * HOW TO ACTIVATE
 * ───────────────
 * Save (or rename/copy) this file as:
 *
 *   payment-processors/paddle/PaddleProcessorExtension.php
 *
 * The platform auto-detects that file and uses this class instead of the
 * built-in PaddleProcessor — no changes to config/payment_processors.php needed.
 *
 * HOW IT WORKS
 * ────────────
 * This class EXTENDS the built-in PaddleProcessor, so all existing behaviour
 * is inherited automatically. Override only the methods you want to change.
 *
 * Built-in class:   app/Services/Payments/Processors/PaddleProcessor.php
 * Namespace:        PaymentProcessors\Paddle   (must stay exactly this)
 * Class name:       PaddleProcessorExtension   (must stay exactly this)
 */

namespace PaymentProcessors\Paddle;

use App\Services\Payments\Processors\PaddleProcessor as BasePaddleProcessor;
use App\Services\Payments\PaymentResult;

class PaddleProcessorExtension extends BasePaddleProcessor
{
    // ─────────────────────────────────────────────────────────────────────────
    // Override Examples — uncomment and customise what you need
    // ─────────────────────────────────────────────────────────────────────────

    // EXAMPLE 1: Add custom metadata to every Paddle transaction
    //
    // public function createTransaction(float $amount, string $currency = 'USD', array $meta = []): array
    // {
    //     // Inject a custom field into every transaction's custom_data
    //     $meta['my_store_ref'] = config('app.name');
    //     return parent::createTransaction($amount, $currency, $meta);
    // }

    // EXAMPLE 2: Override display name shown in order records
    //
    // public function getName(): string
    // {
    //     return 'My Store Billing' . ($this->isSandbox() ? ' (Test)' : '');
    // }

    // EXAMPLE 3: Override client() for advanced SDK configuration
    //
    // protected function client(): \Paddle\SDK\Client
    // {
    //     $apiKey = $this->isSandbox()
    //         ? env('PADDLE_SANDBOX_API_KEY')
    //         : env('PADDLE_API_KEY');
    //     return new \Paddle\SDK\Client(
    //         apiKey:  $apiKey,
    //         options: new \Paddle\SDK\Options(
    //             sandbox: $this->isSandbox(),
    //             logLevel: \Psr\Log\LogLevel::DEBUG,
    //         ),
    //     );
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
    //   charge()               — verifies Paddle transaction, returns PaymentResult
    //   isSandbox()            — true when sandbox/test mode
    //   getName()              — 'Paddle' or 'Paddle (Sandbox)'
    //   getClientToken()       — client-side token for Paddle.js
    //   getEnvironment()       — 'sandbox' or 'production'
    //   createTransaction()    — creates Paddle Billing transaction for checkout
    //   client()  [protected]  — returns configured Paddle SDK Client
    // ─────────────────────────────────────────────────────────────────────────
}
