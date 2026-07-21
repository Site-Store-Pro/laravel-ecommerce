<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsSlideshow extends Model
{
    protected $table = 'cms_slideshows';
    protected $primaryKey = 'slideshow_id';

    protected $fillable = [
        'slideshow_name',
        'slideshow_active',
        'sort_order',
        'slide_show_alignment',
    ];

    protected $casts = [
        'slideshow_active' => 'integer',
        'sort_order' => 'integer',
    ];

    public function slides(): HasMany
    {
        return $this->hasMany(CmsSlide::class, 'slideshow_id', 'slideshow_id')
            ->orderBy('ImageSort');
    }

    public static function alignmentOptions(): array
    {
        return [
            'top-left'      => 'Top Left',
            'top-center'    => 'Top Center',
            'top-right'     => 'Top Right',
            'middle-left'   => 'Middle Left',
            'middle-center' => 'Middle Center',
            'middle-right'  => 'Middle Right',
            'bottom-left'   => 'Bottom Left',
            'bottom-center' => 'Bottom Center',
            'bottom-right'  => 'Bottom Right',
        ];
    }
}
