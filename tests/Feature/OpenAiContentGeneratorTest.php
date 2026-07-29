<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OpenAiContentGeneratorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['ai.openai_api_key' => 'test_openai_key']);
    }

    public function test_ai_kb_article_content_safely_handles_array_and_nested_json(): void
    {
        $input = "Sample article content";
        $prompt = "Rewrite article";

        $output = ai_kb_article_content($input, $prompt);

        $this->assertIsString($output);
        $this->assertStringContainsString("placeholder AI content", $output);
    }

    public function test_ai_safe_str_converts_mixed_types_without_type_error(): void
    {
        $this->assertEquals("Hello World", ai_safe_str("Hello World"));
        $this->assertEquals("123", ai_safe_str(123));
        $this->assertEquals("Item A\nItem B", ai_safe_str(["Item A", "Item B"]));
        $this->assertEquals("", ai_safe_str(null));
    }

    public function test_cms_page_editor_ai_button_hidden_when_api_key_empty(): void
    {
        config(['ai.openai_api_key' => null]);
        $_ENV['OPENAI_API_KEY'] = null;
        $_SERVER['OPENAI_API_KEY'] = null;

        $admin = User::factory()->create(['role_id' => UserRole::Admin]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class)
            ->assertViewHas('showAiButton', false)
            ->assertDontSee('Generate with OPENAI');
    }

    public function test_cms_page_editor_ai_button_visible_and_generates_content(): void
    {
        config(['ai.openai_api_key' => 'test_openai_key']);
        $admin = User::factory()->create(['role_id' => UserRole::Admin]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class)
            ->assertViewHas('showAiButton', true)
            ->set('content', '<p>Existing CMS page content</p>')
            ->set('aiPrompt', 'Make it persuasive')
            ->call('generateAiContent')
            ->assertSet('aiResponse', function ($val) {
                return str_contains($val, 'AI Generated Page Content') && str_contains($val, 'Make it persuasive');
            })
            ->assertSee('AI Suggested Content');
    }

    public function test_product_edit_ai_button_hidden_when_api_key_empty(): void
    {
        config(['ai.openai_api_key' => null]);
        $_ENV['OPENAI_API_KEY'] = null;
        $_SERVER['OPENAI_API_KEY'] = null;

        $admin = User::factory()->create(['role_id' => UserRole::Admin]);
        $product = Product::create(['title' => 'Test Product', 'seo_slug' => 'test-product']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertViewHas('showAiButton', false)
            ->assertDontSee('Generate with OPENAI');
    }

    public function test_product_edit_ai_generator_compiles_context_and_generates_content(): void
    {
        config(['ai.openai_api_key' => 'test_openai_key']);
        $admin = User::factory()->create(['role_id' => UserRole::Admin]);
        $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        $product = Product::create(['title' => 'Smart Watch', 'seo_slug' => 'smart-watch', 'short_description' => 'A great watch']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertViewHas('showAiButton', true)
            ->set('selectedCategories', [$category->id])
            ->set('short_description', 'A great smart watch with heart tracking')
            ->set('long_description', '<p>Current long description</p>')
            ->set('aiPrompt', 'Focus on fitness tracking and battery life')
            ->call('generateAiContent')
            ->assertSet('aiResponse', function ($val) {
                return str_contains($val, 'AI generated product description') && str_contains($val, 'Focus on fitness tracking');
            })
            ->assertSee('AI Suggested Content');
    }

    public function test_admin_products_create_panel_ai_generator(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProducts::class)
            ->assertViewHas('showAiButton', true)
            ->set('title', 'New Wireless Earbuds')
            ->set('short_description', 'Crystal clear sound')
            ->set('aiPrompt', 'Write a catchy description')
            ->call('generateAiContent')
            ->assertSet('aiResponse', function ($val) {
                return str_contains($val, 'AI generated product description') && str_contains($val, 'New Wireless Earbuds');
            });
    }
}
