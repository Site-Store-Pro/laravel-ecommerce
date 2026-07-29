<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsTestimonial extends Model
{
    use HasFactory;
    use HasTranslations;

    protected function translationForeignKey(): string
    {
        return 'testimonial_id';
    }

    protected $table = 'cms_testimonials';

    protected $fillable = [
        'author_name',
        'author_title',
        'content',
        'avatar_image',
        'rating',
        'company_name',
        'company_link',
        'is_active',
        'sort_order',
    ];

    /** Fields automatically translated when translations relation is loaded. */
    protected array $translatable = [
        'author_name',
        'content',
        'author_title',
        'company_name',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'rating'     => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Scope query to active testimonials ordered by sort_order.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    /**
     * Helper to resolve avatar image URL.
     */
    public function getAvatarUrl(): string
    {
        if (!empty($this->avatar_image)) {
            if (str_starts_with($this->avatar_image, 'http://') || str_starts_with($this->avatar_image, 'https://')) {
                return $this->avatar_image;
            }
            return asset('storage/' . ltrim($this->avatar_image, '/'));
        }

        // Return ui-avatars placeholder if no avatar image
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->author_name) . '&color=4f46e5&background=e0e7ff';
    }
}
