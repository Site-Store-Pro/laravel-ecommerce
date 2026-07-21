<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class OrderReviewStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_review_renders_state_abbreviation(): void
    {
        // 1. Create a user with CA state
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'email_verified_at' => now(),
            'shipping_address1' => '123 Test St',
            'shipping_city' => 'San Diego',
            'shipping_state' => 'CA',
            'shopping_postalcode' => '92103',
            'shipping_country' => 'United States',
            'shipping_countrycode' => 'US',
        ]);

        $this->actingAs($user);

        // Seed default order processors & checkout options needed by OrderReview component
        DB::table('order_checkout_options')->insert([
            'primary_processor' => 0,
            'secondary_processor' => 1,
            'tertiary_processor' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('order_processors')->insert([
            ['processor_id' => 0, 'processor_name' => 'Test Gateway', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed a shippable product
        $productId = DB::table('products')->insertGetId([
            'title' => 'Notebook',
            'seo_slug' => 'notebook',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $variantId = DB::table('product_variants')->insertGetId([
            'product_id' => $productId,
            'sku' => 'NOTE-1',
            'public_price' => 10.00,
            'wholesale_price' => 8.00,
            'shipping' => 1, // Shippable
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Put in active shopping cart log
        DB::table('shopping_cart_log')->insert([
            'cart_log_session' => session()->getId(),
            'user_id' => $user->id,
            'item_name' => 'Notebook',
            'item_qty' => 1,
            'item_price' => 10.00,
            'item_discount_price' => 0.00,
            'item_attributes' => json_encode(['variant_id' => $variantId]),
            'item_shippable' => 1,
            'item_weight' => 0.5,
            'item_taxable' => 1,
            'item_downloadable' => 0,
            'order_id' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Test rendering order-review Livewire component
        $component = Livewire::test('order-review');

        // 3. Verify that the correct address format is displayed in the HTML output
        $html = $component->html();
        
        $htmlClean = preg_replace('/<!--(.*?)-->/s', '', $html);
        $this->assertStringContainsString('San Diego, CA 92103', $htmlClean);
    }
}
