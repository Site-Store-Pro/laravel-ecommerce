<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use App\Models\User;
use App\Services\Payments\Processors\PayPalProcessor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class PayPalSubscriptionIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement("INSERT IGNORE INTO `user_roles` (`id`, `name`, `description`) VALUES 
            (1, 'User', 'Customer'),
            (2, 'Wholesale', 'Wholesale'),
            (3, 'Admin', 'Admin')");

        // Seed default order processors & checkout options
        DB::table('order_processors')->upsert([
            ['processor_id' => 0, 'processor_name' => 'Test Gateway', 'production' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['processor_id' => 3, 'processor_name' => 'PayPal Payments', 'production' => 0, 'created_at' => now(), 'updated_at' => now()],
        ], ['processor_id']);

        DB::table('order_checkout_options')->updateOrInsert(
            ['id' => 1],
            [
                'primary_processor'   => 3, // PayPal active
                'secondary_processor' => 0,
                'tertiary_processor'  => 0,
                'randomize_processor' => 0,
                'updated_at'          => now(),
            ]
        );
    }

    public function test_variant_is_subscription_with_paypal_plan(): void
    {
        $product = Product::create([
            'title'             => 'Membership Software ' . rand(1000, 9999),
            'meta_title'        => 'Membership Software',
            'short_description' => 'Software subscription',
            'seo_slug'          => 'membership-software-' . rand(1000, 9999),
            'sku'               => 'MEMB-01',
        ]);

        $variant = ProductVariant::create([
            'product_id'             => $product->id,
            'sku'                    => 'MEMB-01-M',
            'public_price'           => 29.99,
            'wholesale_price'        => 0.00,
            'paypal_sandbox_plan_id' => 'P-SANDBOX-123456',
            'paypal_live_plan_id'    => 'P-LIVE-123456',
        ]);

        $this->assertTrue($variant->isSubscriptionVariant());
        $this->assertEquals(1, $variant->subscription);
    }

    public function test_paypal_subscription_prepare_payment_and_place_order(): void
    {
        Http::fake([
            'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
                'access_token' => 'mock-paypal-token',
                'token_type'   => 'Bearer',
                'expires_in'   => 3600,
            ], 200),
            'https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-SUB-TEST-999' => Http::response([
                'id'       => 'I-SUB-TEST-999',
                'status'   => 'ACTIVE',
                'plan_id'  => 'P-SANDBOX-123456',
                'subscriber' => [
                    'email_address' => 'sub-buyer@example.com',
                ],
            ], 200),
        ]);

        $user = User::create([
            'name'     => 'Jane Subscriber',
            'email'    => 'sub-buyer-' . rand(1000, 9999) . '@example.com',
            'password' => bcrypt('password'),
            'role_id'  => 1,
        ]);

        $this->actingAs($user);

        $product = Product::create([
            'title'             => 'Monthly Pro SaaS ' . rand(1000, 9999),
            'meta_title'        => 'Monthly Pro SaaS',
            'short_description' => 'SaaS subscription product',
            'seo_slug'          => 'monthly-pro-saas-' . rand(1000, 9999),
            'sku'               => 'SAAS-PRO',
        ]);

        $variant = ProductVariant::create([
            'product_id'             => $product->id,
            'sku'                    => 'SAAS-PRO-M',
            'public_price'           => 49.00,
            'wholesale_price'        => 0.00,
            'paypal_sandbox_plan_id' => 'P-SANDBOX-123456',
            'paypal_live_plan_id'    => 'P-LIVE-123456',
        ]);

        ShoppingCartLog::create([
            'cart_log_session'    => session()->getId(),
            'user_id'             => $user->id,
            'variant_id'          => $variant->id,
            'item_name'           => 'Monthly Pro SaaS',
            'item_qty'            => 1,
            'item_price'          => 49.00,
            'item_discount_price' => 0.00,
            'item_shippable'      => 0,
            'item_downloadable'   => 1,
            'item_weight'         => 0.0,
            'order_id'            => 0,
        ]);

        config(['services.paypal.sandbox_client_id' => 'test-client-id']);
        config(['services.paypal.sandbox_client_secret' => 'test-secret']);

        // 1. preparePayment must return subscription details
        Livewire::test('order-review')
            ->call('preparePayment')
            ->assertReturned([
                'processor'      => 'paypal',
                'isSubscription' => true,
                'planId'         => 'P-SANDBOX-123456',
                'clientId'       => 'test-client-id',
            ])
            // 2. placeOrder with the PayPal subscription ID
            ->call('placeOrder', 'I-SUB-TEST-999')
            ->assertHasNoErrors();

        // 3. Verify order & payment records
        $payment = DB::table('order_payments')->where('payment_method', 'PayPal Payments')->first();
        $this->assertNotNull($payment);
        $this->assertEquals('I-SUB-TEST-999', $payment->authorization_code);
        $this->assertStringContainsString('Subscription: I-SUB-TEST-999', $payment->processor_response);
    }

    public function test_paypal_webhook_handles_subscription_activated(): void
    {
        $order = Order::create([
            'order_invoice_no'   => 'INV-SUB-101' . rand(100, 999),
            'order_external_id'  => (string) \Illuminate\Support\Str::uuid(),
            'order_user_id'      => 1,
            'order_status'       => 0, // Incomplete/Pending
            'order_date'         => now(),
            'order_subtotal'     => 49.00,
            'order_taxes'        => 0.00,
            'order_discounts'    => 0.00,
            'order_shipping'     => 0.00,
            'order_download'     => 1,
            'order_total'        => 49.00,
            'order_handling'     => 0.00,
        ]);

        OrderPayment::create([
            'order_id'           => $order->id,
            'payment_date'       => now(),
            'payment_amount'     => 49.00,
            'payment_method'     => 'PayPal Payments',
            'payment_status'     => 1,
            'authorization_code' => 'I-SUB-ACTIVE-111',
            'processor_response' => 'Subscription: I-SUB-ACTIVE-111',
        ]);

        $response = $this->postJson(route('webhooks.paypal'), [
            'id'         => 'WH-EVENT-001',
            'event_type' => 'BILLING.SUBSCRIPTION.ACTIVATED',
            'resource'   => [
                'id'     => 'I-SUB-ACTIVE-111',
                'status' => 'ACTIVE',
            ],
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $order->fresh()->order_status);
    }

    public function test_paypal_webhook_handles_sale_completed_recurring_renewal(): void
    {
        $order = Order::create([
            'order_invoice_no'   => 'INV-SUB-102' . rand(100, 999),
            'order_external_id'  => (string) \Illuminate\Support\Str::uuid(),
            'order_user_id'      => 1,
            'order_status'       => 1,
            'order_date'         => now(),
            'order_subtotal'     => 49.00,
            'order_taxes'        => 0.00,
            'order_discounts'    => 0.00,
            'order_shipping'     => 0.00,
            'order_download'     => 1,
            'order_total'        => 49.00,
            'order_handling'     => 0.00,
        ]);

        OrderPayment::create([
            'order_id'           => $order->id,
            'payment_date'       => now()->subMonth(),
            'payment_amount'     => 49.00,
            'payment_method'     => 'PayPal Payments',
            'payment_status'     => 1,
            'authorization_code' => 'I-SUB-RENEW-222',
            'processor_response' => 'Subscription: I-SUB-RENEW-222',
        ]);

        $response = $this->postJson(route('webhooks.paypal'), [
            'id'         => 'WH-EVENT-002',
            'event_type' => 'PAYMENT.SALE.COMPLETED',
            'resource'   => [
                'id'                   => 'SALE-TRANSACTION-999',
                'billing_agreement_id' => 'I-SUB-RENEW-222',
                'amount'               => [
                    'total'    => '49.00',
                    'currency' => 'USD',
                ],
                'state'                => 'completed',
            ],
        ]);

        $response->assertStatus(200);

        // Check that a new renewal payment was created for the parent order
        $payments = OrderPayment::where('order_id', $order->id)->get();
        $this->assertCount(2, $payments);

        $renewalPayment = OrderPayment::where('authorization_code', 'SALE-TRANSACTION-999')->first();
        $this->assertNotNull($renewalPayment);
        $this->assertEquals(49.00, (float)$renewalPayment->payment_amount);
        $this->assertStringContainsString('PayPal Subscription Renewal', $renewalPayment->processor_response);
    }

    public function test_admin_can_generate_paypal_plans_on_the_fly(): void
    {
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin-' . rand(1000, 9999) . '@example.com',
            'password' => bcrypt('password'),
            'role_id'  => 3,
        ]);

        $this->actingAs($admin);

        $product = Product::create([
            'title'             => 'Cloud Hosting Plan',
            'meta_title'        => 'Cloud Hosting Plan',
            'short_description' => 'Scalable cloud hosting',
            'seo_slug'          => 'cloud-hosting-' . rand(1000, 9999),
            'sku'               => 'CLOUD-01',
        ]);

        $variant = ProductVariant::create([
            'product_id'      => $product->id,
            'sku'             => 'CLOUD-PRO',
            'public_price'    => 39.99,
            'wholesale_price' => 0.00,
        ]);

        config(['services.paypal.sandbox_client_id' => 'test-sandbox-id']);
        config(['services.paypal.sandbox_client_secret' => 'test-sandbox-secret']);

        Http::fake([
            'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
                'access_token' => 'mock-token',
                'token_type'   => 'Bearer',
                'expires_in'   => 3600,
            ], 200),
            'https://api-m.sandbox.paypal.com/v1/catalogs/products' => Http::response([
                'id'          => 'PROD-AUTO-999',
                'name'        => 'Cloud Hosting Plan - CLOUD-PRO',
                'description' => 'Scalable cloud hosting',
                'type'        => 'SERVICE',
            ], 201),
            'https://api-m.sandbox.paypal.com/v1/billing/plans' => Http::response([
                'id'          => 'P-AUTO-GENERATED-999',
                'product_id'  => 'PROD-AUTO-999',
                'name'        => 'Cloud Hosting Plan - CLOUD-PRO (Monthly)',
                'status'      => 'ACTIVE',
            ], 201),
        ]);

        Livewire::test('admin-product-edit', ['id' => $product->id])
            ->call('startEditVariant', $variant->id)
            ->set('public_price', 39.99)
            ->set('paypal_billing_interval', 'month')
            ->set('paypal_billing_frequency', 1)
            ->set('paypal_trial_enabled', 1)
            ->set('paypal_trial_days', 14)
            ->set('paypal_trial_price', 1.00)
            ->set('paypal_total_cycles', 0)
            ->call('generatePayPalPlan', 'sandbox')
            ->assertSet('paypal_sandbox_plan_id', 'P-AUTO-GENERATED-999')
            ->call('updateVariant')
            ->assertHasNoErrors();

        $freshVariant = $variant->fresh();
        $this->assertEquals('P-AUTO-GENERATED-999', $freshVariant->paypal_sandbox_plan_id);
        $this->assertEquals('month', $freshVariant->paypal_billing_interval);
        $this->assertEquals(1, $freshVariant->paypal_billing_frequency);
        $this->assertEquals(1, $freshVariant->paypal_trial_enabled);
        $this->assertEquals(14, $freshVariant->paypal_trial_days);
        $this->assertEquals(1.00, (float)$freshVariant->paypal_trial_price);
        $this->assertEquals(0, $freshVariant->paypal_total_cycles);
        $this->assertTrue($freshVariant->isSubscriptionVariant());
    }
}
