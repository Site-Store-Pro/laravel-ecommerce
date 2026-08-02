<?php

namespace App\Jobs;

use App\Models\Language;
use App\Models\ProductVariant;
use App\Models\ProductVariantTranslation;
use App\Services\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Translates a single ProductVariant's attribute labels (Color, Size, Blue, Large…)
 * and its personalization labels into a target language.
 *
 * The raw attributes JSON ({"Color":"Blue","Size":"Large"}) is treated as a
 * flat token pool — every unique key and every unique value is translated
 * individually and stored in product_variant_translations.attributes_translated.
 *
 * The personalization_label, personalization_details_label, and
 * personalization_placeholder fields are translated only when they are non-empty.
 *
 * This job intentionally DOES NOT translate the canonical attributes JSON —
 * that column is always stored in the default (English) language so that
 * selectAttribute() and getVariantColor() continue to work correctly.
 */
class TranslateVariantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;
    public int $timeout = 120;

    public function __construct(
        public readonly int $variantId,
        public readonly int $languageId,
    ) {}

    public function handle(TranslationService $service): void
    {
        $variant = ProductVariant::find($this->variantId);
        if (!$variant) {
            Log::warning('[TranslateVariantJob] Variant not found: #' . $this->variantId);
            return;
        }

        $language = Language::find($this->languageId);
        if (!$language) {
            Log::warning('[TranslateVariantJob] Language not found: #' . $this->languageId);
            return;
        }

        $translationData = [
            'product_variant_id' => $variant->id,
            'language_id'        => $language->id,
        ];

        // ── Translate attribute keys and values ────────────────────────────────
        $rawAttrs = json_decode($variant->attributes ?? '{}', true);
        if (is_array($rawAttrs) && !empty($rawAttrs)) {
            // Collect unique tokens (both keys and values) into a flat token list.
            $tokens = [];
            foreach ($rawAttrs as $key => $val) {
                $tokens[$key] = $key;   // keyed by itself so we can de-duplicate easily
                $tokens[$val] = $val;
            }

            $attrTranslated = [];
            foreach ($tokens as $raw) {
                if (empty(trim((string) $raw))) {
                    continue;
                }
                try {
                    $attrTranslated[$raw] = $service->translateText(
                        (string) $raw,
                        $language->name,
                        'product attribute label or value — keep it very short and accurate'
                    );
                } catch (\Throwable $e) {
                    Log::error('[TranslateVariantJob] Attribute token translation failed: ' . $e->getMessage(), [
                        'token'    => $raw,
                        'variant'  => $variant->id,
                        'language' => $language->id,
                    ]);
                    $attrTranslated[$raw] = ''; // leave blank — will fall back to raw in blade
                }
            }

            if (!empty($attrTranslated)) {
                $translationData['attributes_translated'] = $attrTranslated;
            }
        }

        // ── Translate personalization labels ────────────────────────────────────
        $personalizationFields = [
            'personalization_label'         => 'product personalization prompt label — short UI text',
            'personalization_details_label' => 'product personalization details label — short UI text',
            'personalization_placeholder'   => 'product personalization placeholder text — short hint for input field',
        ];

        foreach ($personalizationFields as $field => $hint) {
            $original = $variant->{$field} ?? '';
            if (empty(trim($original))) {
                continue;
            }
            try {
                $translationData[$field] = $service->translateText($original, $language->name, $hint);
            } catch (\Throwable $e) {
                Log::error('[TranslateVariantJob] Field translation failed: ' . $e->getMessage(), [
                    'field'    => $field,
                    'variant'  => $variant->id,
                    'language' => $language->id,
                ]);
            }
        }

        // Upsert the translation record — merge with any existing manual overrides.
        ProductVariantTranslation::updateOrCreate(
            [
                'product_variant_id' => $variant->id,
                'language_id'        => $language->id,
            ],
            $translationData
        );

        Log::info('[TranslateVariantJob] Translated variant #' . $variant->id . ' → ' . $language->code);
    }

    public function tags(): array
    {
        return ['translation', 'lang:' . $this->languageId, 'ProductVariant'];
    }

    public function failed(\Throwable $e): void
    {
        Log::error('[TranslateVariantJob] Failed: ' . $e->getMessage(), [
            'variant'  => $this->variantId,
            'language' => $this->languageId,
        ]);
    }
}
