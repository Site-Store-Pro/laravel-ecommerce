<?php

namespace App\Services;

use App\Models\SiteLabel;
use App\Models\SiteLabelTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SiteLabelService
{
    /**
     * In-process memory cache so multiple @label calls in one request
     * never hit the cache store more than once per language.
     *
     * Keyed by cache-store key string (e.g. 'site_labels_default', 'site_labels_3').
     *
     * @var array<string, array<string, array{default: string, custom: string|null}>>
     */
    private array $runtime = [];

    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * Return the effective label text for the given key.
     *
     * Priority for non-default language:
     *   1. Translated label_value from site_label_translations
     *   2. Default-language label_custom (admin override)
     *   3. Default-language label_default (seeded value)
     *   4. $fallback
     */
    public function get(string $key, string $fallback = '', ?int $languageId = null): string
    {
        $langService  = app(LanguageService::class);
        $currentId    = $languageId ?? $langService->currentId();
        $defaultId    = $langService->defaultId();

        // Try current language map (includes translated values if non-default)
        $map = $this->loadAll($currentId, $defaultId);
        if (isset($map[$key])) {
            $row = $map[$key];
            if (!empty($row['custom']))   return $row['custom'];
            if (!empty($row['default'])) return $row['default'];
        }

        // Fall back to default language map
        if ($currentId !== $defaultId) {
            $defaultMap = $this->loadAll($defaultId, $defaultId);
            if (isset($defaultMap[$key])) {
                $row = $defaultMap[$key];
                if (!empty($row['custom']))   return $row['custom'];
                if (!empty($row['default'])) return $row['default'];
            }
        }

        return $fallback;
    }

    /**
     * Persist a custom override for the DEFAULT language and flush its cache.
     */
    public function save(string $key, string $customValue, int $languageId = 0): bool
    {
        $label = SiteLabel::where('label_key', $key)->first();
        if (!$label) {
            return false;
        }

        $label->update([
            'label_custom' => $customValue !== '' ? $customValue : null,
            'last_updated' => now(),
        ]);

        $this->clearDefaultCache();
        return true;
    }

    /**
     * Save a translation override for a NON-default language.
     */
    public function saveTranslation(string $key, string $value, int $languageId): bool
    {
        $label = SiteLabel::where('label_key', $key)->first();
        if (!$label) {
            return false;
        }

        SiteLabelTranslation::updateOrCreate(
            ['site_label_id' => $label->id, 'language_id' => $languageId],
            [
                'label_value'         => $value !== '' ? $value : null,
                'translation_status'  => 'reviewed',
                'translated_at'       => now(),
            ]
        );

        $this->clearCache($languageId);
        return true;
    }

    /**
     * Clear the custom override for a label (default language).
     */
    public function clear(string $key, int $languageId = 0): bool
    {
        return $this->save($key, '');
    }

    /**
     * Flush the cached label map for a specific language.
     */
    public function clearCache(int $languageId = 0): void
    {
        $cacheKey = $this->cacheKey($languageId, app(LanguageService::class)->defaultId());
        unset($this->runtime[$cacheKey]);
        Cache::forget($cacheKey);

        // Also clear the default cache since it's the fallback for all languages
        $this->clearDefaultCache();
    }

    /**
     * Flush the default-language cache (used after saving label_custom overrides).
     */
    public function clearDefaultCache(): void
    {
        unset($this->runtime['site_labels_default']);
        Cache::forget('site_labels_default');
    }

    /**
     * Flush all language caches (used after bulk reset).
     */
    public function clearAllCache(): void
    {
        $this->runtime = [];
        Cache::forget('site_labels_default');
        // Clear per-language keys 1–200
        for ($i = 1; $i <= 200; $i++) {
            Cache::forget("site_labels_{$i}");
        }
    }

    // ── Internal ──────────────────────────────────────────────────────────────

    /**
     * Determine the cache store key for a given language ID.
     */
    private function cacheKey(int $languageId, int $defaultId): string
    {
        return ($languageId === 0 || $languageId === $defaultId)
            ? 'site_labels_default'
            : "site_labels_{$languageId}";
    }

    /**
     * Load the full label map for a language, using in-process → cache → DB.
     *
     * For the DEFAULT language: returns label_custom ?? label_default from site_labels.
     * For NON-DEFAULT languages: LEFT JOINs site_label_translations so label_value
     *   (the AI-translated or admin-reviewed text) is returned as 'custom', with
     *   the source-language label_default as 'default' fallback.
     *
     * @return array<string, array{default: string, custom: string|null}>
     */
    private function loadAll(int $languageId, int $defaultId): array
    {
        $cacheKey = $this->cacheKey($languageId, $defaultId);

        if (isset($this->runtime[$cacheKey])) {
            return $this->runtime[$cacheKey];
        }

        $isDefault = ($languageId === 0 || $languageId === $defaultId);

        $this->runtime[$cacheKey] = Cache::remember(
            $cacheKey,
            now()->addHours(24),
            function () use ($languageId, $isDefault) {
                try {
                    if ($isDefault) {
                        // Default language — read directly from site_labels
                        return SiteLabel::get(['label_key', 'label_default', 'label_custom'])
                            ->mapWithKeys(fn ($row) => [
                                $row->label_key => [
                                    'default' => (string) $row->label_default,
                                    'custom'  => $row->label_custom,
                                ],
                            ])
                            ->toArray();
                    }

                    // Non-default language — load labels + translations separately, merge in PHP.
                    // Using two simple queries avoids LEFT JOIN ambiguity across Eloquent scopes.
                    $labels = SiteLabel::get(['id', 'label_key', 'label_default', 'label_custom'])
                        ->keyBy('id');

                    $translations = SiteLabelTranslation::where('language_id', $languageId)
                        ->whereNotNull('label_value')
                        ->where('label_value', '!=', '')
                        ->pluck('label_value', 'site_label_id');

                    return $labels->mapWithKeys(function ($label) use ($translations) {
                        return [
                            $label->label_key => [
                                'default' => (string) $label->label_default,
                                'custom'  => $translations[$label->id] ?? null,
                            ],
                        ];
                    })->toArray();
                } catch (\Throwable $e) {
                    Log::error('SiteLabelService::loadAll failed for language ' . $languageId . ': ' . $e->getMessage());
                    // Return empty so the next request retries — do NOT cache failures.
                    return [];
                }
            }
        );

        return $this->runtime[$cacheKey];
    }
}
