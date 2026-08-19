<?php

namespace App\Services\Payments\Contracts;

use App\Services\Payments\PaymentResult;

interface PaymentProcessorInterface
{
    /**
     * Record / finalise a payment after the client-side gateway has already
     * authorised the charge (Stripe, Paddle) or run a simulation (Test).
     *
     * $payload carries any gateway-specific data forwarded from the frontend:
     *   - Stripe:  ['payment_intent_id' => 'pi_xxx']
     *   - Paddle:  ['transaction_id'    => 'txn_xxx']
     *   - Test:    []
     */
    public function charge(float $amount, string $currency, array $payload): PaymentResult;

    /**
     * Return true when credentials are pointing at a sandbox / test environment.
     */
    public function isSandbox(): bool;

    /**
     * Human-readable name used in order payment records.
     */
    public function getName(): string;

    /**
     * Process a partial or full refund with the payment processor API.
     *
     * @param  string      $transactionId Gateway authorization code / transaction ID / payment intent
     * @param  float       $amount        Refund amount in major currency units (e.g. 25.50)
     * @param  string|null $reason        Optional admin reason/note
     * @param  string      $currency      ISO 4217 currency code (default 'USD')
     */
    public function refund(string $transactionId, float $amount, ?string $reason = null, string $currency = 'USD'): PaymentResult;
}
