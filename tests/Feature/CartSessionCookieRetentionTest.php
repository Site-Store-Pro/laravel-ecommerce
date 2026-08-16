<?php

namespace Tests\Feature;

use App\Models\EmailTemplate;
use App\Models\ShoppingCartLog;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartSessionCookieRetentionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Mail::fake();
        \Illuminate\Support\Facades\DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Customer']);
        \Illuminate\Support\Facades\DB::table('email_template_types')->insertOrIgnore(['id' => 1, 'slug' => 'system', 'name' => 'System']);
    }

    private function createCartItem(array $attributes = []): ShoppingCartLog
    {
        return ShoppingCartLog::create(array_merge([
            'cart_log_session'    => (string) Str::uuid(),
            'item_name'           => 'Test Product (SKU-100)',
            'item_qty'            => 1,
            'item_price'          => 25.00,
            'item_discount_price' => 0,
            'item_shippable'      => 1,
            'item_weight'         => 0,
            'item_taxable'        => 1,
            'item_downloadable'   => 0,
            'variant_id'          => 1,
            'order_id'            => 0,
            'user_id'             => 0,
        ], $attributes));
    }

    public function test_guest_cart_persists_across_visits_via_cookie(): void
    {
        $sessionId = (string) Str::uuid();

        $this->createCartItem([
            'cart_log_session' => $sessionId,
            'item_name'        => 'Test Product (SKU-100)',
            'item_qty'         => 2,
        ]);

        // Request /cart with cart_session_id cookie set to $sessionId
        $response = $this->withCookie('cart_session_id', $sessionId)->get(route('shop.cart'));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertCookie('cart_session_id', $sessionId);
    }

    public function test_logged_in_user_cart_remains_visible_when_returning_as_guest(): void
    {
        $user = User::factory()->create(['role_id' => 3]);
        $sessionId = (string) Str::uuid();

        $this->createCartItem([
            'cart_log_session' => $sessionId,
            'item_name'        => 'Saved User Item (SKU-200)',
            'user_id'          => $user->id,
        ]);

        // User visits as guest (unauthenticated) with cart_session_id cookie
        $response = $this->withCookie('cart_session_id', $sessionId)->get(route('shop.cart'));

        $response->assertStatus(200);
        $response->assertSee('Saved User Item');
    }

    public function test_abandoned_cart_email_token_restores_cart_without_login(): void
    {
        $sessionId = (string) Str::uuid();

        $this->createCartItem([
            'cart_log_session' => $sessionId,
            'item_name'        => 'Abandoned Email Item (SKU-300)',
            'guest_email'      => 'customer@example.com',
        ]);

        // Click abandoned cart email link containing cart_token query parameter
        $url = route('shop.cart', ['cart_token' => $sessionId]);
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee('Abandoned Email Item');
        $response->assertCookie('cart_session_id', $sessionId);
    }

    public function test_abandoned_cart_reminders_include_cart_token_in_checkout_url(): void
    {
        \App\Models\CmsSetting::set('enable_abandoned_cart_reminder_1', '1');

        $type = \App\Models\EmailTemplateType::create([
            'slug' => 'abandoned_cart_reminder_1',
            'name' => 'Abandoned Cart 24h',
        ]);

        EmailTemplate::create([
            'slug'          => 'abandoned_cart_reminder_1',
            'email_type_id' => $type->id,
            'profile_name'  => 'default',
            'subject'       => 'Complete Your Order',
            'body'          => '<p>Click to checkout: {{checkout_url}}</p>',
            'is_active'     => 1,
        ]);

        $sessionId = (string) Str::uuid();

        $item = $this->createCartItem([
            'cart_log_session' => $sessionId,
            'item_name'        => 'Reminder Item (SKU-400)',
            'guest_email'      => 'abandoned@example.com',
        ]);

        // Set created_at past 24 hours threshold in DB
        \Illuminate\Support\Facades\DB::table('shopping_cart_log')
            ->where('id', $item->id)
            ->update(['created_at' => now()->subHours(25)]);

        $stats = \App\Services\AbandonedCartService::processReminders();

        $this->assertGreaterThanOrEqual(1, $stats['sent_24h']);
    }
}
