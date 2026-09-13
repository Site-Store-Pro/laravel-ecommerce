<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AdminProductsDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("INSERT IGNORE INTO `user_roles` (`id`, `name`, `description`) VALUES 
            (1, 'User', 'Customer'),
            (2, 'Wholesale', 'Wholesale'),
            (3, 'Admin', 'Admin')");

        $this->admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin_del_test@support.local',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::Admin,
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_can_open_and_cancel_delete_modal(): void
    {
        $product = Product::create([
            'active'            => true,
            'title'             => 'Product To Keep',
            'short_description' => 'Will not be deleted',
            'seo_slug'          => 'product-to-keep',
        ]);

        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\AdminProducts::class)
            ->assertSet('showDeleteModal', false)
            ->call('confirmDeleteProduct', $product->id)
            ->assertSet('showDeleteModal', true)
            ->assertSet('deleteProductId', $product->id)
            ->assertSet('deleteProductTitle', 'Product To Keep')
            ->call('cancelDeleteProduct')
            ->assertSet('showDeleteModal', false)
            ->assertSet('deleteProductId', null);

        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_deleting_product_only_deletes_the_targeted_product(): void
    {
        // Create 3 distinct products
        $productA = Product::create([
            'active'            => true,
            'title'             => 'Product Alpha',
            'short_description' => 'Alpha description',
            'seo_slug'          => 'product-alpha',
        ]);
        $variantA = ProductVariant::create([
            'product_id'      => $productA->id,
            'sku'             => 'SKU-ALPHA-1',
            'public_price'    => 19.99,
            'wholesale_price' => 15.00,
        ]);

        $productB = Product::create([
            'active'            => true,
            'title'             => 'Product Beta',
            'short_description' => 'Beta description',
            'seo_slug'          => 'product-beta',
        ]);
        $variantB = ProductVariant::create([
            'product_id'      => $productB->id,
            'sku'             => 'SKU-BETA-1',
            'public_price'    => 29.99,
            'wholesale_price' => 25.00,
        ]);

        $productC = Product::create([
            'active'            => true,
            'title'             => 'Product Gamma',
            'short_description' => 'Gamma description',
            'seo_slug'          => 'product-gamma',
        ]);
        $variantC = ProductVariant::create([
            'product_id'      => $productC->id,
            'sku'             => 'SKU-GAMMA-1',
            'public_price'    => 39.99,
            'wholesale_price' => 35.00,
        ]);

        // Expand Product A & B in the UI accordion
        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\AdminProducts::class)
            ->call('toggleProductExpand', $productA->id)
            ->call('toggleProductExpand', $productB->id)
            ->assertSet('expandedProducts', [$productA->id, $productB->id]);

        // Delete specifically Product B through the confirm flow
        $test->call('confirmDeleteProduct', $productB->id)
            ->assertSet('deleteProductId', $productB->id)
            ->call('deleteProduct')
            ->assertSet('showDeleteModal', false)
            ->assertSet('deleteProductId', null);

        // Verify in database: ONLY Product B is deleted
        $this->assertDatabaseMissing('products', ['id' => $productB->id]);
        $this->assertDatabaseMissing('product_variants', ['id' => $variantB->id]);

        // Product A and Product C MUST remain intact
        $this->assertDatabaseHas('products', ['id' => $productA->id]);
        $this->assertDatabaseHas('product_variants', ['id' => $variantA->id]);
        $this->assertDatabaseHas('products', ['id' => $productC->id]);
        $this->assertDatabaseHas('product_variants', ['id' => $variantC->id]);

        // Expanded products list should now only contain Product A
        $test->assertSet('expandedProducts', [$productA->id]);
    }

    public function test_delete_with_explicit_parameter_safely_deletes_single_item(): void
    {
        $product1 = Product::create([
            'active'            => true,
            'title'             => 'Item One',
            'seo_slug'          => 'item-one',
        ]);
        $product2 = Product::create([
            'active'            => true,
            'title'             => 'Item Two',
            'seo_slug'          => 'item-two',
        ]);

        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\AdminProducts::class)
            ->call('deleteProduct', $product1->id);

        $this->assertDatabaseMissing('products', ['id' => $product1->id]);
        $this->assertDatabaseHas('products', ['id' => $product2->id]);
    }
}
