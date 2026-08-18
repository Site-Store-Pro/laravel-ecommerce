<?php

namespace Tests\Feature;

use App\Livewire\AdminOrderDetails;
use App\Livewire\ReportSubscriptions;
use App\Livewire\UserDashboard;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Models\OrderStatusList;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Plugins\Display\OrderStatusTrackerPlugin;
use App\Services\Payments\SubscriptionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class SubscriptionCancellationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        OrderStatusList::updateOrCreate(
            ['orderstatuscode' => 1],
            [
                'orderstatus'     => 'Open / Pending',
                'customerdisplay' => 'Processing',
                'Active'          => 1,
                'sortorder'       => 1,
            ]
        );
    }

    public function test_user_can_cancel_their_own_subscription_via_dashboard(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $order = Order::factory()->create([
            'order_user_id' => $user->id,
            'order_status'  => 1,
            'order_date'    => now(),
        ]);

        $detail = OrderDetail::create([
            'order_id'              => $order->id,
            'item_name'             => 'Monthly Plan',
            'item_qty'              => 1,
            'final_price'           => 49.00,
            'base_price'            => 49.00,
            'discount_price'        => 0.00,
            'options_fee'           => 0.00,
            'inventory_id'          => 1,
            'subscription'          => 1,
            'active_subscription'   => 1,
            'subscription_user_id'  => $user->id,
            'subscription_plan_id'  => 'I-TEST12345678',
            'subscription_provider' => 'paypal',
            'subscription_status'   => 'active',
        ]);

        $this->mock(SubscriptionService::class, function ($mock) use ($detail) {
            $mock->shouldReceive('cancelSubscription')
                ->once()
                ->andReturnUsing(function ($d) {
                    $d->update(['active_subscription' => 0, 'subscription_status' => 'cancelled']);
                    return true;
                });
        });

        $this->actingAs($user);

        Livewire::test(UserDashboard::class)
            ->call('cancelSubscription', $detail->id)
            ->assertSessionHas('status');

        $this->assertEquals(0, $detail->fresh()->active_subscription);
        $this->assertEquals('cancelled', $detail->fresh()->subscription_status);
    }

    public function test_user_cannot_cancel_another_users_subscription(): void
    {
        $user1 = User::factory()->create(['email_verified_at' => now()]);
        $user2 = User::factory()->create(['email_verified_at' => now()]);

        $order = Order::factory()->create([
            'order_user_id' => $user1->id,
            'order_status'  => 1,
            'order_date'    => now(),
        ]);

        $detail = OrderDetail::create([
            'order_id'              => $order->id,
            'item_name'             => 'Pro Plan',
            'item_qty'              => 1,
            'final_price'           => 99.00,
            'base_price'            => 99.00,
            'discount_price'        => 0.00,
            'options_fee'           => 0.00,
            'inventory_id'          => 1,
            'subscription'          => 1,
            'active_subscription'   => 1,
            'subscription_user_id'  => $user1->id,
            'subscription_plan_id'  => 'sub_stripe999',
            'subscription_provider' => 'stripe',
            'subscription_status'   => 'active',
        ]);

        $this->actingAs($user2);

        Livewire::test(UserDashboard::class)
            ->call('cancelSubscription', $detail->id)
            ->assertSessionHas('error');

        $this->assertEquals(1, $detail->fresh()->active_subscription);
    }

    public function test_admin_can_cancel_subscription_on_order_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'order_user_id' => $user->id,
            'order_status'  => 1,
            'order_date'    => now(),
        ]);

        $detail = OrderDetail::create([
            'order_id'              => $order->id,
            'item_name'             => 'Enterprise Plan',
            'item_qty'              => 1,
            'final_price'           => 299.00,
            'base_price'            => 299.00,
            'discount_price'        => 0.00,
            'options_fee'           => 0.00,
            'inventory_id'          => 1,
            'subscription'          => 1,
            'active_subscription'   => 1,
            'subscription_user_id'  => $user->id,
            'subscription_plan_id'  => 'sub_paddle001',
            'subscription_provider' => 'paddle',
            'subscription_status'   => 'active',
        ]);

        $this->mock(SubscriptionService::class, function ($mock) {
            $mock->shouldReceive('cancelSubscription')
                ->once()
                ->andReturnUsing(function ($d) {
                    $d->update(['active_subscription' => 0, 'subscription_status' => 'cancelled']);
                    return true;
                });
        });

        $this->actingAs($admin);

        Livewire::test(AdminOrderDetails::class, ['id' => $order->id])
            ->call('cancelSubscription', $detail->id)
            ->assertSessionHas('status');

        $this->assertEquals(0, $detail->fresh()->active_subscription);
    }

    public function test_report_subscriptions_lists_and_filters_subscriptions(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'order_user_id'     => $user->id,
            'order_status'      => 1,
            'order_invoice_no'  => 'INV-SUB-100',
            'order_date'        => now(),
        ]);

        $activeSub = OrderDetail::create([
            'order_id'              => $order->id,
            'item_name'             => 'Active Subscription Item',
            'item_qty'              => 1,
            'final_price'           => 50.00,
            'base_price'            => 50.00,
            'discount_price'        => 0.00,
            'options_fee'           => 0.00,
            'inventory_id'          => 1,
            'subscription'          => 1,
            'active_subscription'   => 1,
            'subscription_user_id'  => $user->id,
            'subscription_plan_id'  => 'I-ACTIVE999',
            'subscription_provider' => 'paypal',
            'subscription_status'   => 'active',
        ]);

        $cancelledSub = OrderDetail::create([
            'order_id'              => $order->id,
            'item_name'             => 'Cancelled Subscription Item',
            'item_qty'              => 1,
            'final_price'           => 30.00,
            'base_price'            => 30.00,
            'discount_price'        => 0.00,
            'options_fee'           => 0.00,
            'inventory_id'          => 2,
            'subscription'          => 1,
            'active_subscription'   => 0,
            'subscription_user_id'  => $user->id,
            'subscription_plan_id'  => 'sub_cancelled111',
            'subscription_provider' => 'stripe',
            'subscription_status'   => 'cancelled',
        ]);

        OrderPayment::create([
            'order_id'           => $order->id,
            'payment_amount'     => 50.00,
            'payment_method'     => 'PayPal Subscription',
            'authorization_code' => 'I-ACTIVE999',
            'payment_status'     => 1,
            'payment_date'       => now(),
        ]);

        $this->actingAs($admin);

        Livewire::test(ReportSubscriptions::class)
            ->assertSee('Active Subscription Item')
            ->assertSee('Cancelled Subscription Item')
            ->set('statusFilter', 'active')
            ->assertSee('Active Subscription Item')
            ->assertDontSee('Cancelled Subscription Item')
            ->set('statusFilter', 'cancelled')
            ->assertSee('Cancelled Subscription Item')
            ->assertDontSee('Active Subscription Item');
    }

    public function test_order_lookup_plugin_displays_active_subscription_and_cancels(): void
    {
        $user = User::factory()->create(['email' => 'customer@example.com']);
        $order = Order::factory()->create([
            'order_user_id'    => $user->id,
            'order_invoice_no' => 'VY0RR0MICZ',
            'order_status'     => 1,
            'order_date'       => now(),
        ]);

        $detail = OrderDetail::create([
            'order_id'              => $order->id,
            'item_name'             => 'Weekly Box',
            'item_qty'              => 1,
            'final_price'           => 25.00,
            'base_price'            => 25.00,
            'discount_price'        => 0.00,
            'options_fee'           => 0.00,
            'inventory_id'          => 1,
            'subscription'          => 1,
            'active_subscription'   => 1,
            'subscription_user_id'  => $user->id,
            'subscription_plan_id'  => 'I-BOX123',
            'subscription_provider' => 'paypal',
            'subscription_status'   => 'active',
        ]);

        $pluginModel = new \App\Models\Plugin([
            'slug'      => 'order-tracker-2026',
            'name'      => 'Order Status Tracker',
            'installed' => 1,
            'active'    => 1,
        ]);

        $tracker = new OrderStatusTrackerPlugin();

        // 1. Initial lookup view
        request()->merge([
            'ost_order_number' => 'VY0RR0MICZ',
            'ost_email'        => 'customer@example.com',
            'ost_submit'       => '1',
        ]);

        $html = $tracker->render([], $pluginModel);
        $this->assertStringContainsString('Active Subscription', $html);
        $this->assertStringContainsString('Cancel Subscription', $html);

        // 2. Cancellation through tracker
        $this->mock(SubscriptionService::class, function ($mock) {
            $mock->shouldReceive('cancelSubscription')
                ->once()
                ->andReturnUsing(function ($d) {
                    $d->update(['active_subscription' => 0, 'subscription_status' => 'cancelled']);
                    return true;
                });
        });

        request()->merge([
            'ost_cancel_sub_id' => $detail->id,
        ]);

        $htmlAfterCancel = $tracker->render([], $pluginModel);
        $this->assertStringContainsString('Subscription has been cancelled successfully.', $htmlAfterCancel);
        $this->assertEquals(0, $detail->fresh()->active_subscription);
    }
}
