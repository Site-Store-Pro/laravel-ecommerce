<?php

namespace App\Jobs;

use App\Models\Language;
use App\Services\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;
    public int $timeout = 120;

    public function __construct(
        public readonly string $modelClass,
        public readonly int    $modelId,
        public readonly int    $languageId,
    ) {}

    public function handle(TranslationService $service): void
    {
        $model = $this->modelClass::find($this->modelId);
        if (!$model) {
            Log::warning('[TranslateContentJob] Model not found: ' . $this->modelClass . '#' . $this->modelId);
            return;
        }

        $language = Language::find($this->languageId);
        if (!$language) {
            Log::warning('[TranslateContentJob] Language not found: ' . $this->languageId);
            return;
        }

        $service->translateRecord($model, $language);

        Log::info('[TranslateContentJob] Translated ' . class_basename($this->modelClass) . '#' . $this->modelId . ' → ' . $language->code);
    }

    public function tags(): array
    {
        return ['translation', 'lang:' . $this->languageId, class_basename($this->modelClass)];
    }

    public function failed(\Throwable $e): void
    {
        Log::error('[TranslateContentJob] Failed: ' . $e->getMessage(), [
            'model'    => $this->modelClass,
            'id'       => $this->modelId,
            'language' => $this->languageId,
        ]);
    }
}
