<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteLabelSection extends Model
{
    protected $table = 'site_label_sections';

    protected $fillable = ['id', 'name', 'slug', 'description', 'sort_order'];

    /** Disable auto-increment so we can use manual IDs (1-13). */
    public $incrementing = false;

    protected $keyType = 'int';

    public function labels(): HasMany
    {
        return $this->hasMany(SiteLabel::class, 'section_id', 'id');
    }
}
