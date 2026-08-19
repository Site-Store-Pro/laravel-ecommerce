<?php

namespace App\Services\Payments\Processors;

use App\Services\Payments\Contracts\PaymentProcessorInterface;
use App\Services\Payments\PaymentResult;
use Illuminate\Support\Str;

/**
 * Built-in test / simulation processor (processor_id = 0).
 * Always present — no SDK or .env credentials required.
 *
 * Supports two simulation modes via $payload['simulate']:
 *   ''     → success  (default)
 *   'fail' → returns a declined payment result
 */
class TestProcessor implements PaymentProcessorInterface
{
    public function __construct(private bool $sandbox = true) {}

    public function charge(float $amount, string $currency, array $payload): PaymentResult
    {
        // Simulate Failure when the frontend sends the 'fail' token
        if (($payload['simulate'] ?? '') === 'fail') {
            return new PaymentResult(
                success:           false,
                authorizationCode: '',
                transactionId:     '',
                errorMessage:      'Simulated payment failure — card declined.',
                processorName:     'Test Gateway (Simulated)',
            );
        }

        return new PaymentResult(
            success:           true,
            authorizationCode: 'TEST-' . strtoupper(Str::random(8)),
            transactionId:     'SIM-' . strtoupper(Str::random(12)),
            processorName:     'Test Gateway (Simulated)',
        );
    }

    public function isSandbox(): bool
    {
        return true; // test processor is always sandbox
    }

    public function getName(): string
    {
        return 'Test Gateway (Simulated)';
    }

    public function refund(string $transactionId, float $amount, ?string $reason = null, string $currency = 'USD'): PaymentResult
    {
        $refundCode = 'TEST-RFND-' . strtoupper(Str::random(8));

        return new PaymentResult(
            success:           true,
            authorizationCode: $refundCode,
            transactionId:     $refundCode,
            processorName:     $this->getName(),
        );
    }
}
