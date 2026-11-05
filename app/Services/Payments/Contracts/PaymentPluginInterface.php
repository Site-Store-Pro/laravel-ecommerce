<?php

namespace App\Services\Payments\Contracts;

use App\Models\OrderPayment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Extended contract for stand-alone, auto-discovered payment processor plugins
 * located in `payment-processors/{slug}/`.
 *
 * Extends PaymentProcessorInterface so all existing type-hints and built-in
 * processors (Test, Stripe, Paddle, PayPal) remain 100% compatible.
 */
interface PaymentPluginInterface extends PaymentProcessorInterface
{
    /**
     * Unique frontend processor type slug (e.g. 'square').
     * Used by PaymentProcessorManager::activeProcessorType() and OrderReview.
     */
    public function getTypeSlug(): string;

    /**
     * Prepare client-side checkout data for OrderReview::preparePayment().
     * Must return an array containing at least ['processor' => $this->getTypeSlug(), ...],
     * or ['error' => 'message'] if preparation fails.
     *
     * @param  float  $amount   Order total in major currency units (e.g. 25.50)
     * @param  string $currency ISO 4217 currency code (e.g. 'usd' or 'USD')
     * @param  array  $context  Additional checkout context (user, items, totals, etc.)
     * @return array<string, mixed>
     */
    public function prepareCheckout(float $amount, string $currency, array $context = []): array;

    /**
     * Build the $payload array passed to charge() from the frontend $gatewayToken
     * during OrderReview::placeOrder().
     *
     * @param  string $gatewayToken Token/nonce returned from the browser SDK
     * @param  array  $context      Additional order context (user, totals, etc.)
     * @return array<string, mixed>
     */
    public function buildChargePayload(string $gatewayToken, array $context = []): array;

    /**
     * Return the absolute filesystem path to the plugin's checkout Blade partial
     * rendered inside the Payment Method card on the Order Review page (or null if none).
     */
    public function getCheckoutViewPath(): ?string;

    /**
     * Return view variables passed to the plugin's checkout Blade template
     * during OrderReview::render().
     *
     * @param  float  $total    Current order total
     * @param  string $currency ISO 4217 currency code
     * @return array<string, mixed>
     */
    public function getCheckoutViewData(float $total, string $currency): array;

    /**
     * Determine whether a historical OrderPayment record belongs to this processor
     * so PaymentRefundService can route admin refunds automatically.
     */
    public function matchesPayment(OrderPayment $payment): bool;

    /**
     * Handle incoming webhook requests at POST /webhooks/payment/{slug} or /webhooks/{slug}.
     */
    public function handleWebhook(Request $request): Response;
}
