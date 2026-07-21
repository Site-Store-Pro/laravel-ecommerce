<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'CmsSettingsSeeder']);
    }

    public function test_seeds_all_five_default_settings(): void
    {
        $this->assertEquals('0', CmsSetting::where('key', 'frontend_dark_mode')->value('value'));
        $this->assertEquals('0', CmsSetting::where('key', 'admin_dark_mode')->value('value'));
        $this->assertTrue(CmsSetting::where('key', 'google_fonts_url')->exists());
        $this->assertTrue(CmsSetting::where('key', 'google_analytics_id')->exists());
        $this->assertTrue(CmsSetting::where('key', 'custom_js_loader')->exists());
    }

    public function test_cms_setting_get_returns_default_when_key_missing(): void
    {
        $this->assertNull(CmsSetting::get('nonexistent_key'));
        $this->assertEquals('fallback', CmsSetting::get('nonexistent_key', 'fallback'));
    }

    public function test_cms_setting_set_updates_value(): void
    {
        CmsSetting::set('frontend_dark_mode', '1');
        $this->assertEquals('1', CmsSetting::get('frontend_dark_mode'));
    }

    public function test_cms_setting_is_enabled_casts_correctly(): void
    {
        CmsSetting::set('frontend_dark_mode', '0');
        $this->assertFalse(CmsSetting::isEnabled('frontend_dark_mode'));

        CmsSetting::set('frontend_dark_mode', '1');
        $this->assertTrue(CmsSetting::isEnabled('frontend_dark_mode'));
    }

    public function test_admin_settings_page_redirects_unauthenticated_users(): void
    {
        $this->get('/admin/settings')->assertRedirect('/login');
    }

    public function test_admin_settings_page_loads_for_admin_user(): void
    {
        $admin = User::factory()->create([
            'role_id'           => UserRole::Admin->value,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/settings')
            ->assertStatus(200)
            ->assertSee('Site Settings')
            ->assertSee('Frontend Dark Mode')
            ->assertSee('Admin Dark Mode')
            ->assertSee('Google Fonts Stylesheet')
            ->assertSee('Google Analytics');
    }

    public function test_admin_can_save_settings_via_livewire(): void
    {
        $admin = User::factory()->create([
            'role_id'           => UserRole::Admin->value,
            'email_verified_at' => now(),
        ]);

        \Livewire\Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminSettings::class)
            ->set('frontend_dark_mode', true)
            ->set('admin_dark_mode', true)
            ->set('google_fonts_url', '<link rel="preconnect" href="https://fonts.googleapis.com">')
            ->set('google_analytics_id', 'G-TEST123456')
            ->set('custom_js_loader', '<script>alert("test");</script>')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('1', CmsSetting::get('frontend_dark_mode'));
        $this->assertEquals('1', CmsSetting::get('admin_dark_mode'));
        $this->assertEquals('<link rel="preconnect" href="https://fonts.googleapis.com">', CmsSetting::get('google_fonts_url'));
        $this->assertEquals('G-TEST123456', CmsSetting::get('google_analytics_id'));
        $this->assertEquals('<script>alert("test");</script>', CmsSetting::get('custom_js_loader'));
    }
}
