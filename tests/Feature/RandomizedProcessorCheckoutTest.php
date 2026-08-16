<?php

namespace Tests\Feature;

use App\Models\OrderCheckoutOption;
use App\Models\OrderProcessor;
use App\Models\User;
use App\Services\Payments\PaymentProcessorManager;
use App\Services\Payments\Processors\PaddleProcessor;
use App\Services\Payments\Processors\PayPalProcessor;
use App\Services\Payments\Processors\StripeProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class RandomizedProcessorCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('user_roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'Customer'],
            ['id' => 2, 'name' => 'Wholesale'],
            ['id' => 3, 'name' => 'Admin'],
        ]);

        OrderCheckoutOption::query()->delete();
        OrderProcessor::query()->delete();

        OrderCheckoutOption::create([
            'primary_processor'   => 1, // Stripe
            'secondary_processor' => 2, // Paddle
            'tertiary_processor'  => 3, // PayPal
            'randomize_processor' => 1,
        ]);

        OrderProcessor::create(['processor_id' => 1, 'processor_name' => 'Stripe', 'production' => 0]);
        OrderProcessor::create(['processor_id' => 2, 'processor_name' => 'Paddle', 'production' => 0]);
        OrderProcessor::create(['processor_id' => 3, 'processor_name' => 'PayPal', 'production' => 0]);
    }

    public function test_payment_processor_manager_caches_active_processor_id_and_resolves_matching_driver(): void
    {
        $manager = new PaymentProcessorManager();

        $activeId = $manager->activeProcessorId();
        $this->assertContains($activeId, [1, 2, 3]);

        // Second call on the same manager instance must return the cached ID
        $this->assertEquals($activeId, $manager->activeProcessorId());

        // resolveActive() must return the matching driver for $activeId, not primary
        $driver = $manager->resolveActive();

        if ($activeId === 1) {
            $this->assertInstanceOf(StripeProcessor::class, $driver);
            $this->assertEquals('stripe', $manager->activeProcessorType());
        } elseif ($activeId === 2) {
            $this->assertInstanceOf(PaddleProcessor::class, $driver);
            $this->assertEquals('paddle', $manager->activeProcessorType());
        } elseif ($activeId === 3) {
            $this->assertInstanceOf(PayPalProcessor::class, $driver);
            $this->assertEquals('paypal', $manager->activeProcessorType());
        }
    }

    public function test_order_review_persists_randomized_processor_across_livewire_lifecycle(): void
    {
        $user = User::create([
            'name'                 => 'Jane Doe',
            'email'                => 'jane@example.com',
            'password'             => bcrypt('password'),
            'role_id'              => 1,
            'email_verified_at'    => now(),
            'shipping_address1'    => '456 Main St',
            'shipping_city'        => 'Austin',
            'shipping_state'       => 'TX',
            'shopping_postalcode'  => '78701',
            'shipping_country'     => 'United States',
            'shipping_countrycode' => 'US',
        ]);

        $this->actingAs($user);

        // Seed a product in shopping cart log
        $productId = DB::table('products')->insertGetId([
            'title'      => 'Digital Item',
            'seo_slug'   => 'digital-item',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $variantId = DB::table('product_variants')->insertGetId([
            'product_id'      => $productId,
            'sku'             => 'DIGI-1',
            'public_price'    => 15.00,
            'wholesale_price' => 12.00,
            'shipping'        => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        DB::table('shopping_cart_log')->insert([
            'cart_log_session'    => session()->getId(),
            'user_id'             => $user->id,
            'item_name'           => 'Digital Item',
            'item_qty'            => 1,
            'item_price'          => 15.00,
            'item_discount_price' => 0.00,
            'item_attributes'     => json_encode(['variant_id' => $variantId]),
            'item_shippable'      => 0,
            'item_weight'         => 0.0,
            'item_taxable'        => 0,
            'item_downloadable'   => 1,
            'order_id'            => 0,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $testComp = Livewire::test('order-review');

        $processorId = $testComp->get('activeProcessorId');
        $this->assertContains($processorId, [1, 2, 3]);

        // Re-render component and check activeProcessorId hasn't changed
        $testComp->call('$refresh');
        $this->assertEquals($processorId, $testComp->get('activeProcessorId'));
    }

    public function test_admin_can_toggle_stripe_address_requirement(): void
    {
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id'  => 3, // Admin role
        ]);

        $this->actingAs($admin);

        Livewire::test('admin-checkout-processors')
            ->set('stripe_address_required', true)
            ->assertSet('stripe_address_required', true);

        $this->assertEquals(1, OrderCheckoutOption::first()->stripe_address_required);

        Livewire::test('admin-checkout-processors')
            ->set('stripe_address_required', false)
            ->assertSet('stripe_address_required', false);

        $this->assertEquals(0, OrderCheckoutOption::first()->stripe_address_required);
    }
}

