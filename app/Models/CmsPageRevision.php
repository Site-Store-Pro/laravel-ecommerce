<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPageRevision extends Model
{
    use HasFactory;

    protected $table = 'cms_page_revisions';

    protected $fillable = [
        'cms_page_id',
        'title',
        'content',
        'meta_title',
        'meta_description',
        'custom_css',
        'custom_js',
        'header_image',
        'background_image',
        'revision_type',
        'author_id',
        'layout_type',
        'left_col',
        'right_col',
        'custom_author',
        'show_author',
        'show_title',
        'show_date',
    ];

    protected $casts = [
        'layout_type' => 'integer',
        'show_author' => 'boolean',
        'show_title' => 'boolean',
        'show_date' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
