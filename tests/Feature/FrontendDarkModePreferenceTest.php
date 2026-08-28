<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\User;
use App\Services\HeaderFooterParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cookie;
use Livewire\Livewire;
use Tests\TestCase;

class FrontendDarkModePreferenceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_toggle_sets_cookie_and_does_not_modify_cms_settings(): void
    {
        // Admin setting default is 0 (light)
        CmsSetting::set('frontend_dark_mode', '0');

        Livewire::test(\App\Livewire\PublicHeader::class)
            ->call('toggleFrontendDarkMode', 'dark');

        $this->assertNotNull(Cookie::queued('frontend_theme'));
        $this->assertEquals('dark', Cookie::queued('frontend_theme')->getValue());

        // Verify CmsSetting was NOT modified
        $this->assertFalse(CmsSetting::isEnabled('frontend_dark_mode'));
    }

    public function test_authenticated_user_toggle_updates_user_preference_and_cookie(): void
    {
        CmsSetting::set('frontend_dark_mode', '0');

        $user = User::factory()->create([
            'role_id' => \App\Enums\UserRole::User,
            'theme_preference' => 'light',
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublicHeader::class)
            ->call('toggleFrontendDarkMode', 'dark');

        $this->assertNotNull(Cookie::queued('frontend_theme'));
        $this->assertEquals('dark', Cookie::queued('frontend_theme')->getValue());

        $this->assertEquals('dark', $user->fresh()->theme_preference);
        $this->assertFalse(CmsSetting::isEnabled('frontend_dark_mode'));
    }

    public function test_switcher_is_rendered_only_when_enabled_in_cms_settings(): void
    {
        CmsSetting::set('show_frontend_dark_mode_switcher', true);
        $parsedWithSwitcher = HeaderFooterParserService::parse('{{cart_features}}');
        $this->assertStringContainsString('toggleTheme()', $parsedWithSwitcher);
        $this->assertStringContainsString('Toggle Dark Mode', $parsedWithSwitcher);

        CmsSetting::set('show_frontend_dark_mode_switcher', false);
        $parsedWithoutSwitcher = HeaderFooterParserService::parse('{{cart_features}}');
        $this->assertStringNotContainsString('toggleTheme()', $parsedWithoutSwitcher);
    }

    public function test_guest_retains_dark_mode_cookie_across_page_requests(): void
    {
        CmsSetting::set('frontend_dark_mode', '0');

        $responseHome = $this->withUnencryptedCookie('frontend_theme', 'dark')->get('/');
        $responseHome->assertStatus(200);
        $responseHome->assertSee('class="scroll-smooth dark"', false);

        $responseShop = $this->withUnencryptedCookie('frontend_theme', 'dark')->get('/shop');
        $responseShop->assertStatus(200);
        $responseShop->assertSee('class="overflow-x-hidden max-w-full dark"', false);

        $responseLight = $this->withUnencryptedCookie('frontend_theme', 'light')->get('/shop');
        $responseLight->assertStatus(200);
        $responseLight->assertSee('class="overflow-x-hidden max-w-full "', false);
        $responseLight->assertDontSee('class="overflow-x-hidden max-w-full dark"', false);
    }
}
