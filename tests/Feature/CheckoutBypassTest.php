<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ShoppingCartLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutBypassTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->withoutMiddleware(\Illuminate\Cookie\Middleware\EncryptCookies::class);
    }

    private function createCartItem(array $attributes): ShoppingCartLog
    {
        return ShoppingCartLog::create(array_merge([
            'cart_log_session' => 'test-session-123',
            'user_id' => 0,
            'order_id' => 0,
            'item_id' => 1,
            'item_qty' => 1,
            'item_price' => 10.00,
            'item_discount_price' => 0.00,
            'item_weight' => 0.00,
            'item_handling_fee' => 0.00,
            'item_tax_class' => 0,
        ], $attributes));
    }

    public function test_guest_is_not_bypassed(): void
    {
        $this->createCartItem([
            'item_name' => 'Physical Item',
            'item_shippable' => 1,
            'item_downloadable' => 0,
        ]);

        $response = $this->withUnencryptedCookie('cart_session_id', 'test-session-123')
            ->get(route('shop.checkout'));

        $response->assertStatus(200);
    }

    public function test_user_with_incomplete_profile_is_not_bypassed(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);

        $this->createCartItem([
            'user_id' => $user->id,
            'item_name' => 'Physical Item',
            'item_shippable' => 1,
            'item_downloadable' => 0,
        ]);

        $response = $this->actingAs($user)
            ->withUnencryptedCookie('cart_session_id', 'test-session-123')
            ->get(route('shop.checkout'));

        $response->assertStatus(200);
    }

    public function test_user_with_complete_profile_is_bypassed_to_review(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'shipping_address1' => '123 Test St',
            'shipping_city' => 'Testville',
            'shipping_countrycode' => 'US',
            'shipping_state' => 'CA',
            'shopping_postalcode' => '90210',
        ]);

        $this->createCartItem([
            'user_id' => $user->id,
            'item_name' => 'Physical Item',
            'item_shippable' => 1,
            'item_downloadable' => 0,
        ]);

        $response = $this->actingAs($user)
            ->withUnencryptedCookie('cart_session_id', 'test-session-123')
            ->get(route('shop.checkout'));

        $response->assertRedirect(route('shop.checkout-review'));
    }

    public function test_user_with_complete_profile_is_not_bypassed_when_editing(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'shipping_address1' => '123 Test St',
            'shipping_city' => 'Testville',
            'shipping_countrycode' => 'US',
            'shipping_state' => 'CA',
            'shopping_postalcode' => '90210',
        ]);

        $this->createCartItem([
            'user_id' => $user->id,
            'item_name' => 'Physical Item',
            'item_shippable' => 1,
            'item_downloadable' => 0,
        ]);

        $response = $this->actingAs($user)
            ->withUnencryptedCookie('cart_session_id', 'test-session-123')
            ->get(route('shop.checkout', ['edit' => 1]));

        $response->assertStatus(200);
    }

    public function test_download_only_order_is_bypassed_with_name_and_email(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);

        $this->createCartItem([
            'user_id' => $user->id,
            'item_name' => 'Digital Item',
            'item_shippable' => 0,
            'item_downloadable' => 1,
        ]);

        $response = $this->actingAs($user)
            ->withUnencryptedCookie('cart_session_id', 'test-session-123')
            ->get(route('shop.checkout'));

        $response->assertRedirect(route('shop.checkout-review'));
    }
}
