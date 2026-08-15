<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutInventoryValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('user_roles')->insertOrIgnore(['id' => 1, 'name' => 'Customer']);
        DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Admin']);
    }

    public function test_checkout_removes_out_of_stock_item_and_redirects_to_cart(): void
    {
        $user = User::factory()->create();

        $product = Product::create([
            'title' => 'Validation Product',
            'seo_slug' => 'validation-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'OOS-SKU-100',
            'public_price' => 15.00,
            'wholesale_price' => 10.00,
        ]);

        ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 0, // Out of stock!
            'warehouse_stock_level' => 0,
            'use_warehouse_stock' => true,
            'reserved_stock' => 0,
            'location_id' => 1,
        ]);

        ShoppingCartLog::create([
            'cart_log_session' => 'session-123',
            'item_name' => 'Validation Product (OOS-SKU-100)',
            'item_qty' => 1,
            'item_price' => 15.00,
            'item_discount_price' => 15.00,
            'item_shippable' => 1,
            'item_weight' => 0.5,
            'variant_id' => $variant->id,
            'order_id' => 0,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::withCookie('cart_session_id', 'session-123')
            ->test(\App\Livewire\Checkout::class)
            ->assertRedirect(route('shop.cart'));

        $this->assertDatabaseMissing('shopping_cart_log', [
            'variant_id' => $variant->id,
            'order_id' => 0,
        ]);
    }

    public function test_order_review_removes_out_of_stock_item_and_redirects_to_cart(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'shipping_address1' => '123 Main St',
            'shipping_city' => 'Austin',
            'shipping_state' => 'TX',
            'shipping_countrycode' => 'US',
            'shopping_postalcode' => '78701',
        ]);

        $product = Product::create([
            'title' => 'Validation Product 2',
            'seo_slug' => 'validation-product-2',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'OOS-SKU-200',
            'public_price' => 20.00,
            'wholesale_price' => 12.00,
        ]);

        ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 0,
            'warehouse_stock_level' => 0,
            'use_warehouse_stock' => true,
            'reserved_stock' => 0,
            'location_id' => 1,
        ]);

        ShoppingCartLog::create([
            'cart_log_session' => 'session-456',
            'item_name' => 'Validation Product 2 (OOS-SKU-200)',
            'item_qty' => 1,
            'item_price' => 20.00,
            'item_discount_price' => 20.00,
            'item_shippable' => 1,
            'item_weight' => 0.5,
            'variant_id' => $variant->id,
            'order_id' => 0,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::withCookie('cart_session_id', 'session-456')
            ->test(\App\Livewire\OrderReview::class)
            ->assertRedirect(route('shop.cart'));

        $this->assertDatabaseMissing('shopping_cart_log', [
            'variant_id' => $variant->id,
            'order_id' => 0,
        ]);
    }
}
