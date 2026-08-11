<?php

namespace Tests\Feature;

use App\Models\ProductInventory;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class AdminInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_inventory(): void
    {
        $response = $this->get(route('admin.ecommerce.inventory'));
        $response->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_inventory(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1, // Regular user
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.ecommerce.inventory'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_and_save_inventory(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@support.local',
            'password' => bcrypt('password'),
            'role_id' => 3, // Admin
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'title' => 'Test Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'test-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TEST-SKU',
            'public_price' => 10.00,
            'wholesale_price' => 9.00,
        ]);

        $inventory = ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 10,
            'warehouse_stock_level' => 5,
            'use_warehouse_stock' => false,
            'reserved_stock' => 2,
            'location_id' => 1,
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\AdminInventory::class)
            ->set("stockInputs.{$inventory->id}", 15)
            ->set("warehouseInputs.{$inventory->id}", 8)
            ->set("useWarehouseInputs.{$inventory->id}", true)
            ->set("reservedInputs.{$inventory->id}", 3)
            ->call('saveStock', $inventory->id);

        $inventory->refresh();
        $this->assertEquals(15, $inventory->quantity_available);
        $this->assertEquals(8, $inventory->warehouse_stock_level);
        $this->assertTrue($inventory->use_warehouse_stock);
        $this->assertEquals(3, $inventory->reserved_stock);
    }

    public function test_admin_can_upload_csv_bulk_inventory(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@support.local',
            'password' => bcrypt('password'),
            'role_id' => 3, // Admin
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'title' => 'Test Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'test-product',
        ]);

        $variant1 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-AAA',
            'public_price' => 10.00,
            'wholesale_price' => 9.00,
        ]);

        $variant2 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-BBB',
            'public_price' => 10.00,
            'wholesale_price' => 9.00,
        ]);

        $inv1 = ProductInventory::create(['variant_id' => $variant1->id, 'quantity_available' => 1, 'warehouse_stock_level' => 1]);
        $inv2 = ProductInventory::create(['variant_id' => $variant2->id, 'quantity_available' => 1, 'warehouse_stock_level' => 1]);

        // Create a dummy CSV file content
        $csvContent = "SKU|stock_level|warehouse_level|locationid\n"
                    . "SKU-AAA|100|50|2\n"
                    . "SKU-BBB|200|75|4\n";

        $file = UploadedFile::fake()->createWithContent('inventory.csv', $csvContent);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\AdminInventory::class)
            ->set('csvFile', $file)
            ->call('uploadCsv');

        $inv1->refresh();
        $inv2->refresh();

        $this->assertEquals(100, $inv1->quantity_available);
        $this->assertEquals(50, $inv1->warehouse_stock_level);
        $this->assertEquals(2, $inv1->location_id);

        $this->assertEquals(200, $inv2->quantity_available);
        $this->assertEquals(75, $inv2->warehouse_stock_level);
        $this->assertEquals(4, $inv2->location_id);
    }
}
