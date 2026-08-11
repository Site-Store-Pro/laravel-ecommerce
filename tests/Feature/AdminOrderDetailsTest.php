<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderStatusList;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class AdminOrderDetailsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default OrderStatusList
        OrderStatusList::create([
            'orderstatuscode' => 1,
            'orderstatus' => 'Open',
            'customerdisplay' => 'Processed',
            'Active' => 1,
            'sortorder' => 1,
        ]);
        OrderStatusList::create([
            'orderstatuscode' => 5,
            'orderstatus' => 'Partially Shipped',
            'customerdisplay' => 'Partially Shipped',
            'Active' => 1,
            'sortorder' => 5,
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@support.local',
            'password' => bcrypt('password'),
            'role_id' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role_id' => UserRole::User,
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
            'sku' => 'TEST-01',
            'public_price' => 130.00,
            'wholesale_price' => 130.00,
            'download_item' => 0,
        ]);

        $this->order = Order::create([
            'order_invoice_no' => 'INV-2026-0001',
            'order_external_id' => 'ext_123',
            'order_user_id' => $customer->id,
            'order_status' => 1, // Open
            'order_date' => now(),
            'order_total' => 150.00,
            'order_subtotal' => 130.00,
            'order_taxes' => 10.00,
            'order_discounts' => 0.00,
            'order_shipping' => 10.00,
            'order_shipping_method' => 1,
            'order_shipping_method_name' => 'Standard Shipping',
        ]);

        OrderDetail::create([
            'order_id' => $this->order->id,
            'variant_id' => $variant->id,
            'item_qty' => 1,
            'item_name' => 'Test Item',
            'base_price' => 130.00,
            'final_price' => 130.00,
            'discount_price' => 0.00,
            'options_fee' => 0.00,
            'download_item' => 0,
        ]);
    }

    public function test_admin_can_view_order_details(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\AdminOrderDetails::class, ['id' => $this->order->id])
            ->assertStatus(200)
            ->assertSee('INV-2026-0001')
            ->assertSee('Test Item');
    }

    public function test_partially_shipped_status_is_hidden(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\AdminOrderDetails::class, ['id' => $this->order->id])
            ->assertViewHas('statuses', function ($statuses) {
                // Partially Shipped has code 5. We assert that it does NOT exist in the list.
                foreach ($statuses as $status) {
                    if ((int)$status->orderstatuscode === 5) {
                        return false;
                    }
                }
                return true;
            });
    }

    public function test_can_resend_duplicate_order_confirmation_email(): void
    {
        Mail::fake();
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\AdminOrderDetails::class, ['id' => $this->order->id])
            ->assertSet('showEmailConfirm', false)
            ->call('triggerEmailConfirm')
            ->assertSet('showEmailConfirm', true)
            ->call('cancelEmailSend')
            ->assertSet('showEmailConfirm', false)
            ->call('triggerEmailConfirm')
            ->call('sendDuplicateOrderConfirmation')
            ->assertSet('showEmailConfirm', false);

        Mail::assertSent(\App\Mail\DynamicTemplateMail::class, function ($mail) {
            return $mail->hasTo('customer@example.com') && $mail->tpl->type->slug === 'order_confirmation';
        });
    }

    public function test_admin_sees_order_comments_and_payment_history(): void
    {
        // 1. Test order with comments and payments
        $this->order->order_comments = 'Customer requested fast delivery.';
        $this->order->save();

        \App\Models\OrderPayment::create([
            'order_id' => $this->order->id,
            'payment_date' => now(),
            'payment_amount' => 150.00,
            'payment_method' => 'Stripe Checkout',
            'payment_status' => 'Success',
            'authorization_code' => 'AUTH-ST-998',
            'processor_response' => 'Completed',
        ]);

        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\AdminOrderDetails::class, ['id' => $this->order->id])
            ->assertStatus(200)
            ->assertSee('Customer requested fast delivery.')
            ->assertSee('Stripe Checkout')
            ->assertSee('AUTH-ST-998')
            ->assertSee('$150.00');

        // 2. Test order with no comments
        $this->order->order_comments = null;
        $this->order->save();

        Livewire::test(\App\Livewire\AdminOrderDetails::class, ['id' => $this->order->id])
            ->assertStatus(200)
            ->assertSee('no customer comments for this order');
    }
}
