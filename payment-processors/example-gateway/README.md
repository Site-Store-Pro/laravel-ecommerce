# Example Gateway — Custom Processor Template

This directory is a **template** for adding a completely new (third-party) payment gateway.
It is not active by default because it does not contain a `processor.json` manifest.

---

## When to use this vs. the Stripe/Paddle/PayPal extension

| Scenario | What to do |
|---|---|
| Customise or extend Stripe | Use `payment-processors/stripe/StripeProcessorExtension.php` (auto-detected) |
| Customise or extend Paddle | Use `payment-processors/paddle/PaddleProcessorExtension.php` (auto-detected) |
| Customise or extend PayPal | Use `payment-processors/paypal/PayPalProcessorExtension.php` (auto-detected) |
| Add a brand-new drop-in gateway (e.g. Square, Braintree, Authorize.net) | Follow the Auto-Discovery Plugin steps below (see `payment-processors/square/` for a complete working example) |

---

## Drop-In Auto-Discovery Payment Plugin (Recommended)

New payment processors placed in `payment-processors/<slug>/` with a `processor.json` manifest and implementing `PaymentPluginInterface` are **automatically discovered, registered in the database, rendered at checkout, refunded from Admin Order Details, and routed for webhooks** without editing any core checkout or Admin files.

### 1. Create your plugin directory

```
payment-processors/my-gateway/
├── processor.json           ← Auto-discovery manifest
├── MyGatewayProcessor.php   ← Main driver (implements PaymentPluginInterface)
├── checkout.blade.php       ← Self-contained checkout card UI + JS hooks
└── README.md                ← Setup notes & credentials
```

### 2. Create `processor.json`

Use a unique `processor_id` of `100` or higher (`0`–`3` are reserved for built-in processors):

```json
{
    "processor_id": 105,
    "name": "My Gateway",
    "type": "my-gateway",
    "class": "PaymentProcessors\\MyGateway\\MyGatewayProcessor",
    "file": "MyGatewayProcessor.php",
    "production_default": 0,
    "description": "My Gateway payment processor plugin"
}
```

On application boot, `PluginServiceProvider` calls `PaymentProcessorManager::syncDiscoveredProcessors()`, which automatically creates the row in `order_processors` (`processor_id = 105`) if it does not already exist. It immediately appears in **Admin → Checkout → Payment Processors**.

### 3. Implement `PaymentPluginInterface`

`PaymentPluginInterface` extends `PaymentProcessorInterface` (`charge()`, `refund()`, `isSandbox()`, `getName()`) with lifecycle hooks for checkout, refunds, and webhooks:

```php
namespace PaymentProcessors\MyGateway;

use App\Models\OrderPayment;
use App\Services\Payments\Contracts\PaymentPluginInterface;
use App\Services\Payments\PaymentResult;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MyGatewayProcessor implements PaymentPluginInterface
{
    public function __construct(private readonly bool $sandbox = true) {}

    public function getTypeSlug(): string { return 'my-gateway'; }
    public function isSandbox(): bool { return $this->sandbox; }
    public function getName(): string { return 'My Gateway'; }

    public function prepareCheckout(float $total, string $currency, int $userId): array
    {
        return ['ready' => true];
    }

    public function buildChargePayload(string $paymentToken, array $context = []): array
    {
        return ['token' => $paymentToken];
    }

    public function charge(float $amount, string $currency, array $payload): PaymentResult
    {
        // Call gateway charge API...
    }

    public function refund(OrderPayment $payment, float $amount, string $currency = 'USD'): PaymentResult
    {
        // Call gateway refund API...
    }

    public function getCheckoutViewPath(): ?string
    {
        return __DIR__ . '/checkout.blade.php';
    }

    public function getCheckoutViewData(float $total, string $currency): array
    {
        return ['total' => $total, 'currency' => $currency];
    }

    public function matchesPayment(OrderPayment $payment): bool
    {
        return str_contains(strtolower((string) $payment->payment_method), 'my gateway');
    }

    public function handleWebhook(Request $request): Response
    {
        return response()->json(['status' => 'ok']);
    }
}
```

### 4. Create `checkout.blade.php`

Your Blade view renders the payment container and registers `window.PaymentProcessorPlugins['my-gateway']` with:
- `init(component)`: Called on checkout page init.
- `reboot(component)`: Called when Livewire updates the DOM (e.g., promo code applied).
- `handlePlaceOrder(component)`: Tokenizes the payment method and calls `await component.$wire.placeOrder(token)` to finalize the order.

### 5. Webhooks

Your plugin automatically receives webhook `POST` requests at:
```
POST /webhooks/payment/my-gateway
```
which delegates directly to `MyGatewayProcessor::handleWebhook(Request $request)`.

### 6. Enable in Admin

Navigate to **Admin → Checkout → Processors** (`/admin/checkout/processors`), select **My Gateway** as Primary (or Secondary/Tertiary), and toggle Sandbox/Production mode.
