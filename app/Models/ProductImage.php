<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_images';

    protected $touches = ['variant'];

    protected $fillable = [
        'variant_id',
        'thumbnail_path',
        'main_path',
        'zoom_path',
        'image_alt',
        'zoom_label',
        'image_s3',
        'image_url_source',
        'cdn_url',
        'search_image',
        'active',
        'image_s3_region',
        'image_s3_bucket_name',
        'image_s3_access_key_id',
        'image_s3_secret_access_key',
    ];

    protected $casts = [
        'image_url_source' => 'integer',
        'search_image'     => 'integer',
        'active'           => 'integer',
    ];


    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getStorageDisk()
    {
        if ($this->image_s3 == 1) {
            return Storage::disk('s3');
        } elseif ($this->image_s3 == 2) {
            $diskName = 'custom_image_s3_' . ($this->id ?: ($this->variant_id ?: 'temp'));
            config([
                "filesystems.disks.{$diskName}" => [
                    'driver' => 's3',
                    'key' => $this->image_s3_access_key_id,
                    'secret' => $this->image_s3_secret_access_key,
                    'region' => $this->image_s3_region,
                    'bucket' => $this->image_s3_bucket_name,
                    'use_path_style_endpoint' => false,
                ]
            ]);
            return Storage::disk($diskName);
        }

        return Storage::disk('public');
    }

    private function resolveUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // Direct external URL — use the path value as-is
        if ($this->image_url_source == 1) {
            return $path;
        }

        if ($this->image_s3 == 1 || $this->image_s3 == 2) {
            $cdn = $this->cdn_url ?: config('app.cdn_url');
            if ($cdn) {
                return rtrim($cdn, '/') . '/' . ltrim($path, '/');
            }

            if ($this->image_s3 == 1) {
                return Storage::disk('s3')->url($path);
            } else {
                return $this->getStorageDisk()->url($path);
            }
        }

        return Storage::disk('public')->url($path);
    }

    public function thumbnailUrl(): ?string
    {
        return $this->resolveUrl($this->thumbnail_path);
    }

    public function mainUrl(): ?string
    {
        return $this->resolveUrl($this->main_path);
    }

    public function zoomUrl(): ?string
    {
        return $this->resolveUrl($this->zoom_path);
    }
}
