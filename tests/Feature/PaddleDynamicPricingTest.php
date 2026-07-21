<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ShoppingCartLog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\OrderCheckoutOption;
use App\Models\OrderProcessor;
use App\Services\Payments\PaymentProcessorManager;
use App\Services\Payments\Processors\PaddleProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaddleDynamicPricingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('order_processors')->insert([
            ['processor_id' => 0, 'processor_name' => 'Test Gateway', 'production' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['processor_id' => 2, 'processor_name' => 'Paddle', 'production' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('order_checkout_options')->insert([
            'primary_processor'   => 2, // Paddle active
            'secondary_processor' => 0,
            'tertiary_processor'  => 0,
            'randomize_processor' => 0,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }

    public function test_paddle_variant_custom_fields_mapping(): void
    {
        $product = Product::create([
            'title' => 'Test Subscription Product',
            'seo_slug' => 'test-sub-prod',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SUB-123',
            'public_price' => 29.99,
            'wholesale_price' => 29.99,
            'paddle_price' => 29.99,
            'paddle_interval' => 'month',
            'paddle_frequency' => 1,
            'paddle_currency_code' => 'USD',
        ]);

        $this->assertEquals(29.99, $variant->paddle_price);
        $this->assertEquals('month', $variant->paddle_interval);
        $this->assertEquals(1, $variant->paddle_frequency);
        $this->assertEquals('USD', $variant->paddle_currency_code);
        $this->assertTrue($variant->isSubscriptionVariant());
    }

    public function test_paddle_transaction_creation_with_cart_items(): void
    {
        $product = Product::create([
            'title' => 'Dynamic Item',
            'seo_slug' => 'dyn-item',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'DYN-123',
            'public_price' => 39.99,
            'wholesale_price' => 39.99,
            'paddle_price' => 30.00, // mismatch to force dynamic pricing
            'paddle_interval' => 'month',
            'paddle_frequency' => 1,
            'paddle_currency_code' => 'USD',
        ]);

        // Create a mocked Paddle processor that returns a mock client
        $mockClient = $this->createMock(\Paddle\SDK\Client::class);
        $mockTransactions = $this->createMock(\Paddle\SDK\Resources\Transactions\TransactionsClient::class);
        $mockTransactionEntity = $this->createMock(\Paddle\SDK\Entities\Transaction::class);
        $mockTransactionEntity->id = 'txn_mock123';

        $mockTransactions->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($operation) {
                // Verify transaction items are constructed correctly
                $items = $operation->items;
                if (count($items) !== 1) {
                    return false;
                }
                $item = $items[0];
                if (!$item instanceof \Paddle\SDK\Resources\Transactions\Operations\Create\TransactionCreateItemWithPrice) {
                    return false;
                }
                $price = $item->price;
                if (!$price instanceof \Paddle\SDK\Resources\Transactions\Operations\Price\TransactionNonCatalogPriceWithProduct) {
                    return false;
                }
                if ($price->description !== 'Dynamic Item (DYN-123)') {
                    return false;
                }
                if ($price->unitPrice->amount !== '3999') { // 39.99 in cents
                    return false;
                }
                if ($price->billingCycle->interval->getValue() !== 'month') {
                    return false;
                }
                return true;
            }))
            ->willReturn($mockTransactionEntity);

        $mockPrices = $this->createMock(\Paddle\SDK\Resources\Prices\PricesClient::class);

        $refTrans = new \ReflectionProperty(\Paddle\SDK\Client::class, 'transactions');
        $refTrans->setValue($mockClient, $mockTransactions);

        $refPrices = new \ReflectionProperty(\Paddle\SDK\Client::class, 'prices');
        $refPrices->setValue($mockClient, $mockPrices);

        // Subclass PaddleProcessor to inject mock client
        $processor = new class($mockClient) extends PaddleProcessor {
            public function __construct(private $mockClient) {
                parent::__construct(true);
            }
            protected function client(): \Paddle\SDK\Client {
                return $this->mockClient;
            }
        };

        // Create ShoppingCartLog item
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);

        $cartItem = ShoppingCartLog::create([
            'cart_log_session' => 'sess_123',
            'item_name' => 'Dynamic Item (DYN-123)',
            'item_qty' => 1,
            'item_price' => 39.99,
            'item_discount_price' => 0.0,
            'item_taxable' => 1,
            'item_weight' => 0.0,
            'variant_id' => $variant->id,
            'user_id' => $user->id,
        ]);

        $res = $processor->createTransaction(39.99, 'USD', [
            'customer_email' => 'john@example.com',
            'cart_items' => [$cartItem],
        ]);

        $this->assertEquals('txn_mock123', $res['transaction_id']);
    }
}
