<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Plugin
 *
 * Core plugin registry model. Supports per-language setting overrides via the
 * plugin_setting_translations table (see PluginSettingTranslation).
 */
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

    public function settingTranslations(): HasMany
    {
        return $this->hasMany(PluginSettingTranslation::class, 'plugin_id');
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
        $settings = $this->settings()->pluck('field_value', 'field_name')->toArray();

        foreach ($this->options as $option) {
            if (!array_key_exists($option->field_name, $settings)) {
                $settings[$option->field_name] = $option->field_default_value;
            }

            if ($option->field_type === 'checkbox') {
                $val = $settings[$option->field_name] ?? $option->field_default_value;
                $settings[$option->field_name] = in_array(strtolower(trim((string)$val)), ['1', 'true', 'on', 'yes'], true);
            }
        }

        return $settings;
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
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }
            PluginSetting::updateOrCreate(
                ['plugin_id' => $this->id, 'field_name' => $key],
                ['field_value' => (string) $value]
            );
        }
    }

    public function getOptionsSchema(): Collection
    {
        return $this->options()->orderBy('sort_order')->get();
    }

    /**
     * Returns [field_name => 'Human-Readable Label'] for settings that have
     * per-language translations.  Add entries here as new translatable plugins
     * are introduced.
     */
    public function getTranslatableFields(): array
    {
        $map = [
            'order-tracker-2026' => [
                'header_title'       => 'Form Header Title',
                'order_number_label' => 'Order Number Field Label',
                'email_label'        => 'Email Address Field Label',
                'button_label'       => 'Submit Button Text',
                'error_not_found'    => 'Order Not Found Error Message',
                'status_label'       => 'Status Field Label',
                'date_label'         => 'Order Date Label',
                'total_label'        => 'Order Total Label',
                'tracking_label'     => 'Tracking Number Label',
                'items_label'        => 'Items List Label',
            ],
            'live-search-2026' => [
                'button_label' => 'Button Label',
                'placeholder'  => 'Placeholder Text',
            ],
            'testimonials-2026' => [
                'header_title' => 'Header Title',
            ],
            'events-calendar-2026' => [
                'header_title' => 'Header Title',
            ],
            'faqs-2026' => [
                'header_title' => 'Header Title',
            ],
            'featured-items-2026' => [
                'header_title' => 'Header Title',
            ],
            'categories-2026' => [
                'header_title' => 'Header Title',
            ],
            'brands-2026' => [
                'header_title' => 'Header Title',
            ],
        ];

        if (isset($map[$this->shortcode])) {
            return $map[$this->shortcode];
        }

        $fields = [];
        foreach ($this->options as $opt) {
            if (in_array($opt->field_type, ['input', 'textarea', 'wysiwyg', 'tinymce'])) {
                if (preg_match('/(title|label|heading|text|placeholder|message|msg|header)/i', $opt->field_name)) {
                    $fields[$opt->field_name] = $opt->field_label ?: $opt->field_name;
                }
            }
        }

        return $fields;
    }

    /**
     * Returns the base settings array merged with translated overrides for
     * the given language.  Empty/null translation values are ignored so the
     * base setting acts as the fallback.
     */
    public function getSettingsForLanguage(int $langId): array
    {
        $base      = $this->getSettings();
        $overrides = $this->settingTranslations()
            ->where('language_id', $langId)
            ->pluck('field_value', 'field_name')
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->toArray();

        return array_merge($base, $overrides);
    }

    /**
     * Upserts translation values for a given language.  Only the fields
     * present in $values are written; omitted fields are left unchanged.
     */
    public function saveSettingsForLanguage(int $langId, array $values): void
    {
        foreach ($values as $fieldName => $value) {
            PluginSettingTranslation::updateOrCreate(
                [
                    'plugin_id'   => $this->id,
                    'language_id' => $langId,
                    'field_name'  => $fieldName,
                ],
                ['field_value' => $value]
            );
        }
    }
}
