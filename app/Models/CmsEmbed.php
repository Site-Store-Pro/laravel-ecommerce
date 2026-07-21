<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsEmbed extends Model
{
    use HasFactory;

    protected $table = 'cms_embeds';

    /** Embed type constants */
    const TYPE_YOUTUBE = 0;
    const TYPE_VIMEO   = 1;
    const TYPE_OTHER   = 2;

    protected $fillable = [
        'name',
        'embed_type',
        'code_snippet',
        'is_active',
    ];

    protected $casts = [
        'embed_type' => 'integer',
        'is_active'  => 'boolean',
    ];

    /**
     * True when the embed type is YouTube or Vimeo — these get
     * wrapped in a responsive 16:9 container when rendered.
     */
    public function isVideo(): bool
    {
        return in_array((int) $this->embed_type, [self::TYPE_YOUTUBE, self::TYPE_VIMEO]);
    }

    /**
     * Human-readable type label for admin UI display.
     */
    public function typeLabel(): string
    {
        return match ((int) $this->embed_type) {
            self::TYPE_YOUTUBE => 'YouTube',
            self::TYPE_VIMEO   => 'Vimeo',
            default            => 'Other HTML',
        };
    }

    /**
     * Tailwind badge color classes per embed type.
     */
    public function typeBadgeColor(): string
    {
        return match ((int) $this->embed_type) {
            self::TYPE_YOUTUBE => 'bg-red-100 text-red-700 border-red-200',
            self::TYPE_VIMEO   => 'bg-sky-100 text-sky-700 border-sky-200',
            default            => 'bg-slate-100 text-slate-600 border-slate-200',
        };
    }

    /**
     * Shortcode string for inserting this embed into CMS content.
     */
    public function shortcode(): string
    {
        return '[code-embed:' . $this->id . ']';
    }
}
