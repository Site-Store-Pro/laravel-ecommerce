<?php

/**
 * ╔═══════════════════════════════════════════════════════════════════════════╗
 * ║  Example Gateway — Custom Processor Template                              ║
 * ║  payment-processors/example-gateway/ExampleGatewayProcessor.php          ║
 * ╚═══════════════════════════════════════════════════════════════════════════╝
 *
 * This is a TEMPLATE for adding a brand-new payment gateway.
 * It is NOT active — see README.md for activation steps.
 *
 * STEP 1: Copy this directory to payment-processors/my-gateway/
 * STEP 2: Rename ExampleGatewayProcessor.php to MyGatewayProcessor.php
 * STEP 3: Update the namespace and class name below
 * STEP 4: Implement the methods
 * STEP 5: Register in config/payment_processors.php (see comment at bottom of that file)
 * STEP 6: In Admin → Checkout → Processors, set as Primary
 */

namespace PaymentProcessors\ExampleGateway;

use App\Services\Payments\Contracts\PaymentProcessorInterface;
use App\Services\Payments\PaymentResult;

class ExampleGatewayProcessor implements PaymentProcessorInterface
{
    /**
     * @param bool $sandbox  True = sandbox/test mode, False = production mode.
     *                       This value is read from order_processors.production in the DB.
     */
    public function __construct(private readonly bool $sandbox) {}

    // ─────────────────────────────────────────────────────────────────────────
    // Required: PaymentProcessorInterface
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Verify and finalise a payment after the client-side gateway has
     * completed checkout.
     *
     * By the time this is called, the client has already confirmed the payment.
     * Use $payload to receive the gateway's transaction/reference ID from the
     * frontend JavaScript.
     *
     * EXAMPLE payload shapes:
     *   Stripe  → ['payment_intent_id' => 'pi_xxx']
     *   Paddle  → ['transaction_id'    => 'txn_xxx']
     *   Custom  → ['reference_id'      => 'REF123']  ← define your own key
     *
     * Return a PaymentResult indicating success or failure.
     */
    public function charge(float $amount, string $currency, array $payload): PaymentResult
    {
        $referenceId = $payload['reference_id'] ?? null;

        if (empty($referenceId)) {
            return new PaymentResult(
                success:           false,
                authorizationCode: '',
                errorMessage:      'No reference ID received from frontend.',
                processorName:     $this->getName(),
            );
        }

        try {
            // TODO: Call your gateway SDK / API to verify $referenceId
            // $gateway = $this->client();
            // $transaction = $gateway->transactions->verify($referenceId);
            // if ($transaction->status !== 'paid') { ... }

            // Stub: always succeed for testing
            return new PaymentResult(
                success:           true,
                authorizationCode: $referenceId,
                transactionId:     $referenceId,
                processorName:     $this->getName(),
            );

        } catch (\Throwable $e) {
            return new PaymentResult(
                success:           false,
                authorizationCode: '',
                errorMessage:      'Payment verification failed: ' . $e->getMessage(),
                processorName:     $this->getName(),
            );
        }
    }

    /**
     * Return true when using sandbox/test credentials.
     * The PaymentProcessorManager sets this based on order_processors.production.
     */
    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    /**
     * Human-readable processor name stored in order payment records.
     */
    public function getName(): string
    {
        return 'Example Gateway' . ($this->sandbox ? ' (Sandbox)' : '');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Gateway-specific methods
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Optional: return the client-side token/key needed by your gateway's JS.
     * Call this from OrderReview::preparePayment() when $type === 'example'.
     */
    public function getClientToken(): string
    {
        return $this->sandbox
            ? (env('EXAMPLE_GATEWAY_SANDBOX_KEY') ?? '')
            : (env('EXAMPLE_GATEWAY_KEY') ?? '');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Build and return a configured SDK/API client.
     * Replace this with your actual gateway SDK instantiation.
     */
    private function client(): object
    {
        // TODO: Replace with your gateway SDK
        // Example:
        // return new \MyGateway\Client(
        //     apiKey: $this->sandbox
        //         ? env('EXAMPLE_GATEWAY_SANDBOX_API_KEY')
        //         : env('EXAMPLE_GATEWAY_API_KEY'),
        // );
        throw new \RuntimeException('ExampleGatewayProcessor::client() is not implemented yet.');
    }
}
