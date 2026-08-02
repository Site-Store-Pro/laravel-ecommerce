<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CmsSetting extends Model
{
    protected $table = 'cms_settings';

    protected $fillable = ['key', 'value', 'label', 'type', 'group', 'sort_order'];

    /**
     * Get all settings as a key=>value array, cached for 60 minutes.
     */
    public static function allCached(): array
    {
        return Cache::remember('cms_settings_all', 60, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Retrieve a single setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::allCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Set / upsert a single setting value and clear the cache.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'label' => $key]
        );
        Cache::forget('cms_settings_all');
        Cache::flush();
    }

    /**
     * Bulk-set multiple settings at once and clear the cache once.
     */
    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value, 'label' => $key]
            );
        }
        Cache::forget('cms_settings_all');
        Cache::flush();
    }

    /**
     * Check if a boolean-type setting is truthy.
     */
    public static function isEnabled(string $key): bool
    {
        $val = static::get($key, false);
        return in_array($val, ['1', 1, true, 'true'], true);
    }

    /**
     * Check if Advanced Shop Search Filtering is enabled.
     */
    public static function isAdvancedSearchEnabled(): bool
    {
        return static::isEnabled('enable_advanced_shop_search');
    }

    /**
     * Get the configured site name, falling back to APP_NAME env.
     */
    public static function getSiteName(): string
    {
        $siteName = static::get('site_name', '');
        return (is_string($siteName) && trim($siteName) !== '') ? trim($siteName) : config('app.name', 'Support Tickets');
    }

    /**
     * Resolve the logo URL or SVG HTML from cms_settings.
     * Returns an array: ['type' => 'url'|'svg'|null, 'value' => '...']
     * Returns ['type' => null, 'value' => null] if no logo is configured.
     */
    public static function resolveLogoUrl(): array
    {
        $settings = static::allCached();
        $type = $settings['logo_type'] ?? null;
        $path = $settings['logo_path'] ?? null;
        $cdnUrl = $settings['logo_cdn_url'] ?? null;
        $svgHtml = $settings['logo_svg_html'] ?? null;

        if (!$type) {
            return ['type' => null, 'value' => null];
        }

        switch ($type) {
            case 'local':
                if ($path) {
                    return ['type' => 'url', 'value' => asset('storage/' . ltrim($path, '/'))];
                }
                break;

            case 's3':
                if ($path) {
                    $bucket = config('filesystems.disks.s3.bucket', '');
                    $region = config('filesystems.disks.s3.region', 'us-east-1');
                    $url = "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($path, '/');
                    if ($cdnUrl) {
                        $url = rtrim($cdnUrl, '/') . '/' . ltrim($path, '/');
                    }
                    return ['type' => 'url', 'value' => $url];
                }
                break;

            case 'custom_s3':
                $bucket  = $settings['logo_s3_bucket'] ?? '';
                $s3Key   = $settings['logo_s3_key'] ?? '';
                $region  = $settings['logo_s3_region'] ?? 'us-east-1';
                if ($bucket && $path) {
                    $url = "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($path, '/');
                    if ($cdnUrl) {
                        $url = rtrim($cdnUrl, '/') . '/' . ltrim($path, '/');
                    }
                    return ['type' => 'url', 'value' => $url];
                }
                break;

            case 'cdn':
                if ($cdnUrl && $path) {
                    return ['type' => 'url', 'value' => rtrim($cdnUrl, '/') . '/' . ltrim($path, '/')];
                }
                break;

            case 'url':
                if ($path) {
                    return ['type' => 'url', 'value' => $path];
                }
                break;

            case 'svg':
                if ($svgHtml) {
                    return ['type' => 'svg', 'value' => $svgHtml];
                }
                break;
        }

        return ['type' => null, 'value' => null];
    }

    /**
     * Resolve the page background image URL from cms_settings.
     * Direct URL overrides all other upload sources if provided.
     */
    public static function resolvePageBgImageUrl(): ?string
    {
        $settings = static::allCached();
        $directUrl = trim($settings['page_bg_image_url'] ?? '');

        // Direct URL override takes highest priority
        if (!empty($directUrl)) {
            return $directUrl;
        }

        $type   = $settings['page_bg_image_type'] ?? null;
        $path   = trim($settings['page_bg_image_path'] ?? '');
        $cdnUrl = trim($settings['page_bg_image_s3_cdn_url'] ?? '');

        if (!$type || empty($path)) return null;

        switch ($type) {
            case 'local':
                return asset('storage/' . ltrim($path, '/'));
            case 's3':
                if (!empty($cdnUrl)) {
                    return rtrim($cdnUrl, '/') . '/' . ltrim($path, '/');
                }
                $bucket = config('filesystems.disks.s3.bucket', '');
                $region = config('filesystems.disks.s3.region', 'us-east-1');
                return "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($path, '/');
            case 'custom_s3':
                $bucket = $settings['page_bg_image_s3_bucket'] ?? '';
                $region = $settings['page_bg_image_s3_region'] ?? 'us-east-1';
                if ($bucket) {
                    if (!empty($cdnUrl)) {
                        return rtrim($cdnUrl, '/') . '/' . ltrim($path, '/');
                    }
                    return "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($path, '/');
                }
                break;
            case 'cdn':
            case 'url':
                return str_starts_with($path, 'http') ? $path : asset('storage/' . ltrim($path, '/'));
        }

        return null;
    }

    /**
     * Resolve the page background video URL from cms_settings.
     * Direct URL overrides all other upload sources if provided.
     */
    public static function resolvePageBgVideoUrl(): ?string
    {
        $settings = static::allCached();
        $directUrl = trim($settings['page_bg_video_url'] ?? '');

        // Direct URL override takes highest priority
        if (!empty($directUrl)) {
            return $directUrl;
        }

        $type   = $settings['page_bg_video_type'] ?? null;
        $path   = trim($settings['page_bg_video_path'] ?? '');
        $cdnUrl = trim($settings['page_bg_video_s3_cdn_url'] ?? '');

        if (!$type || empty($path)) return null;

        switch ($type) {
            case 'local':
                return asset('storage/' . ltrim($path, '/'));
            case 's3':
                if (!empty($cdnUrl)) {
                    return rtrim($cdnUrl, '/') . '/' . ltrim($path, '/');
                }
                $bucket = config('filesystems.disks.s3.bucket', '');
                $region = config('filesystems.disks.s3.region', 'us-east-1');
                return "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($path, '/');
            case 'custom_s3':
                $bucket = $settings['page_bg_video_s3_bucket'] ?? '';
                $region = $settings['page_bg_video_s3_region'] ?? 'us-east-1';
                if ($bucket) {
                    if (!empty($cdnUrl)) {
                        return rtrim($cdnUrl, '/') . '/' . ltrim($path, '/');
                    }
                    return "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($path, '/');
                }
                break;
            case 'cdn':
            case 'url':
                return str_starts_with($path, 'http') ? $path : asset('storage/' . ltrim($path, '/'));
        }

        return null;
    }
}
