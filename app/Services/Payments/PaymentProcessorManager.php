<?php

namespace App\Services\Payments;

use App\Models\OrderCheckoutOption;
use App\Models\OrderProcessor;
use App\Services\Payments\Contracts\PaymentPluginInterface;
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

        // Ensure any drop-in processor.json plugins are loaded and synced to order_processors
        foreach (self::discoverManifests() as $procId => $manifest) {
            if (!isset($this->registry[$procId]) && !empty($manifest['class']) && class_exists($manifest['class'])) {
                $this->registry[$procId] = $manifest['class'];
            }
        }

        self::syncDiscoveredProcessors();
    }

    /**
     * Discover all stand-alone payment processor manifests in payment-processors/<slug>/processor.json.
     *
     * @return array<int, array<string, mixed>> Keyed by processor_id
     */
    public static function discoverManifests(): array
    {
        $manifests = [];
        $pattern = base_path('payment-processors/*/processor.json');

        foreach (glob($pattern) ?: [] as $manifestPath) {
            $data = json_decode((string) file_get_contents($manifestPath), true);
            if (!is_array($data) || empty($data['processor_id']) || empty($data['class'])) {
                continue;
            }

            $procId = (int) $data['processor_id'];
            if ($procId < 4) {
                continue; // Reserved for built-in processors (0=Test, 1=Stripe, 2=Paddle, 3=PayPal)
            }

            $fileName = $data['file'] ?? (basename(str_replace('\\', '/', $data['class'])) . '.php');
            $classFile = dirname($manifestPath) . '/' . $fileName;

            if (file_exists($classFile)) {
                require_once $classFile;
            }

            $data['_dir'] = dirname($manifestPath);
            $data['_class_file'] = $classFile;
            $manifests[$procId] = $data;
        }

        return $manifests;
    }

    /**
     * Ensure every auto-discovered payment processor has a corresponding row
     * in the `order_processors` table so it appears in Admin -> Checkout -> Processors.
     */
    public static function syncDiscoveredProcessors(): void
    {
        $manifests = self::discoverManifests();
        if (empty($manifests)) {
            return;
        }

        try {
            foreach ($manifests as $procId => $data) {
                OrderProcessor::firstOrCreate(
                    ['processor_id' => $procId],
                    [
                        'processor_name' => $data['name'] ?? ('Processor #' . $procId),
                        'production'     => (int) ($data['default_production'] ?? 0),
                    ]
                );
            }
        } catch (\Throwable $e) {
            // Silently ignore if the database / migrations are not yet ready
        }
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
     * appropriate JS integration. Returns 'test', 'stripe', 'paddle', 'paypal',
     * or a custom plugin's getTypeSlug() (e.g. 'square').
     */
    public function activeProcessorType(?int $processorId = null): string
    {
        $id = $processorId ?? $this->activeProcessorId();

        return match ($id) {
            1       => 'stripe',
            2       => 'paddle',
            3       => 'paypal',
            default => $this->resolveCustomProcessorType($id),
        };
    }

    /**
     * Resolve the type slug for a custom/plugin processor ID, falling back to 'test'.
     */
    private function resolveCustomProcessorType(int $processorId): string
    {
        if ($processorId <= 0 || !isset($this->registry[$processorId])) {
            return 'test';
        }

        $driver = $this->resolve($processorId);
        if ($driver instanceof PaymentPluginInterface) {
            return $driver->getTypeSlug();
        }

        return 'test';
    }

    /**
     * Return all registered custom processors (processor_id >= 4) that implement PaymentPluginInterface.
     *
     * @return array<int, class-string<PaymentPluginInterface>>
     */
    public function getPluginProcessors(): array
    {
        $plugins = [];
        foreach ($this->registry as $id => $class) {
            if ($id >= 4 && class_exists($class) && is_subclass_of($class, PaymentPluginInterface::class)) {
                $plugins[(int) $id] = $class;
            }
        }
        return $plugins;
    }

    /**
     * Resolve a PaymentPluginInterface instance by its type slug (e.g. 'square').
     */
    public function resolveByTypeSlug(string $slug): ?PaymentPluginInterface
    {
        $normalized = strtolower(trim($slug));
        foreach ($this->getPluginProcessors() as $id => $class) {
            $driver = $this->resolve($id);
            if ($driver instanceof PaymentPluginInterface && strtolower($driver->getTypeSlug()) === $normalized) {
                return $driver;
            }
        }
        return null;
    }
}
