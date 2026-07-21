<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PluginSetting extends Model
{
    protected $table = 'plugin_settings';

    public $timestamps = false;

    protected $fillable = [
        'plugin_id',
        'field_name',
        'field_value'
    ];

    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class, 'plugin_id');
    }
}
