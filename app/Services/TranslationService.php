<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    /**
     * Translate a text string to the target language.
     * Shortcodes like [plugin:...], [page:...] etc. are extracted before
     * sending to OpenAI and reinserted into the translated result.
     */
    public function translateText(string $text, string $targetLanguageName, string $context = ''): string
    {
        if (empty(trim($text))) {
            return $text;
        }

        $apiKey = config('ai.openai_api_key');
        if (empty($apiKey)) {
            throw new \RuntimeException('OpenAI API key is not configured.');
        }

        // ── Protect shortcodes ────────────────────────────────────────────────
        [$protected, $placeholders] = $this->extractShortcodes($text);

        // ── Call OpenAI ──────────────────────────────────────────────────────
        try {
            $client = \OpenAI::client($apiKey);

            $systemPrompt = 'You are a professional translator. ' .
                'Translate the following content into ' . $targetLanguageName . '. ' .
                'Preserve all HTML tags and attributes exactly as-is. ' .
                'Preserve any placeholder tokens in the format __SSP_*__ exactly as-is — do not translate or alter them. ' .
                'Return ONLY the translated text, no explanation.' .
                ($context ? ' Context: ' . $context : '');

            $response = $client->chat()->create([
                'model'    => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $protected],
                ],
                'temperature' => 0.3,
            ]);

            $translated = $response->choices[0]->message->content ?? $text;
        } catch (\Throwable $e) {
            Log::error('[TranslationService] OpenAI error: ' . $e->getMessage());
            throw $e;
        }

        // ── Reinsert shortcodes ───────────────────────────────────────────────
        return $this->reinsertShortcodes($translated, $placeholders);
    }

    /**
     * Translate multiple fields in a single OpenAI request using JSON mode.
     * Prevents multiple sequential HTTP roundtrips that cause Cloudflare 100s timeouts.
     *
     * @param array<string, array{text: string, hint?: string}> $fieldsMap
     * @return array<string, string>
     */
    public function translateBatch(array $fieldsMap, string $targetLanguageName, string $generalContext = ''): array
    {
        if (empty($fieldsMap)) {
            return [];
        }

        $apiKey = config('ai.openai_api_key');
        if (empty($apiKey)) {
            throw new \RuntimeException('OpenAI API key is not configured.');
        }

        $payload = [];
        $placeholdersMap = [];
        $hints = [];

        foreach ($fieldsMap as $key => $item) {
            $rawText = is_array($item) ? ($item['text'] ?? '') : (string) $item;
            $hint = is_array($item) ? ($item['hint'] ?? '') : '';

            if (empty(trim($rawText))) {
                continue;
            }

            [$protected, $placeholders] = $this->extractShortcodes($rawText);
            $payload[$key] = $protected;
            $placeholdersMap[$key] = $placeholders;
            if (!empty($hint)) {
                $hints[] = "{$key}: {$hint}";
            }
        }

        if (empty($payload)) {
            return [];
        }

        try {
            $client = \OpenAI::client($apiKey);

            $systemPrompt = "You are a professional website translator.\n" .
                "Translate the values in the provided JSON object into {$targetLanguageName}.\n" .
                "RULES:\n" .
                "1. Return ONLY a valid JSON object matching the exact input keys.\n" .
                "2. Preserve all HTML structure, markup tags, classes, and attributes exactly as-is.\n" .
                "3. Preserve all placeholder tokens in the format __SSP_*__ exactly as-is without modification.\n" .
                "4. Maintain accurate contextual tone.\n" .
                (!empty($hints) ? "Field Contexts:\n" . implode("\n", $hints) . "\n" : "") .
                ($generalContext ? "General Context: {$generalContext}\n" : "");

            $response = $client->chat()->create([
                'model'           => 'gpt-4o-mini',
                'response_format' => ['type' => 'json_object'],
                'messages'        => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)],
                ],
                'temperature'     => 0.3,
            ]);

            $content = $response->choices[0]->message->content ?? '';
            $decoded = json_decode($content, true);

            if (!is_array($decoded)) {
                throw new \RuntimeException("Invalid JSON translation response from OpenAI.");
            }

            $results = [];
            foreach ($payload as $key => $origProtected) {
                $transText = $decoded[$key] ?? $origProtected;
                $results[$key] = $this->reinsertShortcodes($transText, $placeholdersMap[$key] ?? []);
            }

            return $results;
        } catch (\Throwable $e) {
            Log::warning("[TranslationService] translateBatch failed, falling back to sequential: " . $e->getMessage());

            // Fallback to sequential translateText if batch fails
            $results = [];
            foreach ($fieldsMap as $key => $item) {
                $rawText = is_array($item) ? ($item['text'] ?? '') : (string) $item;
                $hint = is_array($item) ? ($item['hint'] ?? '') : '';
                if (empty(trim($rawText))) {
                    $results[$key] = $rawText;
                    continue;
                }
                try {
                    $results[$key] = $this->translateText($rawText, $targetLanguageName, $hint ?: $generalContext);
                } catch (\Throwable $err) {
                    $results[$key] = $rawText;
                }
            }
            return $results;
        }
    }

    /**
     * Translate all translatable fields on a model instance for a given language.
     * Creates or updates the corresponding _translations child record.
     */
    public function translateRecord(Model $record, Language $language): bool
    {
        $fields = $this->getTranslatableFields($record);
        if (empty($fields)) {
            return false;
        }

        $context = $this->getContextForModel($record);
        $translationData = [
            'language_id'        => $language->id,
            'translation_status' => 'ai_translated',
            'translated_at'      => now(),
        ];

        $batchInput = [];
        foreach ($fields as $field => $context_hint) {
            $original = $record->{$field} ?? '';
            if (empty(trim(strip_tags($original)))) {
                $translationData[$field] = null;
                continue;
            }
            $batchInput[$field] = [
                'text' => $original,
                'hint' => $context_hint ?: $context,
            ];
        }

        if (!empty($batchInput)) {
            try {
                $translatedBatch = $this->translateBatch($batchInput, $language->name, $context);
                foreach ($translatedBatch as $field => $val) {
                    $translationData[$field] = $val;
                }
            } catch (\Throwable $e) {
                Log::error('[TranslationService] Failed to translate record in batch: ' . $e->getMessage());
            }
        }

        // Upsert the translation record
        $fkField = $this->getForeignKeyFor($record);
        $translationData[$fkField] = $record->id;

        $translationClass = $this->getTranslationClass($record);
        if (!class_exists($translationClass)) {
            return false;
        }

        $translationClass::updateOrCreate(
            [$fkField => $record->id, 'language_id' => $language->id],
            $translationData
        );

        // Flush SiteLabelService cache so translated labels appear immediately
        if (class_basename($record) === 'SiteLabel') {
            app(\App\Services\SiteLabelService::class)->clearCache($language->id);
        }

        return true;
    }

    // ── Non-Translatable Asset & Shortcode Protection ────────────────────────

    /**
     * Extract non-translatable assets (style tags, script tags, SVG icons, shortcodes)
     * and replace them with placeholder tokens to drastically reduce token count,
     * protect code integrity, and prevent timeouts.
     * Returns [protected_text, placeholders_map].
     */
    private function extractShortcodes(string $text): array
    {
        $placeholders = [];
        $index = 0;

        // 1. Protect <style> blocks
        $text = preg_replace_callback('/<style\b[^>]*>(.*?)<\/style>/is', function ($matches) use (&$placeholders, &$index) {
            $key = '__SSP_STYLE_' . $index++ . '__';
            $placeholders[$key] = $matches[0];
            return $key;
        }, $text);

        // 2. Protect <script> blocks
        $text = preg_replace_callback('/<script\b[^>]*>(.*?)<\/script>/is', function ($matches) use (&$placeholders, &$index) {
            $key = '__SSP_SCRIPT_' . $index++ . '__';
            $placeholders[$key] = $matches[0];
            return $key;
        }, $text);

        // 3. Protect <svg> elements
        $text = preg_replace_callback('/<svg\b[^>]*>(.*?)<\/svg>/is', function ($matches) use (&$placeholders, &$index) {
            $key = '__SSP_SVG_' . $index++ . '__';
            $placeholders[$key] = $matches[0];
            return $key;
        }, $text);

        // 4. Protect [word:anything-inside] patterns (plugin shortcodes, page links, etc.)
        $text = preg_replace_callback(
            '/\[[a-zA-Z][a-zA-Z0-9_-]*:[^\]]*\]/',
            function (array $matches) use (&$placeholders, &$index) {
                $key = '__SSP_SC_' . $index++ . '__';
                $placeholders[$key] = $matches[0];
                return $key;
            },
            $text
        );

        return [$text, $placeholders];
    }

    private function reinsertShortcodes(string $text, array $placeholders): string
    {
        if (empty($placeholders)) {
            return $text;
        }
        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    // ── Model Field Mapping ──────────────────────────────────────────────────

    private function getTranslatableFields(Model $record): array
    {
        $map = [
            'CmsPage'          => ['title' => 'page title', 'content' => 'page body content (HTML)', 'meta_title' => 'SEO title', 'meta_description' => 'SEO description', 'alternate_page_title' => 'alternate heading'],
            'Product'          => ['title' => 'product name', 'short_description' => 'short product description', 'long_description' => 'full product description (HTML)', 'meta_title' => 'SEO title', 'meta_description' => 'SEO description'],
            'KbArticle'        => ['title' => 'knowledge base article title', 'article_content' => 'article body (HTML)', 'meta_description' => 'SEO description'],
            'CmsTestimonial'   => ['content' => 'customer review/testimonial', 'author_title' => 'job title'],
            'NavItem'          => ['label' => 'navigation menu label', 'html_content' => 'custom HTML nav content'],
            'CmsListMenuItem'  => ['list_item' => 'list menu item text'],
            'SiteLabel'        => ['label_value' => 'short UI label, button text, or form field label — keep it concise'],
            'Category'         => ['name' => 'product category name', 'description' => 'product category description (brief)'],
            'CmsPagesCategory' => ['name' => 'CMS page category name'],
            'CmsPagesTag'      => ['name' => 'CMS page tag name'],
            'KbCategory'       => ['name' => 'knowledge base category name', 'description' => 'KB category description'],
            'EmailTemplate'    => [
                'subject'      => 'email subject line',
                'header_html'  => 'email header HTML content',
                'salutation'   => 'email salutation greeting',
                'greeting'     => 'email greeting text',
                'body'         => 'email body content (HTML)',
                'sign_off'     => 'email sign-off text',
                'signature'    => 'email signature',
                'disclaimer'   => 'email legal disclaimer',
                'copyright'    => 'email copyright line',
                'footer_html'  => 'email footer HTML content',
            ],
            'CmsModal'         => [
                'title' => 'modal popup window title',
                'body'  => 'modal popup window body content (HTML)',
            ],
            'CmsFaq'           => [
                'question' => 'frequently asked question title/prompt',
                'answer'   => 'frequently asked question answer (HTML)',
            ],
            'CmsSlide'         => [
                'slide_heading'              => 'slideshow slide heading / title text displayed over the slide image',
                'slide_sub_heading'          => 'slideshow slide sub-heading / description text displayed under the heading',
                'slide_callout_button_label' => 'slideshow slide call-to-action button label — keep it short and action-oriented',
            ],
            'CmsBuilderBlock'  => [
                'title'           => 'header/footer builder block title (internal label)',
                'content_desktop' => 'header or footer HTML block content for desktop — preserve all HTML and shortcodes exactly',
                'content_tablet'  => 'header or footer HTML block content for tablet — preserve all HTML and shortcodes exactly',
                'content_mobile'  => 'header or footer HTML block content for mobile — preserve all HTML and shortcodes exactly',
            ],
            'ProductInventoryAlert' => [
                'message' => 'Custom out of stock message for products — clear, concise customer notification',
            ],
            'ProductField' => [
                'label' => 'product customization field label shown to the customer at checkout (e.g. "Personalization Text", "Choose Colour")',
            ],
            'ProductFieldOption' => [
                'option_value' => 'product customization field choice option shown to the customer (e.g. "Red", "Small", "Gift wrap")',
            ],
            'ProductReview' => [
                'comments' => 'customer review comments and feedback for a product — preserve the reviewer tone and sentiment',
            ],
        ];

        return $map[class_basename($record)] ?? [];
    }

    private function getForeignKeyFor(Model $record): string
    {
        $overrides = [
            'CmsTestimonial'        => 'testimonial_id',
            'KbArticle'             => 'kb_article_id',
            'CmsListMenuItem'       => 'cms_list_menu_item_id',
            'NavItem'               => 'nav_item_id',
            'CmsPage'               => 'cms_page_id',
            'Product'               => 'product_id',
            'SiteLabel'             => 'site_label_id',
            'Category'              => 'category_id',
            'CmsPagesCategory'      => 'cms_pages_category_id',
            'CmsPagesTag'           => 'cms_pages_tag_id',
            'KbCategory'            => 'kb_category_id',
            'EmailTemplate'         => 'email_template_id',
            'CmsModal'              => 'cms_modal_id',
            'CmsFaq'                => 'cms_faq_id',
            'CmsSlide'              => 'cms_slide_id',
            'CmsBuilderBlock'       => 'cms_builder_block_id',
            'ProductInventoryAlert' => 'product_inventory_alert_id',
            'ProductField'          => 'product_field_id',
            'ProductFieldOption'    => 'product_field_option_id',
            'ProductReview'         => 'product_review_id',
        ];
        return $overrides[class_basename($record)] ?? $record->getForeignKey();
    }

    private function getContextForModel(Model $record): string
    {
        return 'e-commerce website';
    }

    /**
     * Count how many records of a model class have been translated for a language.
     */
    public function translationStats(string $modelClass, int $languageId): array
    {
        $translationClass = $this->getTranslationClassByName(class_basename($modelClass));
        if (!class_exists($translationClass)) {
            return ['total' => 0, 'translated' => 0, 'pending' => 0, 'reviewed' => 0];
        }

        $total      = $modelClass::count();
        $translated = $translationClass::where('language_id', $languageId)->count();

        $table    = (new $translationClass())->getTable();
        $reviewed = \Illuminate\Support\Facades\Schema::hasColumn($table, 'translation_status')
            ? $translationClass::where('language_id', $languageId)->where('translation_status', 'reviewed')->count()
            : 0;

        $pending = $total - $translated;

        return compact('total', 'translated', 'pending', 'reviewed');
    }

    /**
     * Translate all translatable settings/fields for a plugin into a target language via OpenAI.
     */
    public function translatePlugin(\App\Models\Plugin $plugin, Language $language): bool
    {
        $fields = $plugin->getTranslatableFields();
        if (empty($fields)) {
            return false;
        }

        $baseSettings = $plugin->getSettings();
        $translations = [];

        foreach ($fields as $fieldName => $label) {
            $original = $baseSettings[$fieldName] ?? $plugin->getSetting($fieldName, '');
            if (empty(trim(strip_tags((string)$original)))) {
                continue;
            }
            try {
                $translations[$fieldName] = $this->translateText(
                    (string)$original,
                    $language->name,
                    'plugin UI label or error message for ' . ($plugin->name ?? $plugin->shortcode) . ' (' . $label . ')'
                );
            } catch (\Throwable $e) {
                Log::error('[TranslationService] Failed to translate plugin field ' . $fieldName . ': ' . $e->getMessage());
            }
        }

        if (!empty($translations)) {
            $plugin->saveSettingsForLanguage($language->id, $translations);
            return true;
        }

        return false;
    }

    /**
     * Count translation statistics for active plugins that have translatable fields.
     */
    public function pluginTranslationStats(int $languageId): array
    {
        $plugins = \App\Models\Plugin::all();
        $total = 0;
        $translated = 0;

        foreach ($plugins as $plugin) {
            $fields = $plugin->getTranslatableFields();
            if (empty($fields)) {
                continue;
            }

            // Mirror translatePlugin(): only count fields that have non-empty source content.
            // Fields with an empty default are skipped by the job, so they must not
            // appear in the total — otherwise they permanently inflate the "pending" count.
            $baseSettings = $plugin->getSettings();
            $translatableFieldNames = [];
            foreach ($fields as $fieldName => $label) {
                $original = $baseSettings[$fieldName] ?? $plugin->getSetting($fieldName, '');
                if (!empty(trim(strip_tags((string)$original)))) {
                    $translatableFieldNames[] = $fieldName;
                }
            }

            if (empty($translatableFieldNames)) {
                // All fields are empty at source — nothing to translate for this plugin.
                continue;
            }

            $total += count($translatableFieldNames);

            $existing = \App\Models\PluginSettingTranslation::where('plugin_id', $plugin->id)
                ->where('language_id', $languageId)
                ->whereIn('field_name', $translatableFieldNames)
                ->whereNotNull('field_value')
                ->where('field_value', '!=', '')
                ->count();

            $translated += $existing;
        }

        $pending = max(0, $total - $translated);

        return ['total' => $total, 'translated' => $translated, 'pending' => $pending, 'reviewed' => 0];
    }


    // ── Translation class resolution ─────────────────────────────────────────

    /**
     * Some translation models don't follow the {ModelName}Translation convention.
     * This override map corrects the lookup for those cases.
     */
    private function getTranslationClass(Model $record): string
    {
        return $this->getTranslationClassByName(class_basename($record));
    }

    private function getTranslationClassByName(string $baseName): string
    {
        $overrides = [
            'CmsTestimonial' => 'App\\Models\\TestimonialTranslation',
        ];
        return $overrides[$baseName] ?? ('App\\Models\\' . $baseName . 'Translation');
    }

    /**
     * Count ProductVariant translation coverage for a given language.
     *
     * A variant is considered "translated" if it has an attributes_translated
     * entry (the attribute token pool has been translated). Variants with no
     * attributes JSON are skipped from the "needs translation" count because
     * there is nothing to translate.
     *
     * Personalization coverage is tracked separately.
     */
    public function variantTranslationStats(int $languageId): array
    {
        // Variants that have at least one non-empty attribute token to translate.
        // Must match the exact condition used in TranslateVariantJob::handle():
        // json_decode returns a non-empty array AND at least one key or value is a non-empty string.
        // We filter in PHP via a collection to avoid DB-specific JSON functions.
        $totalWithAttrs = \App\Models\ProductVariant::whereNotNull('attributes')
            ->where('attributes', '!=', '{}')
            ->where('attributes', '!=', '')
            ->get(['id', 'attributes'])
            ->filter(function ($variant) {
                $decoded = json_decode($variant->attributes ?? '{}', true);
                if (!is_array($decoded) || empty($decoded)) {
                    return false;
                }
                // At least one key or value must be a non-empty string (same as job's loop)
                foreach ($decoded as $key => $val) {
                    if (trim((string) $key) !== '' || trim((string) $val) !== '') {
                        return true;
                    }
                }
                return false;
            })
            ->count();

        // Only count translation rows whose parent variant actually has translatable attributes.
        $translatedAttrs = \App\Models\ProductVariantTranslation::where('language_id', $languageId)
            ->whereNotNull('attributes_translated')
            ->whereHas('variant', fn ($q) => $q
                ->whereNotNull('attributes')
                ->where('attributes', '!=', '{}')
                ->where('attributes', '!=', ''))
            ->count();

        // Variants with personalization enabled.
        $totalPersonalization = \App\Models\ProductVariant::where('personalization_active', 1)
            ->whereNotNull('personalization_label')
            ->where('personalization_label', '!=', '')
            ->count();

        $translatedPersonalization = \App\Models\ProductVariantTranslation::where('language_id', $languageId)
            ->whereNotNull('personalization_label')
            ->where('personalization_label', '!=', '')
            ->whereHas('variant', fn ($q) => $q->where('personalization_active', 1)
                ->whereNotNull('personalization_label')
                ->where('personalization_label', '!=', ''))
            ->count();

        return [
            'total'                      => \App\Models\ProductVariant::count(),
            'total_with_attrs'           => $totalWithAttrs,
            'translated_attrs'           => $translatedAttrs,
            'pending_attrs'              => max(0, $totalWithAttrs - $translatedAttrs),
            'total_personalization'      => $totalPersonalization,
            'translated_personalization' => $translatedPersonalization,
            'pending_personalization'    => max(0, $totalPersonalization - $translatedPersonalization),
        ];
    }
}

