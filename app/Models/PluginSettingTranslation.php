<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PluginSettingTranslation extends Model
{
    protected $table = 'plugin_setting_translations';

    protected $fillable = [
        'plugin_id',
        'language_id',
        'field_name',
        'field_value',
    ];

    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
