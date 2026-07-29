<?php

namespace App\Traits;

use App\Models\Language;
use App\Services\LanguageService;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTranslations
{
    /**
     * The model's translation child-table class.
     * Override in the model if needed: protected string $translationModel = MyTranslation::class;
     */
    protected function translationModelClass(): string
    {
        // Convention: App\Models\{ModelName}Translation
        return 'App\\Models\\' . class_basename(static::class) . 'Translation';
    }

    /**
     * The foreign key name on the translation table.
     * Override in the model if needed.
     */
    protected function translationForeignKey(): string
    {
        return $this->getForeignKey(); // e.g. cms_page_id
    }

    /** All translations for this model. */
    public function translations(): HasMany
    {
        return $this->hasMany($this->translationModelClass(), $this->translationForeignKey());
    }

    /** Single translation for a given language ID (or null). */
    public function translation(?int $languageId = null): mixed
    {
        $languageId ??= app(LanguageService::class)->currentId();
        return $this->translations->firstWhere('language_id', $languageId);
    }

    /**
     * Get a translated field value, falling back to the default-language
     * translation, then to the native model attribute.
     *
     * @param string      $field      The attribute name (e.g. 'title')
     * @param int|null    $languageId Override language (uses current if null)
     */
    public function getTranslated(string $field, ?int $languageId = null): string
    {
        $langService = app(LanguageService::class);
        $languageId ??= $langService->currentId();
        $defaultId   = $langService->defaultId();

        // 1. Try the requested language
        if ($languageId !== $defaultId) {
            $translation = $this->translation($languageId);
            if ($translation && !empty($translation->{$field})) {
                return (string) $translation->{$field};
            }
        }

        // 2. Fall back to the model's own attribute (which IS the default language)
        return (string) ($this->{$field} ?? '');
    }

    /**
     * Eager-load translations for the current language + default language.
     * Call this on collections before rendering.
     */
    public function scopeWithCurrentTranslations($query)
    {
        $langService = app(LanguageService::class);
        $ids = array_unique([$langService->currentId(), $langService->defaultId()]);
        return $query->with(['translations' => fn ($q) => $q->whereIn('language_id', $ids)]);
    }

    /**
     * Automatically return translated field values when the 'translations'
     * relation is already eager-loaded and a non-default language is active.
     *
     * This means $model->title, $model->content, etc. transparently return
     * the translated version without any view changes.
     *
     * Only activates when:
     *   - The model has a $translatable array
     *   - The 'translations' relation is loaded (via withCurrentTranslations())
     *   - The current language is NOT the default
     */
    public function getAttribute($key): mixed
    {
        if (
            property_exists($this, 'translatable')
            && in_array($key, $this->translatable, true)
            && $this->relationLoaded('translations')
        ) {
            try {
                $langService = app(\App\Services\LanguageService::class);
                if (!$langService->isDefault()) {
                    $trans = $this->translations->firstWhere('language_id', $langService->currentId());
                    if ($trans && !empty($trans->{$key})) {
                        return $trans->{$key};
                    }
                }
            } catch (\Throwable) {
                // Silently fall through to default value
            }
        }

        return parent::getAttribute($key);
    }
}
