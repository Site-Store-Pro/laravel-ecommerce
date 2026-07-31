<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'cms_pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'author_id',
        'expires_at',
        'requires_code',
        'access_code',
        'required_product_id',
        'custom_css',
        'custom_js',
        'header_image',
        'background_image',
        'is_active',
        'exclude_from_search',
        'layout_type',
        'left_col',
        'right_col',
        'custom_author',
        'show_author',
        'show_title',
        'show_date',
        'page_type',
        'page_ranking',
        'hide_page_ranking',
        'custom_sorting',
        'featured_image',
        'featured_image_s3',
        'featured_image_region',
        'featured_image_bucket_name',
        'featured_image_access_key_id',
        'featured_image_secret_access_key',
        'media_image_s3',
        'media_image_region',
        'media_image_bucket_name',
        'media_image_access_key_id',
        'media_image_secret_access_key',
        'featured_image_cdn_url',
        'media_image_cdn_url',
        'background_video',
        'background_video_url',
        'background_video_type',
        'background_video_s3',
        'background_video_region',
        'background_video_bucket_name',
        'background_video_access_key_id',
        'background_video_secret_access_key',
        'background_video_cdn_url',
        'alternate_page_title',
        'page_title_alignment',
        'page_title_css',
        'include_slideshow',
        'min_header_height',
        'cms_search_index',
        'cms_search_index_locked',
    ];

    /** Fields automatically translated when translations relation is loaded. */
    protected array $translatable = [
        'title',
        'content',
        'meta_title',
        'meta_description',
        'alternate_page_title',
        'left_col',
        'right_col',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'requires_code' => 'boolean',
        'is_active' => 'boolean',
        'exclude_from_search' => 'boolean',
        'cms_search_index_locked' => 'boolean',
        'layout_type' => 'integer',
        'show_author' => 'boolean',
        'show_title' => 'boolean',
        'show_date' => 'boolean',
        'page_type' => 'integer',
        'page_ranking' => 'integer',
        'hide_page_ranking' => 'integer',
        'custom_sorting' => 'float',
        'featured_image_s3' => 'integer',
        'media_image_s3' => 'integer',
        'background_video_s3' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (CmsPage $page) {
            $page->rebuildSearchIndex();
        });
    }

    public static function stripShortcodesAndHtml(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Strip bracketed shortcodes e.g. [code-embed:12], [plugin:brands-2026], [plugin:live-search-2026]
        $text = preg_replace('/\[[^\]]+\]/', ' ', $text);

        // Strip HTML tags
        $text = strip_tags($text);

        // Normalize whitespace
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    public function rebuildSearchIndex(bool $force = false): string
    {
        if ($this->cms_search_index_locked && !$force) {
            return (string) $this->cms_search_index;
        }

        $parts = [];

        if (!empty($this->title)) {
            $parts[] = $this->title;
        }
        if (!empty($this->slug)) {
            $parts[] = str_replace(['-', '_'], ' ', $this->slug);
        }
        if (!empty($this->meta_title)) {
            $parts[] = $this->meta_title;
        }
        if (!empty($this->meta_description)) {
            $cleaned = static::stripShortcodesAndHtml($this->meta_description);
            if ($cleaned !== '') $parts[] = $cleaned;
        }
        if (!empty($this->content)) {
            $cleaned = static::stripShortcodesAndHtml($this->content);
            if ($cleaned !== '') $parts[] = $cleaned;
        }
        if (!empty($this->left_col)) {
            $cleaned = static::stripShortcodesAndHtml($this->left_col);
            if ($cleaned !== '') $parts[] = $cleaned;
        }
        if (!empty($this->right_col)) {
            $cleaned = static::stripShortcodesAndHtml($this->right_col);
            if ($cleaned !== '') $parts[] = $cleaned;
        }
        if ($this->relationLoaded('type') && $this->type) {
            $parts[] = $this->type->name;
        }
        if ($this->relationLoaded('categories') && $this->categories) {
            foreach ($this->categories as $cat) {
                $parts[] = $cat->name;
            }
        }
        if ($this->relationLoaded('tags') && $this->tags) {
            foreach ($this->tags as $tag) {
                $parts[] = $tag->name;
            }
        }

        $indexContent = implode(' ', array_filter(array_map('trim', $parts)));
        $this->cms_search_index = $indexContent;

        return $indexContent;
    }

    public function getHeaderImageStorageDisk()
    {
        if ($this->media_image_s3 == 1) {
            return \Storage::disk('s3');
        } elseif ($this->media_image_s3 == 2) {
            $diskName = 'custom_s3_cms_media_' . $this->id;
            config([
                "filesystems.disks.{$diskName}" => [
                    'driver' => 's3',
                    'key' => $this->media_image_access_key_id,
                    'secret' => $this->media_image_secret_access_key,
                    'region' => $this->media_image_region,
                    'bucket' => $this->media_image_bucket_name,
                    'use_path_style_endpoint' => false,
                ]
            ]);
            return \Storage::disk($diskName);
        }

        return \Storage::disk('public');
    }

    public function headerImageUrl(): ?string
    {
        if (!$this->header_image) {
            return null;
        }
        if (str_starts_with($this->header_image, 'http://') || str_starts_with($this->header_image, 'https://')) {
            return $this->header_image;
        }
        if ($this->media_image_s3 == 1 || $this->media_image_s3 == 2) {
            $cdn = $this->media_image_cdn_url ?: config('app.cdn_url');
            if ($cdn) {
                return rtrim($cdn, '/') . '/' . ltrim($this->header_image, '/');
            }
        }
        return $this->getHeaderImageStorageDisk()->url($this->header_image);
    }

    public function backgroundImageUrl(): ?string
    {
        if (!$this->background_image) {
            return null;
        }
        if (str_starts_with($this->background_image, 'http://') || str_starts_with($this->background_image, 'https://')) {
            return $this->background_image;
        }
        if ($this->media_image_s3 == 1 || $this->media_image_s3 == 2) {
            $cdn = $this->media_image_cdn_url ?: config('app.cdn_url');
            if ($cdn) {
                return rtrim($cdn, '/') . '/' . ltrim($this->background_image, '/');
            }
        }
        return $this->getHeaderImageStorageDisk()->url($this->background_image);
    }

    public function getFeaturedImageStorageDisk()
    {
        if ($this->featured_image_s3 == 1) {
            return \Storage::disk('s3');
        } elseif ($this->featured_image_s3 == 2) {
            $diskName = 'custom_s3_cms_' . $this->id;
            config([
                "filesystems.disks.{$diskName}" => [
                    'driver' => 's3',
                    'key' => $this->featured_image_access_key_id,
                    'secret' => $this->featured_image_secret_access_key,
                    'region' => $this->featured_image_region,
                    'bucket' => $this->featured_image_bucket_name,
                    'use_path_style_endpoint' => false,
                ]
            ]);
            return \Storage::disk($diskName);
        }

        return \Storage::disk('public');
    }

    public function featuredImageUrl(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }
        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }
        if ($this->featured_image_s3 == 1 || $this->featured_image_s3 == 2) {
            $cdn = $this->featured_image_cdn_url ?: config('app.cdn_url');
            if ($cdn) {
                return rtrim($cdn, '/') . '/' . ltrim($this->featured_image, '/');
            }
        }
        return $this->getFeaturedImageStorageDisk()->url($this->featured_image);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function requiredProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'required_product_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(CmsPageRevision::class, 'cms_page_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CmsPageType::class, 'page_type');
    }

    public function categories()
    {
        return $this->belongsToMany(CmsPagesCategory::class, 'cms_page_category', 'cms_page_id', 'category_id')->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(CmsPagesTag::class, 'cms_page_tag', 'cms_page_id', 'tag_id')->withTimestamps();
    }

    public function getParsedContentAttribute(): string
    {
        return \App\Services\ContentParserService::parse($this->content);
    }

    public function getParsedLeftColAttribute(): string
    {
        return \App\Services\ContentParserService::parse($this->left_col);
    }

    public function getParsedRightColAttribute(): string
    {
        return \App\Services\ContentParserService::parse($this->right_col);
    }

    /**
     * Resolve the background video URL for this CMS page.
     * Direct URL overrides all other video upload sources if provided.
     */
    public function resolveBackgroundVideoUrl(): ?string
    {
        $cdnUrl = trim($this->background_video_url ?? '');

        // Direct URL override takes highest priority
        if (!empty($cdnUrl)) {
            return $cdnUrl;
        }

        $path = trim($this->background_video ?? '');
        if (empty($path)) {
            return null;
        }

        if ($this->background_video_cdn_url || config('app.cdn_url')) {
            $cdn = $this->background_video_cdn_url ?: config('app.cdn_url');
            return rtrim($cdn, '/') . '/' . ltrim($path, '/');
        }

        $s3Type = (int) ($this->background_video_s3 ?? 0);
        if ($s3Type === 0) {
            return asset('storage/' . ltrim($path, '/'));
        } elseif ($s3Type === 1) {
            $bucket = config('filesystems.disks.s3.bucket', '');
            $region = config('filesystems.disks.s3.region', 'us-east-1');
            return "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($path, '/');
        } elseif ($s3Type === 2) {
            $bucket = $this->background_video_bucket_name;
            $region = $this->background_video_region ?: 'us-east-1';
            if ($bucket) {
                return "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($path, '/');
            }
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
