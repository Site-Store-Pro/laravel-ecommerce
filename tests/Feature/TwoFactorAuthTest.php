<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\User;
use App\Services\TwoFactorAuthService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class TwoFactorAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        foreach (\App\Enums\UserRole::cases() as $role) {
            \Illuminate\Support\Facades\DB::table('user_roles')->insertOrIgnore([
                'id' => $role->value,
                'name' => $role->label(),
                'description' => $role->description(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function test_two_factor_service_reads_settings_correctly(): void
    {
        CmsSetting::set('enable_checkout_2fa', '1');
        CmsSetting::set('enable_login_2fa', '1');

        $this->assertTrue(TwoFactorAuthService::isCheckout2FaEnabled());
        $this->assertTrue(TwoFactorAuthService::isLogin2FaEnabled());

        CmsSetting::set('enable_checkout_2fa', '0');
        CmsSetting::set('enable_login_2fa', '0');

        $this->assertFalse(TwoFactorAuthService::isCheckout2FaEnabled());
        $this->assertFalse(TwoFactorAuthService::isLogin2FaEnabled());
    }

    public function test_social_users_are_exempt_from_login_and_checkout_2fa(): void
    {
        $socialUser = User::factory()->create([
            'email'    => 'social@example.com',
            'provider' => 'google',
            'provider_id' => 'google-12345',
            'last_login_at' => now()->subDays(60), // More than 30 days ago
        ]);

        $this->assertTrue(TwoFactorAuthService::isUserExemptFromLogin2Fa($socialUser));
        $this->assertTrue(TwoFactorAuthService::isCustomerExemptFromCheckout2Fa($socialUser, $socialUser->email));
    }

    public function test_users_active_within_30_days_are_exempt_from_login_2fa(): void
    {
        $recentUser = User::factory()->create([
            'email'         => 'recent@example.com',
            'provider'      => null,
            'last_login_at' => now()->subDays(10), // Within 30 days
        ]);

        $inactiveUser = User::factory()->create([
            'email'         => 'inactive@example.com',
            'provider'      => null,
            'last_login_at' => now()->subDays(45), // More than 30 days
        ]);

        $newUser = User::factory()->create([
            'email'         => 'new@example.com',
            'provider'      => null,
            'last_login_at' => null, // Never logged in before
        ]);

        $this->assertTrue(TwoFactorAuthService::isUserExemptFromLogin2Fa($recentUser));
        $this->assertFalse(TwoFactorAuthService::isUserExemptFromLogin2Fa($inactiveUser));
        $this->assertFalse(TwoFactorAuthService::isUserExemptFromLogin2Fa($newUser));
    }

    public function test_customers_with_orders_within_30_days_are_exempt_from_checkout_2fa(): void
    {
        $recentCustomer = User::factory()->create([
            'email'    => 'buyer@example.com',
            'provider' => null,
        ]);

        // Place an order 5 days ago
        Order::create([
            'order_external_id' => 'ORD-1001',
            'order_user_id'     => $recentCustomer->id,
            'order_status'      => 1,
            'order_date'        => now()->subDays(5),
            'order_total'       => 50.00,
        ]);

        $inactiveCustomer = User::factory()->create([
            'email'    => 'oldbuyer@example.com',
            'provider' => null,
        ]);

        // Place an order 45 days ago
        Order::create([
            'order_external_id' => 'ORD-1002',
            'order_user_id'     => $inactiveCustomer->id,
            'order_status'      => 1,
            'order_date'        => now()->subDays(45),
            'order_total'       => 50.00,
        ]);

        $newCustomer = User::factory()->create([
            'email'    => 'neverordered@example.com',
            'provider' => null,
        ]);

        $this->assertTrue(TwoFactorAuthService::isCustomerExemptFromCheckout2Fa($recentCustomer, $recentCustomer->email));
        $this->assertFalse(TwoFactorAuthService::isCustomerExemptFromCheckout2Fa($inactiveCustomer, $inactiveCustomer->email));
        $this->assertFalse(TwoFactorAuthService::isCustomerExemptFromCheckout2Fa($newCustomer, $newCustomer->email));
        $this->assertFalse(TwoFactorAuthService::isCustomerExemptFromCheckout2Fa(null, 'guest_unknown@example.com'));
    }

    public function test_login_triggers_2fa_challenge_when_enabled(): void
    {
        CmsSetting::set('enable_login_2fa', '1');

        $user = User::factory()->create([
            'email'         => 'login2fa@example.com',
            'password'      => Hash::make('SecretPassword123!'),
            'provider'      => null,
            'last_login_at' => now()->subDays(40),
        ]);

        \Livewire\Volt\Volt::test('pages.auth.login')
            ->set('form.email', 'login2fa@example.com')
            ->set('form.password', 'SecretPassword123!')
            ->call('login')
            ->assertRedirect(route('auth.verify-code', ['context' => 'login']));

        $this->assertFalse(Auth::check());
        $this->assertEquals('login', session('2fa_context'));
        $this->assertEquals($user->id, session('2fa_user_id'));
        $this->assertNotEmpty(session('2fa_code'));
        $this->assertEquals(6, strlen(session('2fa_code')));
    }

    public function test_verification_code_landing_form_completes_login(): void
    {
        $user = User::factory()->create([
            'email'         => 'verifyuser@example.com',
            'last_login_at' => null,
        ]);

        TwoFactorAuthService::startLoginChallenge($user);
        $validCode = session('2fa_code');

        // Test invalid code first
        Livewire::test(\App\Livewire\TwoFactorVerify::class, ['context' => 'login'])
            ->set('code', '000000')
            ->call('verify')
            ->assertHasErrors(['code']);

        $this->assertFalse(Auth::check());

        // Test valid code
        Livewire::test(\App\Livewire\TwoFactorVerify::class, ['context' => 'login'])
            ->set('code', $validCode)
            ->call('verify')
            ->assertHasNoErrors();

        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_verification_code_landing_form_completes_checkout_2fa(): void
    {
        TwoFactorAuthService::startCheckoutChallenge('checkout_customer@example.com', 'Jane Doe');
        $validCode = session('2fa_code');

        $this->assertFalse(TwoFactorAuthService::isCheckout2FaVerified());

        Livewire::test(\App\Livewire\TwoFactorVerify::class, ['context' => 'checkout'])
            ->set('code', $validCode)
            ->call('verify')
            ->assertHasNoErrors()
            ->assertRedirect(route('shop.checkout-review'));

        $this->assertTrue(TwoFactorAuthService::isCheckout2FaVerified());
    }

    public function test_resend_code_respects_cooldown(): void
    {
        $user = User::factory()->create(['email' => 'cooldown@example.com']);
        TwoFactorAuthService::startLoginChallenge($user);

        $resend1 = TwoFactorAuthService::resendCode('login');
        $this->assertFalse($resend1['success']);
        $this->assertGreaterThan(0, $resend1['seconds_remaining']);

        // Simulate elapsed cooldown
        session(['2fa_last_sent_at' => now()->subSeconds(50)->timestamp]);

        $resend2 = TwoFactorAuthService::resendCode('login');
        $this->assertTrue($resend2['success']);
    }
}
