# Example Gateway — Custom Processor Template

This directory is a **template** for adding a completely new (third-party) payment gateway.
It is not active by default.

---

## When to use this vs. the Stripe/Paddle extension

| Scenario | What to do |
|---|---|
| Customise or extend Stripe | Use `payment-processors/stripe/StripeProcessorExtension.php` (auto-detected) |
| Customise or extend Paddle | Use `payment-processors/paddle/PaddleProcessorExtension.php` (auto-detected) |
| Add a brand-new gateway (e.g. Braintree, Square, Authorize.net) | Follow the steps below |

---

## Steps to add a new custom processor

### 1. Copy this directory

```bash
cp -r payment-processors/example-gateway payment-processors/my-gateway
```

### 2. Rename the PHP file

```
payment-processors/my-gateway/MyGatewayProcessor.php
```

### 3. Update namespace and class name

```php
namespace PaymentProcessors\MyGateway;

class MyGatewayProcessor implements PaymentProcessorInterface
```

### 4. Implement the three required interface methods

```php
public function charge(float $amount, string $currency, array $payload): PaymentResult;
public function isSandbox(): bool;
public function getName(): string;
```

`charge()` is called after the client-side JS has completed checkout. Use `$payload` to
receive the gateway''s transaction reference from the browser (via Livewire).

### 5. Add credentials to `.env`

```env
MY_GATEWAY_API_KEY=your_live_key
MY_GATEWAY_SANDBOX_API_KEY=your_sandbox_key
```

### 6. Insert the processor row into the database

The processor needs a row in `order_processors` matching your chosen `processor_id`.
Use an ID of 3 or higher (0, 1, and 2 are reserved for Test, Stripe, and Paddle).

```sql
INSERT INTO order_processors (processor_id, processor_name, production, created_at, updated_at)
VALUES (3, 'My Gateway', 0, NOW(), NOW());
```

Or run a seeder / migration that inserts this row.

### 7. Register in `config/payment_processors.php`

Add these two lines in the "custom processors" section at the bottom of the file:

```php
require_once base_path('payment-processors/my-gateway/MyGatewayProcessor.php');
$processors[3] = \PaymentProcessors\MyGateway\MyGatewayProcessor::class;
```

> **Note:** The processor ID here (3) must match the `processor_id` value in `order_processors`.

### 8. Handle the frontend JS checkout

In `OrderReview::preparePayment()`, add a new `elseif ($type === 'my-gateway')` branch
that calls your processor''s client-token method and passes the data to your JS widget.

Update `PaymentProcessorManager::activeProcessorType()` to return the type string:

```php
return match ($this->activeProcessorId()) {
    1       => 'stripe',
    2       => 'paddle',
    3       => 'my-gateway',   // <-- add this
    default => 'test',
};
```

### 9. Enable in Admin

`Admin → Checkout → Processors` → Set **My Gateway** as Primary → Toggle Production on/off.

---

## Files in a complete processor package

```
payment-processors/my-gateway/
├── MyGatewayProcessor.php   ← Main driver (implements PaymentProcessorInterface)
└── README.md                ← Credentials, setup notes, webhook info
```

---

## Interface Contract

```php
// app/Services/Payments/Contracts/PaymentProcessorInterface.php

interface PaymentProcessorInterface
{
    public function charge(float $amount, string $currency, array $payload): PaymentResult;
    public function isSandbox(): bool;
    public function getName(): string;
}
```

The `PaymentResult` constructor accepts:
```php
new PaymentResult(
    success:           bool,
    authorizationCode: string,
    transactionId:     string  = '',
    errorMessage:      string  = '',
    processorName:     string  = '',
);
```
