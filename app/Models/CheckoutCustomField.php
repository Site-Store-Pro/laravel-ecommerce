<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutCustomField extends Model
{
    protected $fillable = [
        'type',
        'label',
        'instructions',
        'is_required',
        'required_type',
        'required_error_message',
        'html_above',
        'options',
        'position',
        'show_for',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'options'     => 'array',
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
    ];

    /**
     * Returns true for field types that use an options list.
     */
    public function hasOptions(): bool
    {
        return in_array($this->type, ['select', 'radio', 'checkbox_group']);
    }
}
