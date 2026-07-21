<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends Model
{
    use HasFactory;

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
        'alternate_page_title',
        'page_title_alignment',
        'page_title_css',
        'include_slideshow',
        'min_header_height',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'requires_code' => 'boolean',
        'is_active' => 'boolean',
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
    ];

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
}
