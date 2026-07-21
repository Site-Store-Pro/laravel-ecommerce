# Stripe — Built-in Payment Processor

Stripe is a **built-in default processor** (processor_id = 1). It is always registered and
available — no changes to `config/payment_processors.php` are needed.

---

## Quick Setup

### 1. Install the SDK

```bash
composer require stripe/stripe-php
```

### 2. Add credentials to `.env`

```env
# ── Production ─────────────────────────────────────────────────────────────
STRIPE_PUBLISHABLE_KEY=pk_live_xxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET_KEY=sk_live_xxxxxxxxxxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxx

# ── Sandbox / Test ──────────────────────────────────────────────────────────
STRIPE_SANDBOX_PUBLISHABLE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxx
STRIPE_SANDBOX_SECRET_KEY=sk_test_xxxxxxxxxxxxxxxxxxxx
```

### 3. Enable in Admin

`Admin → Checkout → Processors` → Set Stripe as **Primary** → Toggle **Production** on/off.

### 4. Register Webhook (required for subscription and refund events)

In the [Stripe Dashboard](https://dashboard.stripe.com/webhooks), add an endpoint:

```
https://yourdomain.com/webhooks/stripe
```

Events to listen for:
- `payment_intent.succeeded`
- `payment_intent.payment_failed`
- `charge.refunded`
- `customer.created`
- `customer.subscription.created`
- `customer.subscription.updated`
- `customer.subscription.deleted`
- `invoice.payment_succeeded`
- `invoice.payment_failed`

After adding the endpoint, copy the signing secret into `.env` as `STRIPE_WEBHOOK_SECRET`.

---

## Extending the Built-in Stripe Processor

To add or override behaviour **without editing core files**, create an extension class:

### File to create

```
payment-processors/stripe/StripeProcessorExtension.php
```

> **This file is auto-detected.** If it exists, it is loaded automatically and replaces
> the built-in `StripeProcessor` for processor_id = 1. No config changes needed.

### Template

Use `StripeProcessor.php` in this folder as your starting template. Copy it to
`StripeProcessorExtension.php` and uncomment the examples you need.

The class **must**:
- Be in namespace `PaymentProcessors\Stripe`
- Be named `StripeProcessorExtension`
- Extend `App\Services\Payments\Processors\StripeProcessor`

### Example extension skeleton

```php
<?php

namespace PaymentProcessors\Stripe;

use App\Services\Payments\Processors\StripeProcessor as BaseStripeProcessor;

class StripeProcessorExtension extends BaseStripeProcessor
{
    // Override only what you need. All base methods are inherited.

    public function getName(): string
    {
        return 'My Custom Stripe' . ($this->isSandbox() ? ' (Test)' : '');
    }

    public function createPaymentIntent(float $amount, string $currency = 'usd'): array
    {
        $result = parent::createPaymentIntent($amount, $currency);
        // Add your custom logic here (logging, metadata, etc.)
        return $result;
    }
}
```

---

## Architecture

| File | Purpose |
|---|---|
| `app/Services/Payments/Processors/StripeProcessor.php` | Built-in base class (always loaded via PSR-4) |
| `payment-processors/stripe/StripeProcessorExtension.php` | Your customisation (auto-detected if it exists) |
| `app/Http/Controllers/StripeWebhookController.php` | Webhook event handler |
