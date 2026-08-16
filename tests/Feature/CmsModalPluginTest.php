<?php

namespace Tests\Feature;

use App\Models\CmsModal;
use App\Plugins\Display\ModalDisplayPlugin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CmsModalPluginTest extends TestCase
{
    use RefreshDatabase;

    public function test_modal_display_plugin_renders_auto_open_modal(): void
    {
        $modal = CmsModal::create([
            'title' => 'Welcome Promotion Modal',
            'body' => '<p>Get 20% off your first order!</p>',
            'position' => 'center',
            'max_width' => '640px',
            'auto_open' => true,
            'open_delay' => 500,
            'cookie_lifetime' => 0,
            'overlay_dismissible' => true,
            'backdrop_blur' => true,
            'is_active' => true,
        ]);

        $plugin = new \App\Models\Plugin([
            'shortcode' => 'modal',
            'type' => 'display',
            'activation_status' => 1,
        ]);

        $pluginService = new ModalDisplayPlugin();
        $html = $pluginService->render(['id' => $modal->id], $plugin);

        // Verify HTML element output & backdrop blur
        $this->assertStringContainsString('id="cms-modal-outer-' . $modal->id . '"', $html);
        $this->assertStringContainsString('id="cms-modal-bd-' . $modal->id . '"', $html);
        $this->assertStringContainsString('Welcome Promotion Modal', $html);
        $this->assertStringContainsString('bg-slate-900/65', $html);
        $this->assertStringNotContainsString('backdrop-filter', $html);

        // Verify JavaScript auto-open & outside click dismiss logic
        $this->assertStringContainsString('cmsModalOpen_', $html);
        $this->assertStringContainsString('el.style.display="block"', $html);
        $this->assertStringContainsString('!e.target.closest(".cms-modal-panel")', $html);
    }

    public function test_modal_display_plugin_respects_disabled_backdrop_blur(): void
    {
        $modal = CmsModal::create([
            'title' => 'No Blur Modal',
            'body' => '<p>No backdrop blur here</p>',
            'position' => 'center',
            'backdrop_blur' => false,
            'is_active' => true,
        ]);

        $plugin = new \App\Models\Plugin([
            'shortcode' => 'modal',
            'type' => 'display',
            'activation_status' => 1,
        ]);

        $pluginService = new ModalDisplayPlugin();
        $html = $pluginService->render(['id' => $modal->id], $plugin);

        $this->assertStringContainsString('display: none !important', $html);
        $this->assertStringNotContainsString('id="cms-modal-bd-' . $modal->id . '"', $html);
    }

    public function test_modal_display_plugin_renders_bottom_positioned_modal(): void
    {
        $modal = CmsModal::create([
            'title' => 'Bottom Banner Modal',
            'body' => '<p>Special promotion on bottom</p>',
            'position' => 'bottom',
            'is_active' => true,
        ]);

        $plugin = new \App\Models\Plugin([
            'shortcode' => 'modal',
            'type' => 'display',
            'activation_status' => 1,
        ]);

        $pluginService = new ModalDisplayPlugin();
        $html = $pluginService->render(['id' => $modal->id], $plugin);

        // Verify bottom flex container styling & backdrop div presence
        $this->assertStringContainsString('h-full w-full flex flex-col justify-end', $html);
        $this->assertStringContainsString('rounded-t-3xl', $html);
        $this->assertStringContainsString('id="cms-modal-bd-' . $modal->id . '"', $html);
    }

    public function test_modal_display_plugin_applies_custom_bg_color(): void
    {
        $modal = CmsModal::create([
            'title' => 'Glass White Modal',
            'body' => '<p>Glassmorphism style modal</p>',
            'position' => 'center',
            'bg_color' => 'rgba(255,255,255,0.85)',
            'is_active' => true,
        ]);

        $plugin = new \App\Models\Plugin([
            'shortcode' => 'modal',
            'type' => 'display',
            'activation_status' => 1,
        ]);

        $pluginService = new ModalDisplayPlugin();
        $html = $pluginService->render(['id' => $modal->id], $plugin);

        $this->assertStringContainsString('background-color:rgba(255,255,255,0.85)', $html);
    }
}
