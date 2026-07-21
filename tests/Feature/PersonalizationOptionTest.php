<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PersonalizationOptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_variant_personalization_option_rendering_and_cart_addition(): void
    {
        // 1. Create a product with a variant having personalization active and a fee of $5.50
        $product = Product::create([
            'title' => 'Personalized Gift Item',
            'short_description' => 'A customizable gift item.',
            'long_description' => 'Full description.',
            'seo_slug' => 'personalized-gift-item',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'GIFT-PERS-01',
            'public_price' => 20.00,
            'wholesale_price' => 18.00,
            'personalization_active' => true,
            'personalization_fee' => 5.50,
        ]);

        // Create initial inventory so it shows as in stock
        \App\Models\ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 10,
            'reserved_stock' => 0,
            'location_id' => 1,
        ]);

        // 2. Test rendering on storefront product details page
        $component = Livewire::test(\App\Livewire\ProductDetails::class, ['seo_link' => 'personalized-gift-item']);
        
        $component->assertSee('Gift Wrapping')
            ->assertSee('Personalization')
            ->assertSee('Add Gift Wrapping / Personalization')
            ->assertSee('+$5.50');

        // By default, personalization is not selected, price is base price ($20.00)
        $this->assertEquals(20.00, $component->get('calculatedPrice'));

        // 3. Toggle personalization selection
        $component->set('personalization_selected', true)
            ->set('personalization_text', 'Engrave Name: Antigravity');

        // Calculated price must now include the $5.50 fee ($20.00 + $5.50 = $25.50)
        $this->assertEquals(25.50, $component->get('calculatedPrice'));

        // 4. Add to cart
        $component->call('addToCart');

        // Check if item was added to shopping cart logs with customizations populated
        $cartItem = \App\Models\ShoppingCartLog::where('order_id', 0)->first();
        $this->assertNotNull($cartItem);
        $this->assertEquals('Personalized Gift Item (GIFT-PERS-01)', $cartItem->item_name);
        $this->assertEquals(25.50, $cartItem->item_price);

        $attrs = json_decode($cartItem->item_attributes, true);
        $this->assertNotEmpty($attrs['customizations']);
        
        $personalizationNode = collect($attrs['customizations'])->firstWhere('field_id', 'personalization');
        $this->assertNotNull($personalizationNode);
        $this->assertEquals('Gift Wrapping / Personalization', $personalizationNode['label']);
        $this->assertEquals('Engrave Name: Antigravity', $personalizationNode['value']);
        $this->assertEquals(5.50, $personalizationNode['price_modifier']);
    }

    public function test_admin_can_configure_variant_personalization(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3, // Admin
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'title' => 'Configure Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'configure-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'CONF-01',
            'public_price' => 15.00,
            'wholesale_price' => 12.00,
            'personalization_active' => false,
            'personalization_fee' => 0.00,
        ]);

        $this->actingAs($admin);

        // Edit variant to activate personalization and set fee via component
        Livewire::test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->call('startEditVariant', $variant->id)
            ->set('personalization_active', true)
            ->set('personalization_fee', 3.75)
            ->call('updateVariant');

        $variant->refresh();
        $this->assertTrue($variant->personalization_active);
        $this->assertEquals(3.75, $variant->personalization_fee);
    }
}
