<?php

namespace Tests\Feature;

use App\Models\ProductInventory;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class MultiWarehouseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed warehouse locations
        DB::table('warehouse_locations')->delete();
        DB::table('warehouse_locations')->insert([
            [
                'id' => 1,
                'name' => 'US Warehouse',
                'code' => 'US-WH',
                'country_code' => 'US',
                'state_code' => 'TX',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'CA Warehouse',
                'code' => 'CA-WH',
                'country_code' => 'CA',
                'state_code' => 'ON',
                'is_active' => true,
            ],
        ]);
    }

    public function test_getStockForFulfillment_resolves_location_specific_stock(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'test-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TEST-SKU-MW',
            'public_price' => 10.00,
            'wholesale_price' => 9.00,
        ]);

        // Insert inventory for US location (id 1)
        ProductInventory::create([
            'variant_id' => $variant->id,
            'location_id' => 1,
            'quantity_available' => 10,
            'warehouse_stock_level' => 5,
            'use_warehouse_stock' => true, // Total = 15
            'reserved_stock' => 0,
        ]);

        // Insert inventory for CA location (id 2)
        ProductInventory::create([
            'variant_id' => $variant->id,
            'location_id' => 2,
            'quantity_available' => 20,
            'warehouse_stock_level' => 0,
            'use_warehouse_stock' => false, // Total = 20
            'reserved_stock' => 2, // Available = 18
        ]);

        // 1. Resolve for US, TX -> Should return US Warehouse inventory (15)
        $this->assertEquals(15, $variant->getStockForFulfillment('US', 'TX'));

        // 2. Resolve for CA, ON -> Should return CA Warehouse inventory (18)
        $this->assertEquals(18, $variant->getStockForFulfillment('CA', 'ON'));

        // 3. Fallback (No country/state code) -> Should sum all locations (15 + 18 = 33)
        $this->assertEquals(33, $variant->getStockForFulfillment());
    }

    public function test_admin_can_manage_warehouses(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3, // Admin
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin);

        // 1. Add Warehouse Location
        Livewire::test(\App\Livewire\AdminShippingSettings::class)
            ->set('activeTab', 'warehouses')
            ->call('openWarehouseModal')
            ->set('warehouseName', 'New WH')
            ->set('warehouseCode', 'NEW-WH')
            ->set('warehouseCountryCode', 'US')
            ->set('warehouseStateCode', 'NY')
            ->call('saveWarehouse');

        $this->assertDatabaseHas('warehouse_locations', [
            'name' => 'New WH',
            'code' => 'NEW-WH',
            'state_code' => 'NY',
        ]);

        $newWh = DB::table('warehouse_locations')->where('code', 'NEW-WH')->first();

        // 2. Edit Warehouse Location
        Livewire::test(\App\Livewire\AdminShippingSettings::class)
            ->set('activeTab', 'warehouses')
            ->call('openWarehouseModal', $newWh->id)
            ->set('warehouseName', 'Updated WH')
            ->call('saveWarehouse');

        $this->assertDatabaseHas('warehouse_locations', [
            'id' => $newWh->id,
            'name' => 'Updated WH',
        ]);

        // 3. Delete Warehouse Location
        Livewire::test(\App\Livewire\AdminShippingSettings::class)
            ->set('activeTab', 'warehouses')
            ->call('deleteWarehouse', $newWh->id);

        $this->assertDatabaseMissing('warehouse_locations', [
            'id' => $newWh->id,
        ]);
    }
}
