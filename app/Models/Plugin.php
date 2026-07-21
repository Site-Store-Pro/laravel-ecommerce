<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Plugin extends Model
{
    protected $table = 'plugins';

    protected $fillable = [
        'api_id',
        'name',
        'version',
        'type',
        'author',
        'filename',
        'install_type',
        'description',
        'shortcode',
        'activation_required',
        'activation_instructions',
        'activation_failed_msg',
        'activation_success_msg',
        'usage_instructions',
        'help_info',
        'help_url',
        'activation_date',
        'activation_status',
        'activation_key',
        'serial_number'
    ];

    public function options(): HasMany
    {
        return $this->hasMany(PluginOption::class, 'plugin_id');
    }

    public function settings(): HasMany
    {
        return $this->hasMany(PluginSetting::class, 'plugin_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activation_status', 1);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function getSettings(): array
    {
        return $this->settings()->pluck('field_value', 'field_name')->toArray();
    }

    public function getSetting(string $field, mixed $default = null): mixed
    {
        $setting = $this->settings()->where('field_name', $field)->first();
        if ($setting) {
            return $setting->field_value;
        }

        $option = $this->options()->where('field_name', $field)->first();
        if ($option) {
            return $option->field_default_value;
        }

        return $default;
    }

    public function saveSettings(array $values): void
    {
        foreach ($values as $key => $value) {
            PluginSetting::updateOrCreate(
                ['plugin_id' => $this->id, 'field_name' => $key],
                ['field_value' => $value]
            );
        }
    }

    public function getOptionsSchema(): Collection
    {
        return $this->options()->orderBy('sort_order')->get();
    }
}
