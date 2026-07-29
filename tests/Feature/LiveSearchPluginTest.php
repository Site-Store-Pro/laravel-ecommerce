<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\Plugin;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveSearchPluginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PluginSeeder::class);
    }

    public function test_cms_pages_use_meta_description_for_search_excerpt(): void
    {
        $user = \App\Models\User::factory()->create();

        $page = CmsPage::create([
            'title'            => 'About Our Company',
            'slug'             => 'about-company',
            'content'          => '<p>Main page content here that is very long...</p>',
            'meta_description' => 'Specialized meta description excerpt for search engines.',
            'is_active'        => true,
            'author_id'        => $user->id,
            'layout_type'      => 1,
        ]);

        $response = $this->getJson('/api/live-search-api?q=Company');
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertNotEmpty($data);

        $pageItem = collect($data)->firstWhere('type', 'page');
        $this->assertNotNull($pageItem);
        $this->assertEquals('Specialized meta description excerpt for search engines.', $pageItem['snippet']);
    }

    public function test_live_search_dropdown_is_limited_to_15_entries(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'title'       => "Search Item {$i}",
                'seo_slug'    => "search-item-{$i}",
                'is_active'   => true,
            ]);
        }

        $response = $this->getJson('/api/live-search-api?q=Search');
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertCount(15, $data);
    }

    public function test_live_search_plugin_renders_styled_form_and_has_default_css_option(): void
    {
        $plugin = Plugin::where('filename', 'live_search_2026')->first();
        $this->assertNotNull($plugin);

        $pluginClass = new \App\Plugins\Display\LiveSearchPlugin();
        $html = $pluginClass->render([], $plugin);

        $this->assertStringContainsString('max-w-[250px]', $html);
        $this->assertStringContainsString('rounded-r-xl rounded-l-none', $html);

        $defaultCssOpt = $plugin->options()->where('field_name', 'default_css')->first();
        $this->assertNotNull($defaultCssOpt);
        $this->assertEquals('text-only', $defaultCssOpt->field_type);
        $this->assertStringContainsString('border-top-left-radius: 0px !important;', $defaultCssOpt->field_default_value);
    }
}
