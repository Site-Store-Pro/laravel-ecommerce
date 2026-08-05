<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Find the brands-2026 plugin id
        $plugin = DB::table('plugins')->where('shortcode', 'brands-2026')->first();
        if (! $plugin) {
            return; // plugin not yet seeded — seeder will handle it
        }

        $pluginId = $plugin->id;

        // ── Check which columns plugin_options actually has ────────────────────
        $hasTimestamps = collect(DB::select("SHOW COLUMNS FROM plugin_options"))
            ->pluck('Field')
            ->contains('created_at');

        // ── Plugin Option rows ─────────────────────────────────────────────────
        $newOptions = [
            [
                'plugin_id'           => $pluginId,
                'field_name'          => 'autoplay',
                'field_label'         => 'Autoplay (Slider)',
                'field_type'          => 'select',
                'field_selections'    => 'on,off',
                'field_help'          => 'Auto-advance slides in slider mode.',
                'sort_order'          => 45,
                'field_default_value' => 'on',
                'field_required'      => 'no',
            ],
            [
                'plugin_id'           => $pluginId,
                'field_name'          => 'show_label',
                'field_label'         => 'Show Brand Name Label',
                'field_type'          => 'checkbox',
                'field_selections'    => null,
                'field_help'          => 'Display the brand text name below the logo image.',
                'sort_order'          => 47,
                'field_default_value' => '1',
                'field_required'      => 'no',
            ],
        ];

        foreach ($newOptions as $opt) {
            $exists = DB::table('plugin_options')
                ->where('plugin_id', $pluginId)
                ->where('field_name', $opt['field_name'])
                ->exists();

            if (! $exists) {
                $row = $opt;
                if ($hasTimestamps) {
                    $row['created_at'] = now();
                    $row['updated_at'] = now();
                }
                DB::table('plugin_options')->insert($row);
            }

            // Seed default setting value if not already present
            $hasSettingTimestamps = collect(DB::select("SHOW COLUMNS FROM plugin_settings"))
                ->pluck('Field')
                ->contains('created_at');

            $settingExists = DB::table('plugin_settings')
                ->where('plugin_id', $pluginId)
                ->where('field_name', $opt['field_name'])
                ->exists();

            if (! $settingExists) {
                $settingRow = [
                    'plugin_id'   => $pluginId,
                    'field_name'  => $opt['field_name'],
                    'field_value' => $opt['field_default_value'],
                ];
                if ($hasSettingTimestamps) {
                    $settingRow['created_at'] = now();
                    $settingRow['updated_at'] = now();
                }
                DB::table('plugin_settings')->insert($settingRow);
            }
        }
    }

    public function down(): void
    {
        $plugin = DB::table('plugins')->where('shortcode', 'brands-2026')->first();
        if (! $plugin) {
            return;
        }

        DB::table('plugin_options')
            ->where('plugin_id', $plugin->id)
            ->whereIn('field_name', ['show_label', 'autoplay'])
            ->delete();

        DB::table('plugin_settings')
            ->where('plugin_id', $plugin->id)
            ->whereIn('field_name', ['show_label', 'autoplay'])
            ->delete();
    }
};
