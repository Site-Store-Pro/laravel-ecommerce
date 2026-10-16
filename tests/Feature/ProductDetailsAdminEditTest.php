<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductDetailsAdminEditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\DB::table('user_roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'User', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_product_detail_edit_button_visibility(): void
    {
        // Create product and variant
        $product = Product::create([
            'title' => 'Test Product Extra',
            'seo_slug' => 'test-product-extra',
            'description' => 'Test Description',
            'sku_prefix' => 'TPE',
            'is_active' => true,
        ]);
        $product->refresh();

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TPE-VAR',
            'public_price' => 15.00,
            'wholesale_price' => 12.00,
            'quantity_available' => 10,
        ]);

        // 1. Guest user
        $componentGuest = Livewire::test(\App\Livewire\ProductDetails::class, ['seo_link' => $product->seo_slug]);
        $componentGuest->assertDontSee('Edit Product (Admin)');

        // 2. Regular user (role_id = 1)
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'email_verified_at' => now(),
        ]);
        $componentUser = Livewire::actingAs($user)->test(\App\Livewire\ProductDetails::class, ['seo_link' => $product->seo_slug]);
        $componentUser->assertDontSee('Edit Product (Admin)');

        // 3. Admin user (role_id = 3)
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@support.local',
            'password' => bcrypt('password'),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);
        $componentAdmin = Livewire::actingAs($admin)->test(\App\Livewire\ProductDetails::class, ['seo_link' => $product->seo_slug]);
        $componentAdmin->assertSee('Edit Product (Admin)');
        $componentAdmin->assertSeeHtml('target="admin_product_edit"');
        $componentAdmin->assertSeeHtml(route('admin.ecommerce.product-edit', ['id' => $product->id]));
    }

    public function test_admin_can_toggle_and_save_lock_mobile_gallery(): void
    {
        $product = Product::create([
            'title' => 'Mobile Sticky Test Product',
            'seo_slug' => 'mobile-sticky-test-product',
            'active' => 1,
            'lock_mobile_gallery' => 0,
        ]);

        $admin = User::create([
            'name' => 'Admin User 2',
            'email' => 'admin_test2@support.local',
            'password' => bcrypt('password'),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);

        // 1. Save via updateAdvancedSettings
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertSet('lock_mobile_gallery', false)
            ->set('lock_mobile_gallery', true)
            ->call('updateAdvancedSettings')
            ->assertHasNoErrors()
            ->assertSet('lock_mobile_gallery', true);

        $product->refresh();
        $this->assertEquals(1, $product->lock_mobile_gallery);

        // Verify component re-mount remembers state
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertSet('lock_mobile_gallery', true);

        // 2. Toggle OFF and save via saveAllSections
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertSet('lock_mobile_gallery', true)
            ->set('lock_mobile_gallery', false)
            ->call('saveAllSections')
            ->assertHasNoErrors()
            ->assertSet('lock_mobile_gallery', false);

        $product->refresh();
        $this->assertEquals(0, $product->lock_mobile_gallery);

        // 3. Toggle ON again and save via saveAllSections
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertSet('lock_mobile_gallery', false)
            ->set('lock_mobile_gallery', true)
            ->call('saveAllSections')
            ->assertHasNoErrors()
            ->assertSet('lock_mobile_gallery', true);

        $product->refresh();
        $this->assertEquals(1, $product->lock_mobile_gallery);

        // Verify storefront details view renders mobile-locked-gallery-container
        $response = Livewire::test(\App\Livewire\ProductDetails::class, ['seo_link' => $product->seo_slug]);
        $response->assertSeeHtml('mobile-locked-gallery-container');
    }
}
