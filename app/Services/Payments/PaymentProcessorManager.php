<?php

namespace App\Services\Payments;

use App\Models\OrderCheckoutOption;
use App\Models\OrderProcessor;
use App\Services\Payments\Contracts\PaymentProcessorInterface;
use App\Services\Payments\Processors\TestProcessor;
use InvalidArgumentException;

/**
 * Resolves the active payment processor driver from the registry
 * (config/payment_processors.php) and the admin-selected primary processor.
 */
class PaymentProcessorManager
{
    /** @var array<int, class-string<PaymentProcessorInterface>> */
    private array $registry;

    private ?int $resolvedProcessorId = null;

    public function __construct()
    {
        $this->registry = config('payment_processors', [0 => TestProcessor::class]);
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Resolve the driver for the active processor.
     * Falls back to the TestProcessor when:
     *   - no primary/active processor is set (0)
     *   - the processor_id is not in the registry
     *   - the driver class does not exist
     */
    public function resolveActive(?int $processorId = null): PaymentProcessorInterface
    {
        $id = $processorId ?? $this->activeProcessorId();

        return $this->resolve($id);
    }

    /**
     * Resolve a driver by its processor_id.
     */
    public function resolve(int $processorId): PaymentProcessorInterface
    {
        $driverClass = $this->registry[$processorId] ?? null;

        if ($driverClass === null || ! class_exists($driverClass)) {
            // Unknown / unregistered processor → fall back to Test
            return new TestProcessor();
        }

        // Look up whether sandbox is enabled for this processor
        $record = OrderProcessor::where('processor_id', $processorId)->first();
        $isSandbox = $record ? (bool) ! $record->production : true;

        return new $driverClass($isSandbox);
    }

    /**
     * Return the active processor_id, honouring the randomize_processor flag.
     *
     * Resolution order:
     *  1. If randomize_processor = 1 → pick randomly from all non-zero
     *     (non-test) configured slots that are registered in the registry.
     *  2. Otherwise → use primary_processor.
     *  3. Fallback → 0 (Test).
     */
    public function activeProcessorId(): int
    {
        if ($this->resolvedProcessorId !== null) {
            return $this->resolvedProcessorId;
        }

        $opts = OrderCheckoutOption::first();
        if (!$opts) {
            return $this->resolvedProcessorId = 0;
        }

        $randomize = (bool) ($opts->randomize_processor ?? false);

        if ($randomize) {
            // Gather all non-zero processor slots that are registered
            $candidates = array_values(array_unique(array_filter([
                (int) ($opts->primary_processor   ?? 0),
                (int) ($opts->secondary_processor ?? 0),
                (int) ($opts->tertiary_processor  ?? 0),
            ], fn(int $id) => $id > 0 && isset($this->registry[$id]))));

            if (!empty($candidates)) {
                return $this->resolvedProcessorId = $candidates[array_rand($candidates)];
            }
            // No real processors registered → fall through to primary
        }

        $primary = (int) ($opts->primary_processor ?? 0);
        return $this->resolvedProcessorId = isset($this->registry[$primary]) ? $primary : 0;
    }

    /**
     * True when the specified or resolved active processor is running in sandbox/test mode.
     * Reads the `production` column from order_processors.
     */
    public function activeProcessorIsSandbox(?int $processorId = null): bool
    {
        $id = $processorId ?? $this->activeProcessorId();
        if ($id === 0) {
            return true; // Test processor is always sandbox
        }
        $record = OrderProcessor::where('processor_id', $id)->first();
        return $record ? !(bool) $record->production : true;
    }

    /**
     * Return the processor type string used by the frontend to select the
     * appropriate JS integration. Returns 'test', 'stripe', 'paddle', or 'paypal'.
     */
    public function activeProcessorType(?int $processorId = null): string
    {
        $id = $processorId ?? $this->activeProcessorId();

        return match ($id) {
            1       => 'stripe',
            2       => 'paddle',
            3       => 'paypal',
            default => 'test',
        };
    }
}
