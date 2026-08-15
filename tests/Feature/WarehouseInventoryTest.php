<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductInventoryWarehouse;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\WarehouseLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class WarehouseInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_stock_calculation_with_child_warehouses(): void
    {
        $product = Product::create([
            'title' => 'Test Warehouse Product',
            'seo_slug' => 'test-warehouse-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'WH-TEST-001',
            'public_price' => 25.00,
            'wholesale_price' => 15.00,
        ]);

        $loc1 = WarehouseLocation::create([
            'name' => 'East Coast Hub',
            'code' => 'ECH-01',
            'country_code' => 'US',
            'is_active' => true,
        ]);

        $loc2 = WarehouseLocation::create([
            'name' => 'West Coast Hub',
            'code' => 'WCH-02',
            'country_code' => 'US',
            'is_active' => true,
        ]);

        $inventory = ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 10,
            'warehouse_stock_level' => 5,
            'use_warehouse_stock' => false,
            'reserved_stock' => 2,
            'location_id' => 1,
        ]);

        ProductInventoryWarehouse::create([
            'product_inventory_id' => $inventory->id,
            'warehouse_location_id' => $loc1->id,
            'stock_level' => 15,
        ]);

        ProductInventoryWarehouse::create([
            'product_inventory_id' => $inventory->id,
            'warehouse_location_id' => $loc2->id,
            'stock_level' => 25,
        ]);

        // When use_warehouse_stock is FALSE: Available = 10 - 2 = 8
        $this->assertEquals(8, $inventory->fresh()->available_stock);

        // When use_warehouse_stock is TRUE: Available = 10 + 5 + (15 + 25) - 2 = 53
        $inventory->update(['use_warehouse_stock' => true]);
        $this->assertEquals(53, $inventory->fresh()->available_stock);
    }

    public function test_admin_shipping_settings_tab_query_string(): void
    {
        DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => 3]);

        $this->actingAs($admin);

        Livewire::withQueryParams(['tab' => 'warehouses'])
            ->test(\App\Livewire\AdminShippingSettings::class)
            ->assertSet('activeTab', 'warehouses');

        Livewire::withQueryParams(['tab' => 'locations'])
            ->test(\App\Livewire\AdminShippingSettings::class)
            ->assertSet('activeTab', 'warehouses');
    }

    public function test_add_and_remove_warehouse_stock_line_action(): void
    {
        DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => 3]);

        $loc1 = WarehouseLocation::create([
            'name' => 'Dallas Hub',
            'code' => 'DAL-01',
            'country_code' => 'US',
            'is_active' => true,
        ]);

        $product = Product::create([
            'title' => 'Sample Widget',
            'seo_slug' => 'sample-widget',
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->call('addWarehouseStockLine')
            ->assertSet('variantWarehouseStock.0.warehouse_location_id', $loc1->id)
            ->assertSet('variantWarehouseStock.0.stock_level', 0)
            ->call('removeWarehouseStockLine', 0)
            ->assertSet('variantWarehouseStock', []);
    }

    public function test_product_details_warehouse_stock_reflection_on_variant_change(): void
    {
        $product = Product::create([
            'title' => 'Multi-Variant Warehouse Product',
            'seo_slug' => 'multi-variant-warehouse-product',
        ]);

        $v1 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'V1-SHELF',
            'public_price' => 10.00,
            'wholesale_price' => 5.00,
        ]);

        ProductInventory::create([
            'variant_id' => $v1->id,
            'quantity_available' => 0, // Shelf stock 0
            'warehouse_stock_level' => 50, // Warehouse stock 50
            'use_warehouse_stock' => true, // Enabled!
            'reserved_stock' => 0,
            'location_id' => 1,
        ]);

        $v2 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'V2-OOS',
            'public_price' => 12.00,
            'wholesale_price' => 6.00,
        ]);

        ProductInventory::create([
            'variant_id' => $v2->id,
            'quantity_available' => 0,
            'warehouse_stock_level' => 0,
            'use_warehouse_stock' => true,
            'reserved_stock' => 0,
            'location_id' => 1,
        ]);

        Livewire::test(\App\Livewire\ProductDetails::class, ['seo_link' => $product->seo_slug])
            ->assertSet('selectedVariantId', $v1->id)
            ->assertSee('50 in stock')
            ->set('selectedVariantId', $v2->id)
            ->assertSet('selectedVariantId', $v2->id)
            ->assertDontSee('50 in stock');
    }

    public function test_admin_inventory_displays_location_info_and_exports_csv(): void
    {
        DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => 3]);

        $locMain = WarehouseLocation::create([
            'name' => 'Dallas Main Facility',
            'code' => 'DAL-MAIN',
            'country_code' => 'US',
            'is_active' => true,
        ]);

        $locChild = WarehouseLocation::create([
            'name' => 'Atlanta Secondary Hub',
            'code' => 'ATL-HUB',
            'country_code' => 'US',
            'is_active' => true,
        ]);

        $product = Product::create([
            'title' => 'Location Tracking Widget',
            'seo_slug' => 'location-tracking-widget',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'LOC-WIDGET-01',
            'public_price' => 49.99,
            'wholesale_price' => 29.99,
        ]);

        $inventory = ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 20,
            'warehouse_stock_level' => 30,
            'use_warehouse_stock' => true,
            'reserved_stock' => 5,
            'location_id' => $locMain->id,
        ]);

        ProductInventoryWarehouse::create([
            'product_inventory_id' => $inventory->id,
            'warehouse_location_id' => $locChild->id,
            'stock_level' => 15,
        ]);

        $this->actingAs($admin);

        // Verify location information is displayed on /admin/ecommerce/inventory
        Livewire::test(\App\Livewire\AdminInventory::class)
            ->assertSee('Location Tracking Widget')
            ->assertSee('Dallas Main Facility')
            ->assertSee('Atlanta Secondary Hub:');

        // Verify CSV export streams streamed response with child records
        Livewire::test(\App\Livewire\AdminInventory::class)
            ->call('exportCsv')
            ->assertStatus(200);

        // Verify AdminReports multi-warehouse inventory export
        Livewire::test(\App\Livewire\AdminReports::class)
            ->call('exportMultiWarehouseInventory')
            ->assertStatus(200);
    }
}
