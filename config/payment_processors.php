<?php

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * Payment Processor Registry  —  config/payment_processors.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * Maps processor_id (from the `order_processors` database table) to a
 * driver class that implements PaymentProcessorInterface.
 *
 * ┌─ Built-in processors (always active, no configuration needed) ──────────┐
 * │  ID 0 — Test Processor   (no SDK or credentials needed)                 │
 * │  ID 1 — Stripe           (requires stripe/stripe-php SDK + .env keys)   │
 * │  ID 2 — Paddle           (requires paddlehq/paddle-php-sdk + .env keys) │
 * │  ID 3 — PayPal           (requires .env credentials)                    │
 * │                                                                          │
 * │  Stripe, Paddle and PayPal are PSR-4 autoloaded from:                    │
 * │    app/Services/Payments/Processors/StripeProcessor.php                 │
 * │    app/Services/Payments/Processors/PaddleProcessor.php                 │
 * │    app/Services/Payments/Processors/PayPalProcessor.php                 │
 * │                                                                          │
 * │  To enable Stripe, Paddle or PayPal in the Admin panel:                  │
 * │    1. Add credentials to .env (see below).                               │
 * │    2. In Admin → Checkout → Processors, set as Primary and toggle        │
 * │       Production on/off as needed.                                       │
 * └──────────────────────────────────────────────────────────────────────────┘
 *
 * ┌─ Extending Stripe, Paddle or PayPal (auto-detected) ────────────────────┐
 * │  Create one of these files to add or override behaviour:                │
 * │                                                                          │
 * │    payment-processors/stripe/StripeProcessorExtension.php               │
 * │    payment-processors/paddle/PaddleProcessorExtension.php               │
 * │    payment-processors/paypal/PayPalProcessorExtension.php               │
 * │                                                                          │
 * │  If any of these files exist, it is loaded automatically and replaces   │
 * │  the built-in class for that processor — no further changes needed here. │
 * │  See each folder's README.md for a full extension template.              │
 * └──────────────────────────────────────────────────────────────────────────┘
 *
 * ┌─ Adding a brand-new custom processor ───────────────────────────────────┐
 * │  1. Create:  payment-processors/my-gateway/MyGatewayProcessor.php       │
 * │  2. Implement \App\Services\Payments\Contracts\PaymentProcessorInterface │
 * │  3. Add two lines below the divider in this file:                        │
 * │       require_once base_path('payment-processors/my-gateway/...');       │
 * │       $processors[3] = \PaymentProcessors\MyGateway\MyProcessor::class; │
 * │     where N matches the processor_id in the order_processors table.      │
 * │  4. Add credentials to .env (see the example-gateway README for format). │
 * │  5. In Admin → Checkout → Processors, set as Primary.                   │
 * │  See payment-processors/example-gateway/README.md for a full template.  │
 * └──────────────────────────────────────────────────────────────────────────┘
 *
 * ┌─ Sandbox vs Production ─────────────────────────────────────────────────┐
 * │  Each processor row in `order_processors` has a `production` column.    │
 * │  • production = 0  →  sandbox/test credentials used                    │
 * │  • production = 1  →  live/production credentials used                 │
 * └─────────────────────────────────────────────────────────────────────────┘
 */

// ─── Built-in processors (always registered, no require_once needed) ───────

$processors = [
    0 => \App\Services\Payments\Processors\TestProcessor::class,
    1 => \App\Services\Payments\Processors\StripeProcessor::class,
    2 => \App\Services\Payments\Processors\PaddleProcessor::class,
    3 => \App\Services\Payments\Processors\PayPalProcessor::class,
];

// ─── Auto-detect Stripe extension override ──────────────────────────────────
// If payment-processors/stripe/StripeProcessorExtension.php exists, it is
// loaded automatically and replaces the built-in StripeProcessor for ID=1.
// The extension class must extend App\Services\Payments\Processors\StripeProcessor.
// File name must be exactly: StripeProcessorExtension.php

$_stripeExtension = base_path('payment-processors/stripe/StripeProcessorExtension.php');
if (file_exists($_stripeExtension)) {
    require_once $_stripeExtension;
    $processors[1] = \PaymentProcessors\Stripe\StripeProcessorExtension::class;
}

// ─── Auto-detect Paddle extension override ──────────────────────────────────
// If payment-processors/paddle/PaddleProcessorExtension.php exists, it is
// loaded automatically and replaces the built-in PaddleProcessor for ID=2.
// The extension class must extend App\Services\Payments\Processors\PaddleProcessor.
// File name must be exactly: PaddleProcessorExtension.php

$_paddleExtension = base_path('payment-processors/paddle/PaddleProcessorExtension.php');
if (file_exists($_paddleExtension)) {
    require_once $_paddleExtension;
    $processors[2] = \PaymentProcessors\Paddle\PaddleProcessorExtension::class;
}

// ─── Auto-detect PayPal extension override ──────────────────────────────────
// If payment-processors/paypal/PayPalProcessorExtension.php exists, it is
// loaded automatically and replaces the built-in PayPalProcessor for ID=3.
// The extension class must extend App\Services\Payments\Processors\PayPalProcessor.
// File name must be exactly: PayPalProcessorExtension.php

$_paypalExtension = base_path('payment-processors/paypal/PayPalProcessorExtension.php');
if (file_exists($_paypalExtension)) {
    require_once $_paypalExtension;
    $processors[3] = \PaymentProcessors\PayPal\PayPalProcessorExtension::class;
}

// ─── Add further custom processors below this line ──────────────────────────
// Follow the same pattern: require_once + $processors[N] = ClassName::class;
// Use processor IDs starting at 100 (IDs 0 to 99 are reserved).
//
// Example (see payment-processors/example-gateway/README.md for full guide):
// require_once base_path('payment-processors/example-gateway/ExampleGatewayProcessor.php');
// $processors[100] = \PaymentProcessors\ExampleGateway\ExampleGatewayProcessor::class;

return $processors;
