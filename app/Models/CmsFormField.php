<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsFormField extends Model
{
    use HasTranslations;

    protected array $translatable = [
        'label',
        'instructions',
        'required_error_message',
        'html_above',
        'options',
    ];

    protected $fillable = [
        'form_id',
        'type',
        'label',
        'instructions',
        'is_required',
        'required_type',
        'required_error_message',
        'html_above',
        'options',
        'sort_order',
        'field_role',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'options'     => 'array',
        'sort_order'  => 'integer',
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function form(): BelongsTo
    {
        return $this->belongsTo(CmsForm::class, 'form_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CmsFormFieldTranslation::class, 'cms_form_field_id');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Returns true for field types that have selectable options.
     */
    public function hasOptions(): bool
    {
        return in_array($this->type, ['select', 'radio', 'checkbox_group']);
    }

    /**
     * Returns true when this field carries a specific opt-in role.
     */
    public function hasRole(string $role): bool
    {
        return $this->field_role === $role;
    }
}
