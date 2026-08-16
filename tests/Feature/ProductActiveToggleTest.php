<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\LiveSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ProductActiveToggleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("INSERT IGNORE INTO `user_roles` (`id`, `name`, `description`) VALUES 
            (1, 'User', 'Customer'),
            (2, 'Wholesale', 'Wholesale'),
            (3, 'Admin', 'Admin')");
    }

    public function test_inactive_product_returns_404_on_direct_url_and_is_hidden_from_catalog_and_search(): void
    {
        // 1. Create a product with active = true
        $product = Product::create([
            'active'            => true,
            'title'             => 'Test Active Product ' . rand(1000, 9999),
            'short_description' => 'Active product short description',
            'long_description'  => 'Active product long description',
            'seo_slug'          => 'test-active-product-' . rand(1000, 9999),
        ]);

        $variant = ProductVariant::create([
            'product_id'      => $product->id,
            'sku'             => 'TEST-ACT-' . rand(1000, 9999),
            'public_price'    => 29.99,
            'wholesale_price' => 20.00,
        ]);

        // 2. Direct URL should return 200 OK when active
        $response = $this->get(route('shop.product', $product->seo_slug));
        $response->assertStatus(200);

        // 3. Catalog should include active product
        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->assertSee($product->title);

        // 4. Live search should find active product
        $searchService = app(LiveSearchService::class);
        $results = $searchService->search($product->title, 1, 1);
        $this->assertTrue(collect($results)->contains(fn($r) => $r['id'] === $product->id));

        // 5. Toggle product to inactive (active = false)
        $product->update(['active' => false]);

        // 6. Direct URL must return 404 Not Found
        $response404 = $this->get(route('shop.product', $product->seo_slug));
        $response404->assertStatus(404);

        // 7. Catalog must NOT include inactive product
        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->assertDontSee($product->title);

        // 8. Live search must NOT return inactive product
        $resultsInactive = $searchService->search($product->title, 1, 1);
        $this->assertFalse(collect($resultsInactive)->contains(fn($r) => $r['id'] === $product->id));
    }

    public function test_admin_can_toggle_product_active_status(): void
    {
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin_test@support.local',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'active'            => true,
            'title'             => 'Admin Toggle Product',
            'short_description' => 'Test toggle',
            'seo_slug'          => 'admin-toggle-product',
        ]);

        // Toggle via AdminProductEdit
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertSet('active', true)
            ->call('toggleActive')
            ->assertSet('active', false);

        $this->assertFalse((bool) $product->fresh()->active);

        // Toggle via AdminProducts
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProducts::class)
            ->call('toggleProductActive', $product->id);

        $this->assertTrue((bool) $product->fresh()->active);
    }

    public function test_inactive_product_in_cart_redirects_to_cart_and_shows_out_of_stock_error(): void
    {
        // 1. Create product and variant
        $product = Product::create([
            'active'            => true,
            'title'             => 'Solo Cart Item Product',
            'short_description' => 'Test',
            'seo_slug'          => 'solo-cart-item-product',
        ]);

        $variant = ProductVariant::create([
            'product_id'      => $product->id,
            'sku'             => 'SOLO-SKU-100',
            'public_price'    => 49.99,
            'wholesale_price' => 30.00,
        ]);

        $user = User::create([
            'name'              => 'Customer User',
            'email'             => 'customer_checkout@example.com',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::User,
            'email_verified_at' => now(),
            'shipping_address1' => '123 Test St',
            'shipping_city'     => 'Los Angeles',
            'shipping_countrycode' => 'US',
            'shipping_state'    => 'CA',
            'shopping_postalcode' => '90001',
        ]);

        // Add item to cart for this user
        $cartItem = \App\Models\ShoppingCartLog::create([
            'cart_log_session'    => (string) \Illuminate\Support\Str::uuid(),
            'user_id'             => $user->id,
            'item_name'           => 'Solo Cart Item Product (SOLO-SKU-100)',
            'item_qty'            => 1,
            'item_price'          => 49.99,
            'item_discount_price' => 0.00,
            'item_shippable'      => 1,
            'item_weight'         => 1.0,
            'variant_id'          => $variant->id,
            'order_id'            => 0,
        ]);

        // 2. Turn product off (active = false)
        $product->update(['active' => false]);

        // 3. Customer visits Checkout
        $response = $this->actingAs($user)->get(route('shop.checkout'));

        // Customer must be redirected to /cart
        $response->assertRedirect(route('shop.cart'));

        // Session flash error must be present with out-of-stock message
        $response->assertSessionHas('error');
        $errorMsg = session('error');
        $this->assertStringContainsString('out of stock', strtolower($errorMsg));

        // Cart item must be automatically removed from database
        $this->assertDatabaseMissing('shopping_cart_log', [
            'id' => $cartItem->id,
        ]);

        // 4. Visiting Order Review directly also redirects to /cart
        $responseReview = $this->actingAs($user)->get(route('shop.checkout-review'));
        $responseReview->assertRedirect(route('shop.cart'));
    }
}
