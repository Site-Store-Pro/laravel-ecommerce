<?php

namespace App\Services\Payments;

/**
 * Immutable result DTO returned by every payment processor driver.
 */
final class PaymentResult
{
    public function __construct(
        /** Whether the gateway authorised / confirmed the payment. */
        public readonly bool   $success,

        /** Short auth / approval code to store in order_payments.authorization_code */
        public readonly string $authorizationCode,

        /** Gateway transaction / payment-intent ID for order_payments.processor_response */
        public readonly string $transactionId = '',

        /** Human-readable error message shown to the customer on failure. */
        public readonly ?string $errorMessage  = null,

        /** Processor display name written to order_payments.payment_method */
        public readonly string  $processorName = 'Test',
    ) {}
}
