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

        $translationClass = 'App\\Models\\' . class_basename($record) . 'Translation';
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
        ];

        return $map[class_basename($record)] ?? [];
    }

    private function getForeignKeyFor(Model $record): string
    {
        $overrides = [
            'CmsTestimonial'  => 'testimonial_id',
            'KbArticle'       => 'kb_article_id',
            'CmsListMenuItem' => 'cms_list_menu_item_id',
            'NavItem'         => 'nav_item_id',
            'CmsPage'         => 'cms_page_id',
            'Product'         => 'product_id',
            'SiteLabel'       => 'site_label_id',
            'Category'        => 'category_id',
            'CmsPagesCategory'=> 'cms_pages_category_id',
            'CmsPagesTag'     => 'cms_pages_tag_id',
            'KbCategory'      => 'kb_category_id',
            'EmailTemplate'   => 'email_template_id',
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
        $translationClass = 'App\\Models\\' . class_basename($modelClass) . 'Translation';
        if (!class_exists($translationClass)) {
            return ['total' => 0, 'translated' => 0, 'pending' => 0, 'reviewed' => 0];
        }

        $total      = $modelClass::count();
        $translated = $translationClass::where('language_id', $languageId)->count();
        $reviewed   = $translationClass::where('language_id', $languageId)->where('translation_status', 'reviewed')->count();
        $pending    = $total - $translated;

        return compact('total', 'translated', 'pending', 'reviewed');
    }
}
