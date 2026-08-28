<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\ShoppingCartLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutPasswordComplexityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        ShoppingCartLog::create([
            'cart_log_session' => 'test-session-password-123',
            'user_id' => 0,
            'order_id' => 0,
            'item_id' => 1,
            'item_name' => 'Downloadable Item',
            'item_qty' => 1,
            'item_price' => 25.00,
            'item_discount_price' => 0.00,
            'item_weight' => 0.00,
            'item_handling_fee' => 0.00,
            'item_tax_class' => 0,
            'item_shippable' => 0,
            'item_downloadable' => 1,
        ]);

        session()->put('cart_session_id', 'test-session-password-123');
    }

    public function test_simple_password_fails_complexity_validation_on_checkout(): void
    {
        Livewire::withCookie('cart_session_id', 'test-session-password-123')
            ->test(\App\Livewire\Checkout::class)
            ->set('name', 'John Doe')
            ->set('email', 'johndoe@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('saveDetailsAndContinue')
            ->assertHasErrors(['password']);
    }

    public function test_complex_password_passes_validation_on_checkout(): void
    {
        Livewire::withCookie('cart_session_id', 'test-session-password-123')
            ->test(\App\Livewire\Checkout::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'janedoe@example.com')
            ->set('password', 'Valid#SecretPass99!')
            ->set('password_confirmation', 'Valid#SecretPass99!')
            ->call('saveDetailsAndContinue')
            ->assertHasNoErrors(['password']);
    }

    public function test_empty_password_passes_when_guest_checkout_is_allowed(): void
    {
        CmsSetting::set('disable_guest_checkout', false);

        Livewire::withCookie('cart_session_id', 'test-session-password-123')
            ->test(\App\Livewire\Checkout::class)
            ->set('name', 'Guest Buyer')
            ->set('email', 'guestbuyer@example.com')
            ->set('password', '')
            ->set('password_confirmation', '')
            ->call('saveDetailsAndContinue')
            ->assertHasNoErrors(['password']);
    }

    public function test_empty_password_fails_when_guest_checkout_is_disabled(): void
    {
        CmsSetting::set('disable_guest_checkout', true);

        Livewire::withCookie('cart_session_id', 'test-session-password-123')
            ->test(\App\Livewire\Checkout::class)
            ->set('name', 'Required Buyer')
            ->set('email', 'requiredbuyer@example.com')
            ->set('password', '')
            ->set('password_confirmation', '')
            ->call('saveDetailsAndContinue')
            ->assertHasErrors(['password']);
    }
}
