<?php

namespace App\Jobs;

use App\Models\Language;
use App\Models\Plugin;
use App\Services\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslatePluginJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;
    public int $timeout = 120;

    public function __construct(
        public readonly int $pluginId,
        public readonly int $languageId,
    ) {}

    public function handle(TranslationService $service): void
    {
        $plugin = Plugin::find($this->pluginId);
        if (!$plugin) {
            Log::warning('[TranslatePluginJob] Plugin not found: #' . $this->pluginId);
            return;
        }

        $language = Language::find($this->languageId);
        if (!$language) {
            Log::warning('[TranslatePluginJob] Language not found: #' . $this->languageId);
            return;
        }

        $service->translatePlugin($plugin, $language);

        Log::info('[TranslatePluginJob] Translated Plugin #' . $plugin->id . ' (' . $plugin->shortcode . ') → ' . $language->code);
    }

    public function tags(): array
    {
        return ['translation', 'plugin:' . $this->pluginId, 'lang:' . $this->languageId];
    }

    public function failed(\Throwable $e): void
    {
        Log::error('[TranslatePluginJob] Failed: ' . $e->getMessage(), [
            'plugin_id' => $this->pluginId,
            'language'  => $this->languageId,
        ]);
    }
}
