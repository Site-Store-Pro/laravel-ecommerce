<?php

namespace Tests\Feature;

use App\Models\ProductInventory;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Set the secret token for testing
        config(['services.inventory_webhook.token' => 'test-secret-token']);
    }

    public function test_webhook_requires_token(): void
    {
        $response = $this->postJson('webhooks/inventory-update', [
            'sku' => 'TEST-SKU',
            'stock_level' => 10,
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Unauthorized: Invalid or missing webhook token.'
        ]);
    }

    public function test_webhook_requires_correct_token(): void
    {
        $response = $this->withHeaders([
            'X-Inventory-Webhook-Token' => 'wrong-token'
        ])->postJson('webhooks/inventory-update', [
            'sku' => 'TEST-SKU',
            'stock_level' => 10,
        ]);

        $response->assertStatus(401);
    }

    public function test_webhook_returns_404_if_sku_not_found(): void
    {
        $response = $this->withHeaders([
            'X-Inventory-Webhook-Token' => 'test-secret-token'
        ])->postJson('webhooks/inventory-update', [
            'sku' => 'NON-EXISTENT-SKU',
            'stock_level' => 10,
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 'error',
            'message' => "Variant with SKU 'NON-EXISTENT-SKU' not found."
        ]);
    }

    public function test_webhook_updates_inventory_successfully(): void
    {
        // 1. Create a dummy product and variant
        $product = Product::create([
            'title' => 'Test Product',
            'short_description' => 'Short desc',
            'long_description' => 'Long desc',
            'seo_slug' => 'test-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TEST-SKU-123',
            'public_price' => 9.99,
            'wholesale_price' => 8.99,
        ]);

        $inventory = ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 5,
            'warehouse_stock_level' => 0,
            'use_warehouse_stock' => false,
            'reserved_stock' => 1,
            'location_id' => 1,
        ]);

        // 2. Call webhook to update
        $response = $this->withHeaders([
            'X-Inventory-Webhook-Token' => 'test-secret-token'
        ])->postJson('webhooks/inventory-update', [
            'sku' => 'TEST-SKU-123',
            'stock_level' => 20,
            'warehouse_level' => 15,
            'use_warehouse_stock' => true,
            'location_id' => 3,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'sku' => 'TEST-SKU-123',
                'quantity_available' => 20,
                'warehouse_stock_level' => 15,
                'use_warehouse_stock' => true,
                'reserved_stock' => 1,
                'current_total' => 34, // 20 + 15 - 1 = 34
                'location_id' => 3,
            ]
        ]);

        // 3. Verify in database
        $inventory->refresh();
        $this->assertEquals(20, $inventory->quantity_available);
        $this->assertEquals(15, $inventory->warehouse_stock_level);
        $this->assertTrue($inventory->use_warehouse_stock);
        $this->assertEquals(3, $inventory->location_id);
    }
}
