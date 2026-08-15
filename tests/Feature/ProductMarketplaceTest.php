<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_price_window_enforcement(): void
    {
        $product = Product::create([
            'title' => 'Test Product Window',
            'seo_slug' => 'test-product-window'
        ]);

        // Future sale price (should NOT be active yet)
        $variantFuture = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-FUTURE',
            'public_price' => 100.00,
            'wholesale_price' => 80.00,
            'on_sale' => 1,
            'sale_price' => 50.00,
            'sale_price_start_at' => now()->addDays(2),
            'sale_price_end_at' => now()->addDays(5),
            'item_cost' => 30.00,
            'item_map' => 90.00,
        ]);

        $this->assertFalse($variantFuture->isOnSaleActive());

        // Currently active sale window
        $variantActive = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-ACTIVE',
            'public_price' => 100.00,
            'wholesale_price' => 80.00,
            'on_sale' => 1,
            'sale_price' => 50.00,
            'sale_price_start_at' => now()->subDay(),
            'sale_price_end_at' => now()->addDay(),
        ]);

        $this->assertTrue($variantActive->isOnSaleActive());

        // Past sale window (expired)
        $variantExpired = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-EXPIRED',
            'public_price' => 100.00,
            'wholesale_price' => 80.00,
            'on_sale' => 1,
            'sale_price' => 50.00,
            'sale_price_start_at' => now()->subDays(5),
            'sale_price_end_at' => now()->subDays(2),
        ]);

        $this->assertFalse($variantExpired->isOnSaleActive());
    }

    public function test_amazon_and_ebay_exports(): void
    {
        DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Admin']);

        $admin = User::factory()->create(['role_id' => 3]);

        $product = Product::create([
            'title' => 'Marketplace Gadget',
            'short_description' => 'A great gadget',
            'long_description' => 'Full details of gadget',
            'seo_slug' => 'marketplace-gadget',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'AMZ-100',
            'public_price' => 49.99,
            'wholesale_price' => 30.00,
            'amazon_product' => true,
            'amazon_price' => 45.00,
            'amazon_asin' => 'B000TEST123',
            'amazon_bullet_points' => "Feature 1\nFeature 2",
            'amazon_item_type' => 'Electronics',
            'amazon_condition' => 'New',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'EBY-200',
            'public_price' => 59.99,
            'wholesale_price' => 35.00,
            'ebay_product' => true,
            'ebay_price' => 55.00,
            'ebay_category_id' => '12345',
            'ebay_listing_type' => 'Fixed Price',
            'ebay_options' => 'Color: Blue',
            'ebay_shipping_profile_id' => 'SHIP-FREE',
            'ebay_return_policy_id' => 'RET-30',
        ]);

        $this->actingAs($admin);

        \Livewire\Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminReports::class)
            ->call('exportAmazonProducts')
            ->assertFileDownloaded('amazon_products_export_' . now()->format('Y-m-d') . '.csv');

        \Livewire\Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminReports::class)
            ->call('exportEbayProducts')
            ->assertFileDownloaded('ebay_products_export_' . now()->format('Y-m-d') . '.csv');
    }
}
