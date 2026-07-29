<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CmsBuilderBlock extends Model
{
    protected $table = 'cms_builder_blocks';

    protected $fillable = [
        'title',
        'target_element',
        'type',
        'section_type',
        'is_placeholder',
        'sort_desktop',
        'sort_tablet',
        'sort_mobile',
        'content_desktop',
        'content_tablet',
        'content_mobile',
        'is_active_desktop',
        'is_active_tablet',
        'is_active_mobile',
    ];

    protected $casts = [
        'type'              => 'integer',
        'is_placeholder'    => 'boolean',
        'sort_desktop'      => 'float',
        'sort_tablet'       => 'float',
        'sort_mobile'       => 'float',
        'is_active_desktop' => 'boolean',
        'is_active_tablet'  => 'boolean',
        'is_active_mobile'  => 'boolean',
    ];

    public function scopeHeader(Builder $query): Builder
    {
        return $query->where('section_type', 'header');
    }

    public function scopeFooter(Builder $query): Builder
    {
        return $query->where('section_type', 'footer');
    }

    public function scopeActiveForDevice(Builder $query, string $device = 'desktop'): Builder
    {
        $column = match (strtolower($device)) {
            'tablet', 'medium' => 'is_active_tablet',
            'mobile', 'small'  => 'is_active_mobile',
            default            => 'is_active_desktop',
        };

        return $query->where($column, true);
    }

    public function scopeSortForDevice(Builder $query, string $device = 'desktop'): Builder
    {
        $column = match (strtolower($device)) {
            'tablet', 'medium' => 'sort_tablet',
            'mobile', 'small'  => 'sort_mobile',
            default            => 'sort_desktop',
        };

        return $query->orderBy($column, 'asc');
    }

    /**
     * Get content for specific device with fallback to desktop content.
     */
    public function getContentForDevice(string $device = 'desktop'): string
    {
        $content = match (strtolower($device)) {
            'tablet', 'medium' => $this->content_tablet,
            'mobile', 'small'  => $this->content_mobile,
            default            => $this->content_desktop,
        };

        if ($content === null || $content === '') {
            $content = $this->content_desktop ?? '';
        }

        return $content;
    }

    /**
     * Check if block is active for a specific device.
     */
    public function isActiveForDevice(string $device = 'desktop'): bool
    {
        return (bool) match (strtolower($device)) {
            'tablet', 'medium' => $this->is_active_tablet,
            'mobile', 'small'  => $this->is_active_mobile,
            default            => $this->is_active_desktop,
        };
    }
}
