<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\WarehouseLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_webhook_update_with_child_warehouses(): void
    {
        config(['services.inventory_webhook.token' => 'secret_test_token']);

        $product = Product::create([
            'title' => 'Webhook Product',
            'seo_slug' => 'webhook-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'WEBHOOK-SKU-123',
            'public_price' => 20.00,
            'wholesale_price' => 12.00,
        ]);

        $loc1 = WarehouseLocation::create([
            'name' => 'East Coast Warehouse',
            'code' => 'EC-01',
            'country_code' => 'US',
            'is_active' => true,
        ]);

        $loc2 = WarehouseLocation::create([
            'name' => 'West Coast Warehouse',
            'code' => 'WC-02',
            'country_code' => 'US',
            'is_active' => true,
        ]);

        $response = $this->withHeaders([
            'X-Inventory-Webhook-Token' => 'secret_test_token',
        ])->postJson('/webhooks/inventory-update', [
            'sku' => 'WEBHOOK-SKU-123',
            'stock_level' => 50,
            'warehouse_level' => 20,
            'use_warehouse_stock' => true,
            'location_id' => $loc1->id,
            'warehouse_stocks' => [
                ['warehouse_location_id' => $loc1->id, 'stock_level' => 30],
                ['warehouse_location_id' => $loc2->id, 'stock_level' => 40],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'sku' => 'WEBHOOK-SKU-123',
                'quantity_available' => 50,
                'warehouse_stock_level' => 20,
                'use_warehouse_stock' => true,
                'calculated_total' => 140, // 50 (stock) + 20 (wh) + (30 + 40) (child) - 0 (reserved)
            ],
        ]);
    }
}
