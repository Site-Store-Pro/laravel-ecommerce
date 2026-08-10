<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class CmsSlide extends Model
{
    use HasTranslations;

    /**
     * Fields automatically translated when translations relation is eager-loaded
     * and a non-default language is active.
     */
    protected array $translatable = [
        'slide_heading',
        'slide_sub_heading',
        'slide_callout_button_label',
    ];

    protected function translationForeignKey(): string
    {
        return 'cms_slide_id';
    }

    protected $table = 'cms_slides';
    protected $primaryKey = 'id';

    protected $fillable = [
        'Title',
        'Description',
        'SlideURL',
        'LargeImage',
        'Thumbnail',
        'Active',
        'ImageSort',
        'slide_heading',
        'slide_sub_heading',
        'slide_content_css',
        'slide_heading_css',
        'slide_alignment',
        'slide_callout_button_label',
        'slideshow_id',
        'mobile_image',
        // External URL overrides — take full priority over uploaded files
        'cdn_image',
        'cdn_mobile_image',
        'cdn_thumbnail',
        // CDN dimension hints
        'cdn_image_width',
        'cdn_image_height',
        'cdn_mobile_image_height',
        'cdn_mobile_image_width',
        // Storage config
        'image_s3',
        'image_s3_region',
        'image_s3_bucket',
        'image_s3_key',
        'image_s3_secret',
        'cdn_url',
    ];

    protected $casts = [
        'Active'                 => 'integer',
        'ImageSort'              => 'double',
        'slideshow_id'           => 'integer',
        'cdn_image_width'        => 'integer',
        'cdn_image_height'       => 'integer',
        'cdn_mobile_image_height'=> 'integer',
        'cdn_mobile_image_width' => 'integer',
        'image_s3'               => 'integer',
    ];

    public function slideshow(): BelongsTo
    {
        return $this->belongsTo(CmsSlideshow::class, 'slideshow_id', 'slideshow_id');
    }

    // ── Storage ───────────────────────────────────────────────────────────────

    /**
     * Returns a Laravel Storage disk instance for this slide's configured storage.
     *  0 = local public disk
     *  1 = S3 from .env credentials
     *  2 = S3 with custom per-slide credentials
     */
    public function getStorageDisk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        if ($this->image_s3 === 2) {
            $diskName = 'cms_slide_s3_' . $this->id;
            Config::set("filesystems.disks.{$diskName}", [
                'driver'                  => 's3',
                'key'                     => $this->image_s3_key,
                'secret'                  => $this->image_s3_secret,
                'region'                  => $this->image_s3_region,
                'bucket'                  => $this->image_s3_bucket,
                'use_path_style_endpoint' => false,
            ]);
            return Storage::disk($diskName);
        }

        if ($this->image_s3 === 1) {
            return Storage::disk('s3');
        }

        return Storage::disk('public');
    }

    // ── URL resolution ────────────────────────────────────────────────────────

    /**
     * Resolves a stored file path to a public URL.
     * Priority:
     *   1. cdn_url prefix + path  (when image_s3 > 0 and cdn_url is set)
     *   2. Storage disk URL       (when image_s3 > 0)
     *   3. public disk URL        (local storage)
     */
    private function resolveStoredUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if ($this->image_s3 > 0 && $this->cdn_url) {
            return rtrim($this->cdn_url, '/') . '/' . ltrim($path, '/');
        }

        return $this->getStorageDisk()->url($path);
    }

    /**
     * Desktop / hero image URL.
     * Priority:
     *   1. cdn_image field (full external URL) — overrides everything
     *   2. LargeImage uploaded file
     */
    public function desktopImageUrl(): ?string
    {
        // External URL override takes absolute priority
        if (!empty($this->cdn_image)) {
            return $this->cdn_image;
        }

        return $this->resolveStoredUrl($this->LargeImage);
    }

    /**
     * Mobile image URL.
     * Priority:
     *   1. cdn_mobile_image field (full external URL) — overrides everything
     *   2. mobile_image uploaded file
     */
    public function mobileImageUrl(): ?string
    {
        if (!empty($this->cdn_mobile_image)) {
            return $this->cdn_mobile_image;
        }

        return $this->resolveStoredUrl($this->mobile_image);
    }

    /**
     * Thumbnail URL.
     * Priority:
     *   1. cdn_thumbnail field (full external URL) — overrides everything
     *   2. Thumbnail uploaded file
     */
    public function thumbnailUrl(): ?string
    {
        if (!empty($this->cdn_thumbnail)) {
            return $this->cdn_thumbnail;
        }

        $stored = $this->resolveStoredUrl($this->Thumbnail);
        if (!empty($stored)) {
            return $stored;
        }

        return $this->desktopImageUrl() ?: $this->mobileImageUrl();
    }
}
