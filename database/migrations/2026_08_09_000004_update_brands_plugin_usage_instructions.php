<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Plugin;

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
            $plugin->usage_instructions = '<p>Add <strong>[plugin:brands-2026]</strong> to any page. Options: <code>display=slider|grid|list</code>, <code>max=12</code>, <code>cols=4</code>, <code>header="Featured Brands"</code>, <code>autoplay=on|off</code>, <code>show_label=1|0</code> (show/hide text brand name under logo), <code>show_navigation=1|0</code> (show/hide slider prev/next arrows), <code>show_pagination=1|0</code> (show/hide slider pagination dots).</p>';
            $plugin->save();
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
            $plugin->usage_instructions = '<p>Add <strong>[plugin:brands-2026]</strong> to any page. Options: <code>display=slider|grid|list</code>, <code>max=12</code>, <code>cols=4</code>, <code>header="Featured Brands"</code>, <code>autoplay=on|off</code>, <code>show_label=1|0</code> (show/hide text brand name under logo).</p>';
            $plugin->save();
        }
    }
};
