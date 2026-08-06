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
                'Preserve all HTML tags exactly as-is. ' .
                'Preserve any placeholder tokens in the format __SC_N__ exactly as-is — do not translate them. ' .
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

        foreach ($fields as $field => $context_hint) {
            $original = $record->{$field} ?? '';
            if (empty(trim(strip_tags($original)))) {
                $translationData[$field] = null;
                continue;
            }
            try {
                $translationData[$field] = $this->translateText(
                    $original,
                    $language->name,
                    $context_hint ?: $context
                );
            } catch (\Throwable $e) {
                Log::error('[TranslationService] Failed to translate field ' . $field . ': ' . $e->getMessage());
                $translationData[$field] = null;
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

    // ── Shortcode Protection ─────────────────────────────────────────────────

    /**
     * Extract all shortcodes from text, replace with __SC_N__ placeholders.
     * Returns [protected_text, placeholders_map].
     */
    private function extractShortcodes(string $text): array
    {
        $placeholders = [];
        $index = 0;

        // Match [word:anything-inside] patterns (plugin shortcodes, page links, etc.)
        $protected = preg_replace_callback(
            '/\[[a-zA-Z][a-zA-Z0-9_-]*:[^\]]*\]/',
            function (array $matches) use (&$placeholders, &$index) {
                $key = '__SC_' . $index . '__';
                $placeholders[$key] = $matches[0];
                $index++;
                return $key;
            },
            $text
        );

        return [$protected ?? $text, $placeholders];
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
            'CmsBuilderBlock'  => [
                'title'           => 'header/footer builder block title (internal label)',
                'content_desktop' => 'header or footer HTML block content for desktop — preserve all HTML and shortcodes exactly',
                'content_tablet'  => 'header or footer HTML block content for tablet — preserve all HTML and shortcodes exactly',
                'content_mobile'  => 'header or footer HTML block content for mobile — preserve all HTML and shortcodes exactly',
            ],
            'ProductInventoryAlert' => [
                'message' => 'Custom out of stock message for products — clear, concise customer notification',
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
            'CmsBuilderBlock'       => 'cms_builder_block_id',
            'ProductInventoryAlert' => 'product_inventory_alert_id',
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
        $reviewed   = $translationClass::where('language_id', $languageId)->where('translation_status', 'reviewed')->count();
        $pending    = $total - $translated;

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
            $total += count($fields);

            $existing = \App\Models\PluginSettingTranslation::where('plugin_id', $plugin->id)
                ->where('language_id', $languageId)
                ->whereIn('field_name', array_keys($fields))
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

