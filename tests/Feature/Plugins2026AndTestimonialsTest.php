<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CmsTestimonial;
use App\Models\User;
use App\Models\UserRole;
use App\Services\ContentParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class Plugins2026AndTestimonialsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PluginSeeder::class);
    }

    public function test_category_image_field_and_admin_category_management(): void
    {
        $admin = User::factory()->create([
            'role_id' => \App\Enums\UserRole::Admin,
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\AdminEcommerceCategories::class)
            ->set('name', 'Test Category 2026')
            ->set('slug', 'test-category-2026')
            ->set('category_image', 'https://cdn.example.com/test-cat.jpg')
            ->call('saveCategory')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('product_categories', [
            'slug' => 'test-category-2026',
            'category_image' => 'https://cdn.example.com/test-cat.jpg',
        ]);
    }

    public function test_testimonials_admin_crud_manager(): void
    {
        $admin = User::factory()->create([
            'role_id' => \App\Enums\UserRole::Admin,
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\AdminTestimonialsManager::class)
            ->set('author_name', 'Sarah Parker')
            ->set('author_title', 'CEO & Founder')
            ->set('content', 'Exceptional service and product quality!')
            ->set('rating', 5)
            ->set('is_active', true)
            ->call('saveTestimonial')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cms_testimonials', [
            'author_name' => 'Sarah Parker',
            'author_title' => 'CEO & Founder',
            'rating' => 5,
        ]);
    }

    public function test_top_level_categories_plugin_shortcode(): void
    {
        Category::create([
            'name' => 'Top Level Alpha',
            'slug' => 'top-level-alpha',
            'category_image' => 'https://cdn.example.com/alpha.jpg',
            'parent_id' => null,
            'sort_order' => 1,
            'is_visible_in_menu' => true,
        ]);

        $parsed = ContentParserService::parse('[plugin:categories-2026]');

        $this->assertStringContainsString('Top Level Alpha', $parsed);
        $this->assertStringContainsString('https://cdn.example.com/alpha.jpg', $parsed);
    }

    public function test_brands_plugin_shortcode(): void
    {
        Brand::create([
            'name' => 'Brand Vanguard',
            'slug' => 'brand-vanguard',
            'brand_icon' => 'https://cdn.example.com/vanguard-logo.png',
        ]);

        $parsed = ContentParserService::parse('[plugin:brands-2026]');

        $this->assertStringContainsString('Brand Vanguard', $parsed);
        $this->assertStringContainsString('https://cdn.example.com/vanguard-logo.png', $parsed);
    }

    public function test_brand_is_visible_in_menu_filtering(): void
    {
        $visibleBrand = Brand::create([
            'name' => 'Visible Apex Brand',
            'slug' => 'visible-apex-brand',
            'is_visible_in_menu' => true,
        ]);

        $hiddenBrand = Brand::create([
            'name' => 'Hidden Stealth Brand',
            'slug' => 'hidden-stealth-brand',
            'is_visible_in_menu' => false,
        ]);

        $visibleBrands = Brand::visibleInMenu()->get();
        $this->assertTrue($visibleBrands->contains($visibleBrand));
        $this->assertFalse($visibleBrands->contains($hiddenBrand));

        Livewire::test(\App\Livewire\PublicBrandsMenu::class)
            ->assertSee('Visible Apex Brand')
            ->assertDontSee('Hidden Stealth Brand');
    }

    public function test_testimonials_plugin_shortcode(): void
    {
        CmsTestimonial::create([
            'author_name' => 'David Miller',
            'content' => 'Outstanding e-commerce experience!',
            'rating' => 5,
            'is_active' => true,
        ]);

        $parsed = ContentParserService::parse('[plugin:testimonials-2026]');

        $this->assertStringContainsString('David Miller', $parsed);
        $this->assertStringContainsString('Outstanding e-commerce experience!', $parsed);
    }

    public function test_newsflash_plugin_shortcode(): void
    {
        $parsed = ContentParserService::parse('[plugin:newsflash-2026 message="Flash Sale Today Only!"]');

        $this->assertStringContainsString('Flash Sale Today Only!', $parsed);
    }

    public function test_social_icons_plugin_shortcode(): void
    {
        $parsed = ContentParserService::parse('[plugin:social-icons-2026 facebook="https://facebook.com/store" phone="1-800-555-0199" email="support@store.com"]');

        $this->assertStringContainsString('https://facebook.com/store', $parsed);
        $this->assertStringContainsString('tel:18005550199', $parsed);
        $this->assertStringContainsString('mailto:support@store.com', $parsed);
        $this->assertStringContainsString('fa-solid fa-phone', $parsed);
        $this->assertStringContainsString('fa-solid fa-envelope', $parsed);
    }

    public function test_social_icons_plugin_icon_styles(): void
    {
        // 1. Default non-circle (transparent icon only)
        $defaultParsed = ContentParserService::parse('[plugin:social-icons-2026 facebook="https://facebook.com/store"]');
        $this->assertStringContainsString('bg-transparent', $defaultParsed);
        $this->assertStringNotContainsString('rounded-full bg-slate-100', $defaultParsed);

        // 2. Explicit circle style
        $circleParsed = ContentParserService::parse('[plugin:social-icons-2026 style=circle facebook="https://facebook.com/store"]');
        $this->assertStringContainsString('rounded-full bg-slate-100', $circleParsed);
    }

    public function test_live_search_plugin_shortcode_and_api_endpoint(): void
    {
        \App\Models\Product::create([
            'title' => 'Alpha Precision Gadget',
            'seo_slug' => 'alpha-precision-gadget',
            'short_description' => 'High quality gadget',
            'is_active' => true,
        ]);

        \App\Models\CmsPage::create([
            'title' => 'Alpha Documentation Guide',
            'slug' => 'alpha-docs',
            'content' => 'Complete guide for Alpha Gadget',
            'is_active' => true,
        ]);

        \App\Models\KbArticle::create([
            'title' => 'Alpha Troubleshooting FAQ',
            'seo_link' => 'alpha-faq',
            'article_content' => 'How to solve Alpha errors',
            'article_active' => 1,
        ]);

        CmsTestimonial::create([
            'author_name' => 'Alpha User John',
            'content' => 'Love this Alpha product!',
            'rating' => 5,
            'is_active' => true,
        ]);

        // Input Widget Test
        $inputWidget = ContentParserService::parse('[plugin:live-search-2026 placeholder="Find products..." button_label="Find"]');
        $this->assertStringContainsString('Find products...', $inputWidget);
        $this->assertStringContainsString('Find', $inputWidget);

        // Results View Test
        $resultsView = ContentParserService::parse('[plugin:live-search-2026 mode=results query="Alpha"]');
        $this->assertStringContainsString('Alpha Precision Gadget', $resultsView);
        $this->assertStringContainsString('Alpha Documentation Guide', $resultsView);
        $this->assertStringContainsString('Alpha Troubleshooting FAQ', $resultsView);
        $this->assertStringContainsString('Alpha User John', $resultsView);

        // Live Search API Endpoint Test
        $response = $this->get('/api/live-search-api?q=Alpha');
        $response->assertStatus(200);
        $json = $response->json();

        $this->assertGreaterThanOrEqual(4, count($json));
        $types = array_column($json, 'type');
        $this->assertContains('product', $types);
        $this->assertContains('page', $types);
        $this->assertContains('kb', $types);
        $this->assertContains('testimonial', $types);
    }
}
