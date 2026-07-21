<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicContentParsingTest extends TestCase
{
    use RefreshDatabase;

    public function test_cms_page_content_blade_parsing(): void
    {
        // 1. Create a page with dynamic Blade conditionals
        $page = CmsPage::create([
            'title' => 'Dynamic Test Page',
            'slug' => 'dynamic-test',
            'content' => 'Hello @if(true) World! @else People! @endif',
            'is_active' => true,
        ]);

        // 2. Fetch via route
        $response = $this->get('/dynamic-test');

        $response->assertStatus(200);
        $response->assertSee('Hello');
        $response->assertSee('World!');
        $response->assertDontSee('People!');
    }

    public function test_cms_page_auth_conditionals(): void
    {
        $page = CmsPage::create([
            'title' => 'Auth Conditional Page',
            'slug' => 'auth-test',
            'content' => 'Content: @auth Welcome User! @else Welcome Guest! @endauth',
            'is_active' => true,
        ]);

        // Guest check
        $this->get('/auth-test')
            ->assertStatus(200)
            ->assertSee('Welcome Guest!')
            ->assertDontSee('Welcome User!');

        // Auth check
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);

        $this->actingAs($user)
            ->get('/auth-test')
            ->assertStatus(200)
            ->assertSee('Welcome User!')
            ->assertDontSee('Welcome Guest!');
    }

    public function test_product_descriptions_blade_parsing(): void
    {
        $product = Product::create([
            'title' => 'Dynamic Product',
            'seo_slug' => 'dynamic-product',
            'short_description' => 'Short: @auth Member Discount @else Regular Price @endauth',
            'long_description' => 'Long: @if(true)Parsed Successfully@endif',
        ]);

        // Guest check
        $response = $this->get('/items/dynamic-product');
        $response->assertStatus(200);
        $response->assertSee('Regular Price');
        $response->assertDontSee('Member Discount');
        $response->assertSee('Parsed Successfully');
    }

    public function test_malformed_blade_syntax_error_fallback(): void
    {
        $page = CmsPage::create([
            'title' => 'Malformed Page',
            'slug' => 'malformed-test',
            'content' => 'Error: @if(unclosed content here',
            'is_active' => true,
        ]);

        // Should load without 500 error, falling back to raw content
        $this->get('/malformed-test')
            ->assertStatus(200)
            ->assertSee('Error: @if(unclosed content here');
    }
}
