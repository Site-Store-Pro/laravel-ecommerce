<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsModal extends Model
{
    use HasTranslations;

    protected $table = 'cms_modals';

    protected array $translatable = ['title', 'body'];

    protected $fillable = [
        'title',
        'body',
        'position',
        'max_width',
        'custom_css',
        'cookie_name',
        'cookie_lifetime',
        'auto_open',
        'open_delay',
        'overlay_dismissible',
        'show_close_button',
        'trigger_selector',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'auto_open'           => 'boolean',
        'overlay_dismissible' => 'boolean',
        'show_close_button'   => 'boolean',
        'is_active'           => 'boolean',
        'cookie_lifetime'     => 'integer',
        'open_delay'          => 'integer',
        'sort_order'          => 'integer',
    ];

    /**
     * The cookie key used to track whether this modal has been dismissed.
     * Falls back to cms_modal_{id} if no custom name is set.
     */
    public function cookieKey(): string
    {
        return !empty($this->cookie_name) ? $this->cookie_name : "cms_modal_{$this->id}";
    }

    /** All translations for this modal. */
    public function translations(): HasMany
    {
        return $this->hasMany(CmsModalTranslation::class, 'cms_modal_id');
    }
}
