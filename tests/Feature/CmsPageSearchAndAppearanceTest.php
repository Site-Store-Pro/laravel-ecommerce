<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\NavMenu;
use App\Models\CmsSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsPageSearchAndAppearanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_cms_page_exclude_from_search_hides_page_from_search_api_and_results(): void
    {
        $includedPage = CmsPage::create([
            'title' => 'Alpha Public Information Page',
            'slug' => 'alpha-public-info',
            'content' => 'Public content for testing search',
            'is_active' => true,
            'exclude_from_search' => false,
        ]);

        $excludedPage = CmsPage::create([
            'title' => 'Alpha Secret Hidden Page',
            'slug' => 'alpha-secret-hidden',
            'content' => 'Hidden content for testing search',
            'is_active' => true,
            'exclude_from_search' => true,
        ]);

        $response = $this->getJson('/api/live-search-api?q=Alpha');
        $response->assertStatus(200);

        $data = $response->json();
        $titles = collect($data)->pluck('title')->toArray();

        $this->assertContains('Alpha Public Information Page', $titles);
        $this->assertNotContains('Alpha Secret Hidden Page', $titles);

        // Check type_label is 'Site Page'
        $pageResult = collect($data)->firstWhere('title', 'Alpha Public Information Page');
        $this->assertNotNull($pageResult);
        $this->assertEquals('Site Page', $pageResult['type_label']);
    }

    public function test_nav_menu_supports_sticky_body_offset(): void
    {
        $menu = NavMenu::create([
            'name' => 'Main Navigation',
            'slug' => 'main-nav',
            'is_primary' => true,
            'is_active' => true,
            'sticky' => true,
            'sticky_body_offset' => '80px',
        ]);

        $this->assertEquals('80px', $menu->fresh()->sticky_body_offset);
    }

    public function test_secondary_button_settings_can_be_saved_and_retrieved(): void
    {
        CmsSetting::set('theme_secondary_bg_color', 'transparent');
        CmsSetting::set('theme_secondary_text_color', '#4f46e5');
        CmsSetting::set('theme_secondary_border_color', '#4f46e5');

        $this->assertEquals('transparent', CmsSetting::get('theme_secondary_bg_color'));
        $this->assertEquals('#4f46e5', CmsSetting::get('theme_secondary_text_color'));
        $this->assertEquals('#4f46e5', CmsSetting::get('theme_secondary_border_color'));
    }

    public function test_openai_responses_are_wrapped_in_prose_class_div(): void
    {
        require_once app_path('Includes/ai_kb_article_content.php');

        config(['ai.openai_api_key' => 'test_openai_key']);

        $cmsRes = ai_cms_page_content('Original page content', 'Make it better');
        $this->assertStringStartsWith('<div class="prose prose-slate max-w-none" style="max-width: none !important; width: 100%;">', $cmsRes);
        $this->assertStringEndsWith('</div>', trim($cmsRes));

        $prodRes = ai_product_description_content('Product title: Widget', 'Sell this widget');
        $this->assertStringStartsWith('<div class="prose prose-slate max-w-none" style="max-width: none !important; width: 100%;">', $prodRes);
        $this->assertStringEndsWith('</div>', trim($prodRes));
    }
}
