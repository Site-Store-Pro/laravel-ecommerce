<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsBuilderBlockTranslation extends Model
{
    protected $table = 'cms_builder_block_translations';

    protected $fillable = [
        'cms_builder_block_id',
        'language_id',
        'title',
        'content_desktop',
        'content_tablet',
        'content_mobile',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(CmsBuilderBlock::class, 'cms_builder_block_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
