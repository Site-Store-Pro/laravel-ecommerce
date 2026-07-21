<?php
namespace App\Plugins\Support;

use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use App\Plugins\Contracts\ShippingPlugin;
use App\Plugins\Contracts\TopNavigationPlugin;
use Illuminate\Support\Collection;

class PluginManager
{
    protected array $displayPlugins = [];   // slug => DisplayPlugin instance
    protected array $shippingPlugins = [];  // slug => ShippingPlugin instance
    protected array $topNavPlugins = [];    // slug => TopNavigationPlugin instance
    protected bool $booted = false;

    /**
     * Register a plugin class (must implement DisplayPlugin, ShippingPlugin, or TopNavigationPlugin).
     */
    public function register(string $class): void
    {
        $instance = app($class);
        if ($instance instanceof DisplayPlugin) {
            $this->displayPlugins[$instance->slug()] = $instance;
        } elseif ($instance instanceof ShippingPlugin) {
            $this->shippingPlugins[$instance->slug()] = $instance;
        } elseif ($instance instanceof TopNavigationPlugin) {
            $this->topNavPlugins[$instance->slug()] = $instance;
        }
    }

    /**
     * Discover and register plugins from the external plugins/ folder.
     * Each plugin folder must contain plugin.json and a PHP class file.
     */
    public function discoverExternalPlugins(string $pluginsPath): void
    {
        if (!is_dir($pluginsPath)) return;

        foreach (glob($pluginsPath . '/*', GLOB_ONLYDIR) as $dir) {
            $manifest = $dir . '/plugin.json';
            if (!file_exists($manifest)) continue;

            $data = json_decode(file_get_contents($manifest), true);
            if (empty($data['class'])) continue;

            $classFile = $dir . '/' . $data['class'] . '.php';
            if (!file_exists($classFile)) continue;

            require_once $classFile;

            $fqcn = $data['class'];
            if (!class_exists($fqcn)) continue;

            // Sync plugin record to DB
            $this->syncExternalPlugin($data, basename($dir));

            // Register it
            $this->register($fqcn);
        }
    }

    /**
     * Sync a plugin.json manifest into the plugins + plugin_options tables.
     */
    protected function syncExternalPlugin(array $data, string $folderName): void
    {
        try {
            $plugin = Plugin::updateOrCreate(
                ['filename' => $data['filename'] ?? $folderName],
                [
                    'api_id'                 => $data['api_id'] ?? null,
                    'name'                   => $data['name'] ?? $folderName,
                    'version'                => $data['version'] ?? '1.0',
                    'type'                   => $data['type'] ?? 'display',
                    'author'                 => $data['author'] ?? null,
                    'install_type'           => 0, // drop-in
                    'description'            => $data['description'] ?? null,
                    'shortcode'              => $data['shortcode'] ?? $folderName,
                    'activation_required'    => $data['activation_required'] ?? 'no',
                    'activation_instructions'=> $data['activation_instructions'] ?? null,
                    'activation_success_msg' => $data['activation_success_msg'] ?? null,
                    'usage_instructions'     => $data['usage_instructions'] ?? null,
                    'help_url'               => $data['help_url'] ?? null,
                ]
            );

            // Sync options (field schema)
            foreach ($data['options'] ?? [] as $opt) {
                \App\Models\PluginOption::updateOrCreate(
                    ['plugin_id' => $plugin->id, 'field_name' => $opt['field_name']],
                    [
                        'field_label'         => $opt['field_label'] ?? $opt['field_name'],
                        'field_type'          => $opt['field_type'] ?? 'input',
                        'field_data_format'   => $opt['field_data_format'] ?? 'string',
                        'field_default_value' => $opt['field_default_value'] ?? null,
                        'field_selections'    => $opt['field_selections'] ?? null,
                        'field_min_value'     => $opt['field_min_value'] ?? null,
                        'field_max_value'     => $opt['field_max_value'] ?? null,
                        'field_editor'        => $opt['field_editor'] ?? null,
                        'field_help'          => $opt['field_help'] ?? null,
                        'field_required'      => $opt['field_required'] ?? 'no',
                        'field_error_msg'     => $opt['field_error_msg'] ?? null,
                        'sort_order'          => $opt['sort_order'] ?? 0,
                    ]
                );
            }
        } catch (\Exception $e) {
            // Silently skip if DB is not yet available (during migrate)
        }
    }

    /**
     * Get a display plugin by shortcode slug.
     * Returns null if not found or if plugin is inactive in DB.
     */
    public function getDisplay(string $slug): ?DisplayPlugin
    {
        return $this->displayPlugins[$slug] ?? null;
    }

    /**
     * Get all active shipping plugin instances.
     */
    public function getShippingPlugins(): Collection
    {
        return collect($this->shippingPlugins);
    }

    /**
     * Get all registered display plugins as slug => instance map.
     */
    public function allDisplayPlugins(): array
    {
        return $this->displayPlugins;
    }

    /**
     * Get all registered plugins (display + shipping + top-nav) as a collection.
     */
    public function all(): Collection
    {
        return collect(array_merge($this->displayPlugins, $this->shippingPlugins, $this->topNavPlugins));
    }

    /**
     * Get a top-navigation plugin by its item_type slug.
     */
    public function getTopNavPlugin(string $slug): ?TopNavigationPlugin
    {
        return $this->topNavPlugins[$slug] ?? null;
    }

    /**
     * Get all registered top-navigation plugins as slug => instance map.
     */
    public function allTopNavPlugins(): array
    {
        return $this->topNavPlugins;
    }

    /**
     * Fetch realtime shipping rates from ALL active shipping plugins.
     * Returns a flat array of rate options sorted low-to-high by rate amount.
     *
     * Each item: ['code' => string, 'label' => string, 'rate' => float, 'days' => int|null, 'plugin' => string]
     */
    public function getShippingRates(ShippingContext $context): array
    {
        $allRates = [];

        foreach ($this->shippingPlugins as $slug => $instance) {
            // Load the DB record — skip if plugin is inactive
            $pluginModel = Plugin::where('shortcode', $slug)
                ->where('activation_status', 1)
                ->first();

            if (!$pluginModel) {
                continue;
            }

            try {
                $rates = $instance->getRates($context, $pluginModel);
                foreach ($rates as $rate) {
                    $rate['plugin'] = $instance->name();
                    $allRates[] = $rate;
                }
            } catch (\Throwable $e) {
                \Log::error("[PluginManager] Shipping plugin '{$slug}' error: " . $e->getMessage());
            }
        }

        // Sort all rates low to high
        usort($allRates, fn($a, $b) => $a['rate'] <=> $b['rate']);

        return $allRates;
    }
}
