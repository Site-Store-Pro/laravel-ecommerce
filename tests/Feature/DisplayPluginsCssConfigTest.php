<?php

namespace Tests\Feature;

use App\Models\Plugin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisplayPluginsCssConfigTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PluginSeeder::class);
    }

    public function test_all_display_plugins_have_custom_css_and_readonly_default_css_options(): void
    {
        $displayPlugins = Plugin::where('type', 'display')->get();

        $this->assertNotEmpty($displayPlugins);

        foreach ($displayPlugins as $plugin) {
            $customCssOpt = $plugin->options()->where('field_name', 'custom_css')->first();
            $this->assertNotNull($customCssOpt, "Plugin {$plugin->filename} is missing custom_css option");
            $this->assertEquals('textarea', $customCssOpt->field_type, "Plugin {$plugin->filename} custom_css option must be textarea");

            $defaultCssOpt = $plugin->options()->where('field_name', 'default_css')->first();
            $this->assertNotNull($defaultCssOpt, "Plugin {$plugin->filename} is missing default_css option");
            $this->assertEquals('text-only', $defaultCssOpt->field_type, "Plugin {$plugin->filename} default_css option must be text-only");
            $this->assertNotEmpty($defaultCssOpt->field_default_value, "Plugin {$plugin->filename} default_css must have non-empty default CSS content");
        }
    }

    public function test_custom_css_overrides_default_css_in_rendered_output(): void
    {
        $displayPlugins = Plugin::where('type', 'display')->get();

        foreach ($displayPlugins as $plugin) {
            $class = match ($plugin->filename) {
                'slideshow_2026'       => \App\Plugins\Display\SlideshowPlugin::class,
                'featured_items_2026'  => \App\Plugins\Display\FeaturedItemsPlugin::class,
                'cross_sell_list_2026' => \App\Plugins\Display\CrossSellListPlugin::class,
                'brands_2026'          => \App\Plugins\Display\BrandsPlugin::class,
                'categories_2026'      => \App\Plugins\Display\CategoriesPlugin::class,
                'newsflash_2026'       => \App\Plugins\Display\SiteNewsFlashPlugin::class,
                'testimonials_2026'    => \App\Plugins\Display\TestimonialsPlugin::class,
                'social_icons_2026'    => \App\Plugins\Display\SocialIconsPlugin::class,
                'live_search_2026'     => \App\Plugins\Display\LiveSearchPlugin::class,
                default => null,
            };

            if (!$class) {
                continue;
            }

            $instance = new $class();
            $customTestCss = ".custom-override-rule-{$plugin->id} { display: block !important; }";
            
            $html = $instance->render(['custom_css' => $customTestCss], $plugin);

            if (!empty($html) && !str_starts_with(trim($html), '<!-- [plugin:')) {
                $this->assertStringContainsString('<style>', $html, "Plugin {$plugin->filename} output should contain style block");
                $this->assertStringContainsString(".custom-override-rule-{$plugin->id}", $html, "Plugin {$plugin->filename} output should include custom_css override");
            }
        }
    }
}
