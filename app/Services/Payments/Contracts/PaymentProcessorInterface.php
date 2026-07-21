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
}
