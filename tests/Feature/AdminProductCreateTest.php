<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AdminProductCreateTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('user_roles')->insertOrIgnore(['id' => 1, 'name' => 'Customer']);
        DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Admin']);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@support.local',
            'password' => bcrypt('password'),
            'role_id' => 3,
        ]);
    }

    public function test_admin_can_create_new_product_with_long_description(): void
    {
        $this->actingAs($this->admin);

        $longDescriptionHtml = '<p>This is a <strong>comprehensive</strong> product description with rich formatting.</p><ul><li>Feature A</li><li>Feature B</li></ul>';

        Livewire::test(\App\Livewire\AdminProductCreate::class)
            ->set('title', 'Brand New Ergonomic Chair')
            ->set('seo_slug', 'brand-new-ergonomic-chair')
            ->set('short_description', 'A short overview of the chair')
            ->set('long_description', $longDescriptionHtml)
            ->set('bullet_point_1', 'High durability')
            ->set('bullet_point_2', 'Breathable mesh')
            ->call('saveProduct')
            ->assertHasNoErrors()
            ->assertRedirect();

        $product = Product::where('title', 'Brand New Ergonomic Chair')->first();

        $this->assertNotNull($product);
        $this->assertEquals($longDescriptionHtml, $product->long_description);
        $this->assertEquals('A short overview of the chair', $product->short_description);
        $this->assertEquals('High durability', $product->bullet_point_1);
        $this->assertEquals('Breathable mesh', $product->bullet_point_2);
    }

    public function test_admin_can_load_and_edit_product_with_long_description(): void
    {
        $this->actingAs($this->admin);

        $initialLongDescription = '<div class="prose"><p>Initial product detailed description.</p></div>';

        $product = Product::create([
            'title' => 'Test Gaming Mouse',
            'seo_slug' => 'test-gaming-mouse',
            'meta_title' => 'Test Gaming Mouse',
            'short_description' => 'A gaming mouse',
            'long_description' => $initialLongDescription,
            'active' => 1,
            'show_in_results' => 1,
            'layout_type' => 1,
        ]);

        $component = Livewire::test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id]);

        // Verify that long_description is properly loaded into the Livewire state
        $this->assertEquals($initialLongDescription, $component->get('long_description'));

        // Update the long description
        $updatedLongDescription = '<div class="prose"><p>Updated product detailed description with new features.</p></div>';
        $component->set('long_description', $updatedLongDescription)
            ->call('updateProduct')
            ->assertHasNoErrors();

        $product->refresh();
        $this->assertEquals($updatedLongDescription, $product->long_description);
    }

    public function test_admin_can_create_and_edit_product_with_rich_html_short_description(): void
    {
        $this->actingAs($this->admin);

        $shortDescriptionHtml = '<p>Compact summary with a <a href="/special" class="btn-theme-primary">Buy Now</a> button and [plugin:featured_items].</p>';
        $longDescriptionHtml = '<div class="prose"><p>Comprehensive specs and warranty details.</p></div>';

        // 1. Create with rich short description
        Livewire::test(\App\Livewire\AdminProductCreate::class)
            ->set('title', 'Ultra Wireless Headphones')
            ->set('seo_slug', 'ultra-wireless-headphones')
            ->set('short_description', $shortDescriptionHtml)
            ->set('long_description', $longDescriptionHtml)
            ->call('saveProduct')
            ->assertHasNoErrors()
            ->assertRedirect();

        $product = Product::where('title', 'Ultra Wireless Headphones')->first();
        $this->assertNotNull($product);
        $this->assertEquals($shortDescriptionHtml, $product->short_description);
        $this->assertEquals($longDescriptionHtml, $product->long_description);

        // 2. Edit with updated rich short description
        $updatedShortHtml = '<p>Updated compact summary with <strong>enhanced noise cancellation</strong>.</p>';
        Livewire::test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertSet('short_description', $shortDescriptionHtml)
            ->set('short_description', $updatedShortHtml)
            ->call('updateProduct')
            ->assertHasNoErrors();

        $product->refresh();
        $this->assertEquals($updatedShortHtml, $product->short_description);
    }
}
