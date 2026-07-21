<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PluginOption extends Model
{
    protected $table = 'plugin_options';

    public $timestamps = false;

    protected $fillable = [
        'plugin_id',
        'field_name',
        'field_label',
        'field_type',
        'field_data_format',
        'field_default_value',
        'field_selections',
        'field_min_value',
        'field_max_value',
        'field_editor',
        'field_help',
        'field_required',
        'field_error_msg',
        'field_html',
        'sort_order'
    ];

    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class, 'plugin_id');
    }
}
