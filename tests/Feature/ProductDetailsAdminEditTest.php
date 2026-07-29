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
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);
        $componentAdmin = Livewire::actingAs($admin)->test(\App\Livewire\ProductDetails::class, ['seo_link' => $product->seo_slug]);
        $componentAdmin->assertSee('Edit Product (Admin)');
        $componentAdmin->assertSeeHtml('target="admin_product_edit"');
        $componentAdmin->assertSeeHtml(route('admin.ecommerce.product-edit', ['id' => $product->id]));
    }
}
