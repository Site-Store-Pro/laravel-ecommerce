# Paddle — Built-in Payment Processor

Paddle is a **built-in default processor** (processor_id = 2). It is always registered and
available — no changes to `config/payment_processors.php` are needed.

---

## Quick Setup

### 1. Install the SDK

```bash
composer require paddlehq/paddle-php-sdk
```

### 2. Add credentials to `.env`

```env
# ── Production (vendors.paddle.com) ─────────────────────────────────────────
PADDLE_API_KEY=your_live_api_key
PADDLE_CLIENT_TOKEN=your_live_client_token
PADDLE_WEBHOOK_SECRET=pdl_ntf_xxxxxxxxxxxxxxxx

# ── Sandbox (sandbox-vendors.paddle.com) ────────────────────────────────────
PADDLE_SANDBOX_API_KEY=your_sandbox_api_key
PADDLE_SANDBOX_CLIENT_TOKEN=your_sandbox_client_token
```

### 3. Enable in Admin

`Admin → Checkout → Processors` → Set Paddle as **Primary** → Toggle **Production** on/off.

### 4. Register Webhook (required for subscription and customer ID callbacks)

In the [Paddle Dashboard](https://vendors.paddle.com/notifications):

`Developer Tools → Notifications → New Destination`

```
URL:  https://yourdomain.com/webhooks/paddle
```

Events to subscribe to:
- `transaction.completed`
- `transaction.payment_failed`
- `customer.created`
- `subscription.created`
- `subscription.updated`
- `subscription.canceled`

After creating the destination, copy the notification secret into `.env` as `PADDLE_WEBHOOK_SECRET`.

---

## Extending the Built-in Paddle Processor

To add or override behaviour **without editing core files**, create an extension class:

### File to create

```
payment-processors/paddle/PaddleProcessorExtension.php
```

> **This file is auto-detected.** If it exists, it is loaded automatically and replaces
> the built-in `PaddleProcessor` for processor_id = 2. No config changes needed.

### Template

Use `PaddleProcessor.php` in this folder as your starting template. Copy it to
`PaddleProcessorExtension.php` and uncomment the examples you need.

The class **must**:
- Be in namespace `PaymentProcessors\Paddle`
- Be named `PaddleProcessorExtension`
- Extend `App\Services\Payments\Processors\PaddleProcessor`

### Example extension skeleton

```php
<?php

namespace PaymentProcessors\Paddle;

use App\Services\Payments\Processors\PaddleProcessor as BasePaddleProcessor;

class PaddleProcessorExtension extends BasePaddleProcessor
{
    // Override only what you need. All base methods are inherited.

    public function getName(): string
    {
        return 'My Custom Paddle' . ($this->isSandbox() ? ' (Test)' : '');
    }

    public function createTransaction(float $amount, string $currency = 'USD', array $meta = []): array
    {
        // Inject custom metadata into every transaction
        $meta['my_store_ref'] = config('app.name');
        return parent::createTransaction($amount, $currency, $meta);
    }
}
```

---

## Architecture

| File | Purpose |
|---|---|
| `app/Services/Payments/Processors/PaddleProcessor.php` | Built-in base class (always loaded via PSR-4) |
| `payment-processors/paddle/PaddleProcessorExtension.php` | Your customisation (auto-detected if it exists) |
| `app/Http/Controllers/PaddleWebhookController.php` | Webhook event handler |
