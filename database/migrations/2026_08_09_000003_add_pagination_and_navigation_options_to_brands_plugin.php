<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Plugin;
use App\Models\PluginOption;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $plugin = Plugin::where('shortcode', 'brands-2026')->first()
               ?? Plugin::where('filename', 'brands_2026')->first();

        if ($plugin) {
            PluginOption::updateOrCreate(
                ['plugin_id' => $plugin->id, 'field_name' => 'show_navigation'],
                [
                    'field_label'         => 'Show Navigation Arrows',
                    'field_type'          => 'checkbox',
                    'field_default_value' => '1',
                    'sort_order'          => 7,
                ]
            );

            PluginOption::updateOrCreate(
                ['plugin_id' => $plugin->id, 'field_name' => 'show_pagination'],
                [
                    'field_label'         => 'Show Pagination Dots',
                    'field_type'          => 'checkbox',
                    'field_default_value' => '1',
                    'sort_order'          => 8,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $plugin = Plugin::where('shortcode', 'brands-2026')->first()
               ?? Plugin::where('filename', 'brands_2026')->first();

        if ($plugin) {
            PluginOption::where('plugin_id', $plugin->id)
                ->whereIn('field_name', ['show_navigation', 'show_pagination'])
                ->delete();
        }
    }
};
