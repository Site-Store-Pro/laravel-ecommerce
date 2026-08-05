<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_faqs', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->longText('answer');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Register the FAQ display plugin
        DB::table('plugins')->updateOrInsert(
            ['shortcode' => 'faqs-2026'],
            [
                'api_id'              => 'cms-faqs-display',
                'name'                => 'FAQ Accordion Display',
                'version'             => '1.0.0',
                'type'                => 'display',
                'author'              => 'System',
                'filename'            => 'FaqsPlugin',
                'install_type'        => 1,
                'description'         => 'Renders all active FAQs as an animated accordion with plus/minus open-close toggling. Supports Tailwind light/dark mode with optional CSS override.',
                'shortcode'           => 'faqs-2026',
                'usage_instructions'  => 'Use [plugin:faqs-2026] in any CMS page body. Optional params: header="FAQ Title" max=20 custom_css=".faq-accordion{}"',
                'activation_status'   => 1,
                'activation_required' => 0,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]
        );

        // Register default plugin options (schema for the settings UI)
        $plugin = DB::table('plugins')->where('shortcode', 'faqs-2026')->first();
        if ($plugin) {
            $options = [
                ['field_name' => 'header_title',   'field_label' => 'Section Header Title',         'field_type' => 'text',     'field_editor' => null,  'field_default_value' => 'Frequently Asked Questions', 'sort_order' => 1],
                ['field_name' => 'show_header',    'field_label' => 'Show Section Header',           'field_type' => 'toggle',   'field_editor' => null,  'field_default_value' => '1',                          'sort_order' => 2],
                ['field_name' => 'open_first',     'field_label' => 'Open First Item By Default',    'field_type' => 'toggle',   'field_editor' => null,  'field_default_value' => '0',                          'sort_order' => 3],
                ['field_name' => 'allow_multiple', 'field_label' => 'Allow Multiple Open at Once',   'field_type' => 'toggle',   'field_editor' => null,  'field_default_value' => '0',                          'sort_order' => 4],
                ['field_name' => 'max_items',      'field_label' => 'Max FAQs to Show (0 = all)',    'field_type' => 'number',   'field_editor' => null,  'field_default_value' => '0',                          'sort_order' => 5],
                ['field_name' => 'custom_css',     'field_label' => 'Custom CSS Override',           'field_type' => 'textarea', 'field_editor' => 'css', 'field_default_value' => '',                           'sort_order' => 6],
            ];
            foreach ($options as $opt) {
                DB::table('plugin_options')->updateOrInsert(
                    ['plugin_id' => $plugin->id, 'field_name' => $opt['field_name']],
                    array_merge($opt, ['plugin_id' => $plugin->id])
                );
            }
        }
    }

    public function down(): void
    {
        if ($plugin = DB::table('plugins')->where('shortcode', 'faqs-2026')->first()) {
            DB::table('plugin_options')->where('plugin_id', $plugin->id)->delete();
            DB::table('plugin_settings')->where('plugin_id', $plugin->id)->delete();
        }
        DB::table('plugins')->where('shortcode', 'faqs-2026')->delete();
        Schema::dropIfExists('cms_faqs');
    }
};
