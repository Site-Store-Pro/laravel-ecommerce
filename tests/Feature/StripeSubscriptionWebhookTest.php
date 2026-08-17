<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\User;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StripeSubscriptionWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("INSERT IGNORE INTO `user_roles` (`id`, `name`, `description`) VALUES 
            (1, 'User', 'Customer'),
            (2, 'Wholesale', 'Wholesale'),
            (3, 'Admin', 'Admin')");
    }

    public function test_initial_subscription_invoice_is_skipped_and_not_duplicated(): void
    {
        $user = User::create([
            'name'               => 'Test Customer',
            'email'              => 'test_cust_1@example.com',
            'password'           => bcrypt('password'),
            'role_id'            => UserRole::User,
            'stripe_customer_id' => 'cus_test_123',
        ]);

        $order = Order::create([
            'order_invoice_no' => 'INV-TEST-001',
            'order_user_id'    => $user->id,
            'order_total'      => 29.99,
            'order_subtotal'   => 29.99,
            'order_taxes'      => 0.00,
            'order_discounts'  => 0.00,
            'order_status'     => 1,
            'order_date'       => now(),
        ]);

        // Initial payment record created by checkout
        $initialPayment = OrderPayment::create([
            'order_id'           => $order->id,
            'payment_date'       => now(),
            'payment_amount'     => 29.99,
            'payment_method'     => 'Stripe',
            'payment_status'     => 1,
            'authorization_code' => 'sub_test_sub_123',
            'processor_response' => 'Subscription: sub_test_sub_123 | pm_test_pm_123',
        ]);

        $controller = new StripeWebhookController();

        // Simulate initial invoice webhook (subscription_create)
        $invoice = (object)[
            'id'             => 'in_test_initial_001',
            'subscription'   => 'sub_test_sub_123',
            'customer'       => 'cus_test_123',
            'billing_reason' => 'subscription_create',
            'amount_paid'    => 2999,
            'payment_intent' => 'pi_test_pi_001',
            'status'         => 'paid',
        ];

        // Invoke private handleInvoiceEvent via reflection
        $method = new \ReflectionMethod(StripeWebhookController::class, 'handleInvoiceEvent');
        $method->setAccessible(true);
        $method->invoke($controller, 'invoice.payment_succeeded', $invoice);

        // Verify no duplicate payment was created
        $this->assertEquals(1, OrderPayment::where('order_id', $order->id)->count());
    }

    public function test_recurring_subscription_invoice_records_renewal_payment_for_matching_order(): void
    {
        $user = User::create([
            'name'               => 'Test Customer 2',
            'email'              => 'test_cust_2@example.com',
            'password'           => bcrypt('password'),
            'role_id'            => UserRole::User,
            'stripe_customer_id' => 'cus_test_456',
        ]);

        // Order 1: Subscription A (sub_AAA_111)
        $orderA = Order::create([
            'order_invoice_no' => 'INV-SUB-A',
            'order_user_id'    => $user->id,
            'order_total'      => 19.99,
            'order_subtotal'   => 19.99,
            'order_taxes'      => 0.00,
            'order_discounts'  => 0.00,
            'order_status'     => 1,
            'order_date'       => now()->subMonth(),
        ]);

        OrderPayment::create([
            'order_id'           => $orderA->id,
            'payment_date'       => now()->subMonth(),
            'payment_amount'     => 19.99,
            'payment_method'     => 'Stripe',
            'payment_status'     => 1,
            'authorization_code' => 'sub_AAA_111',
            'processor_response' => 'Subscription: sub_AAA_111 | pm_test_aaa',
        ]);

        // Order 2: Subscription B (sub_BBB_222)
        $orderB = Order::create([
            'order_invoice_no' => 'INV-SUB-B',
            'order_user_id'    => $user->id,
            'order_total'      => 49.99,
            'order_subtotal'   => 49.99,
            'order_taxes'      => 0.00,
            'order_discounts'  => 0.00,
            'order_status'     => 1,
            'order_date'       => now()->subMonth(),
        ]);

        OrderPayment::create([
            'order_id'           => $orderB->id,
            'payment_date'       => now()->subMonth(),
            'payment_amount'     => 49.99,
            'payment_method'     => 'Stripe',
            'payment_status'     => 1,
            'authorization_code' => 'sub_BBB_222',
            'processor_response' => 'Subscription: sub_BBB_222 | pm_test_bbb',
        ]);

        $controller = new StripeWebhookController();
        $method = new \ReflectionMethod(StripeWebhookController::class, 'handleInvoiceEvent');
        $method->setAccessible(true);

        // Simulate recurring invoice for Subscription B (subscription_cycle)
        $invoiceRenewalB = (object)[
            'id'             => 'in_renewal_sub_b_002',
            'subscription'   => 'sub_BBB_222',
            'customer'       => 'cus_test_456',
            'billing_reason' => 'subscription_cycle',
            'amount_paid'    => 4999,
            'payment_intent' => 'pi_renewal_pi_b_002',
            'status'         => 'paid',
        ];

        $method->invoke($controller, 'invoice.payment_succeeded', $invoiceRenewalB);

        // Order A should still have only 1 payment
        $this->assertEquals(1, OrderPayment::where('order_id', $orderA->id)->count());

        // Order B should now have 2 payments
        $this->assertEquals(2, OrderPayment::where('order_id', $orderB->id)->count());

        $newPayment = OrderPayment::where('order_id', $orderB->id)->latest('id')->first();
        $this->assertEquals(49.99, (float)$newPayment->payment_amount);
        $this->assertEquals('pi_renewal_pi_b_002', $newPayment->authorization_code);
        $this->assertStringContainsString('sub_BBB_222', $newPayment->processor_response);
        $this->assertStringContainsString('in_renewal_sub_b_002', $newPayment->processor_response);

        // Test idempotency: second call with same invoice should not duplicate
        $method->invoke($controller, 'invoice.payment_succeeded', $invoiceRenewalB);
        $this->assertEquals(2, OrderPayment::where('order_id', $orderB->id)->count());
    }

    public function test_new_stripe_nested_invoice_schema_payload_records_payment(): void
    {
        $user = User::create([
            'name'               => 'Kevin Rounsavelle',
            'email'              => 'krounsavelle@sitestorepro.com',
            'password'           => bcrypt('password'),
            'role_id'            => UserRole::User,
            'stripe_customer_id' => 'cus_V5R9bTGFFOGrXQ',
        ]);

        $order = Order::create([
            'order_invoice_no' => 'INV-KEVIN-001',
            'order_user_id'    => $user->id,
            'order_total'      => 26.98,
            'order_subtotal'   => 26.98,
            'order_taxes'      => 0.00,
            'order_discounts'  => 0.00,
            'order_status'     => 1,
            'order_date'       => now()->subMonth(),
        ]);

        OrderPayment::create([
            'order_id'           => $order->id,
            'payment_date'       => now()->subMonth(),
            'payment_amount'     => 26.98,
            'payment_method'     => 'Stripe',
            'payment_status'     => 1,
            'authorization_code' => 'sub_1U5H0d2INLEQUGO1WMdEXUKP',
            'processor_response' => 'Subscription: sub_1U5H0d2INLEQUGO1WMdEXUKP | pm_1U5GH42INLEQUGO16Jf7QWJU',
        ]);

        // Exact payload JSON structure sent by Stripe in user request
        $payloadJson = <<<'JSON'
{
  "id": "in_1U5H4p2INLEQUGO1gax9hA91",
  "object": "invoice",
  "amount_paid": 2698,
  "billing_reason": "subscription_cycle",
  "customer": "cus_V5R9bTGFFOGrXQ",
  "customer_email": "krounsavelle@sitestorepro.com",
  "lines": {
    "data": [
      {
        "id": "il_1UTwbg2INLEQUGO1nu6PJlgJ",
        "parent": {
          "subscription_item_details": {
            "subscription": "sub_1U5H0d2INLEQUGO1WMdEXUKP"
          }
        },
        "pricing": {
          "price_details": {
            "price": "price_1U5H0c2INLEQUGO1lFiAp0u2",
            "product": "prod_V5RuvBcUad2mTa"
          }
        }
      }
    ]
  },
  "parent": {
    "subscription_details": {
      "subscription": "sub_1U5H0d2INLEQUGO1WMdEXUKP"
    }
  },
  "status": "paid"
}
JSON;

        $invoiceData = json_decode($payloadJson);

        $controller = new StripeWebhookController();
        $method = new \ReflectionMethod(StripeWebhookController::class, 'handleInvoiceEvent');
        $method->setAccessible(true);

        $method->invoke($controller, 'invoice.payment_succeeded', $invoiceData);

        // Assert payment was recorded
        $this->assertEquals(2, OrderPayment::where('order_id', $order->id)->count());

        $newPayment = OrderPayment::where('order_id', $order->id)->latest('id')->first();
        $this->assertEquals(26.98, (float)$newPayment->payment_amount);
        $this->assertEquals('in_1U5H4p2INLEQUGO1gax9hA91', $newPayment->authorization_code);
        $this->assertStringContainsString('sub_1U5H0d2INLEQUGO1WMdEXUKP', $newPayment->processor_response);
    }
}
