<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CmsSetting;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiteThemeCustomizerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@support.local',
            'password' => bcrypt('password'),
            'role_id' => 1, // Admin role
        ]);
    }

    public function test_admin_can_update_theme_settings(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\AdminSettings::class)
            ->set('theme_primary_color', '#ff3366')
            ->set('theme_hover_color', '#cc0033')
            ->set('theme_text_color', '#ffffea')
            ->set('theme_border_radius', '12px')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('toast', message: 'Settings saved successfully.', type: 'success');

        $this->assertEquals('#ff3366', CmsSetting::get('theme_primary_color'));
        $this->assertEquals('#cc0033', CmsSetting::get('theme_hover_color'));
        $this->assertEquals('#ffffea', CmsSetting::get('theme_text_color'));
        $this->assertEquals('12px', CmsSetting::get('theme_border_radius'));
    }

    public function test_theme_styles_rendered_in_public_layout(): void
    {
        CmsSetting::setMany([
            'theme_primary_color' => '#112233',
            'theme_hover_color' => '#445566',
            'theme_text_color' => '#ffffff',
            'theme_border_radius' => '8px',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        // Assert style block exists and contains custom variables
        $response->assertSee('id="site-theme-customizer-styles"', false);
        $response->assertSee('--theme-primary: #112233', false);
        $response->assertSee('--theme-primary-hover: #445566', false);
        $response->assertSee('--theme-text: #ffffff', false);
        $response->assertSee('--theme-border-radius: 8px', false);
    }
}
