<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ShoppingCartLog;
use App\Models\OrderCheckoutOption;
use App\Models\OrderProcessor;
use App\Services\Payments\PaymentProcessorManager;
use App\Services\Payments\Processors\PayPalProcessor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class PayPalIntegrationTest extends TestCase
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

    public function test_paypal_processor_resolves_correctly(): void
    {
        $manager = app(PaymentProcessorManager::class);
        $this->assertEquals(3, $manager->activeProcessorId());
        $this->assertEquals('paypal', $manager->activeProcessorType());

        $driver = $manager->resolveActive();
        $this->assertInstanceOf(PayPalProcessor::class, $driver);
        $this->assertTrue($driver->isSandbox());
        $this->assertEquals('PayPal Payments', $driver->getName());
    }

    public function test_paypal_order_creation_and_capture(): void
    {
        // Fake PayPal token and order creation/capture API responses
        Http::fake([
            'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
                'access_token' => 'mock-paypal-access-token',
                'token_type' => 'Bearer',
                'expires_in' => 3600
            ], 200),
            'https://api-m.sandbox.paypal.com/v2/checkout/orders' => Http::response([
                'id' => 'mock-paypal-order-id',
                'status' => 'CREATED',
            ], 201),
            'https://api-m.sandbox.paypal.com/v2/checkout/orders/mock-paypal-order-id/capture' => Http::response([
                'id' => 'mock-paypal-order-id',
                'status' => 'COMPLETED',
                'purchase_units' => [
                    [
                        'payments' => [
                            'captures' => [
                                [
                                    'id' => 'mock-capture-id',
                                    'status' => 'COMPLETED'
                                ]
                            ]
                        ]
                    ]
                ]
            ], 201),
        ]);

        $user = User::create([
            'name' => 'John PayPal',
            'email' => 'paypal-buyer@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);

        $this->actingAs($user);

        // Put an item in cart
        ShoppingCartLog::create([
            'cart_log_session' => session()->getId(),
            'user_id' => $user->id,
            'item_name' => 'Standard Item',
            'item_qty' => 1,
            'item_price' => 15.00,
            'item_discount_price' => 0.00,
            'item_shippable' => 0,
            'item_downloadable' => 1,
            'item_weight' => 0.0,
            'order_id' => 0,
        ]);

        // Put PayPal credentials in env configuration dynamically for this test
        config(['services.paypal.sandbox_client_id' => 'test-id']);
        config(['services.paypal.sandbox_client_secret' => 'test-secret']);

        // Test Livewire component
        Livewire::test('order-review')
            ->call('preparePayment')
            ->assertReturned([
                'processor'      => 'paypal',
                'isSubscription' => false,
                'orderId'        => 'mock-paypal-order-id',
                'clientId'       => 'test-id',
            ])
            ->call('placeOrder', 'mock-paypal-order-id')
            ->assertHasNoErrors();

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            if (str_contains($request->url(), '/v2/checkout/orders/mock-paypal-order-id/capture')) {
                return $request->body() === '{}';
            }
            return true;
        });

        // Verify order payment record exists in the DB
        $payment = DB::table('order_payments')->where('payment_method', 'PayPal Payments')->first();
        $this->assertNotNull($payment);
        $this->assertEquals('mock-capture-id', $payment->authorization_code);
    }
}
