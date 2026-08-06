<?php

namespace App\Livewire;

use App\Models\CmsBuilderBlock;
use App\Models\NavItem;
use App\Models\NavMenu;
use App\Models\ShoppingCartLog;
use App\Services\HeaderFooterCssManager;
use App\Services\HeaderFooterParserService;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\On;
use Livewire\Component;

class PublicHeader extends Component
{
    public string $deviceView = 'desktop'; // 'desktop', 'tablet', 'mobile'
    public int $cartCount = 0;
    public bool $mobileMenuOpen = false;

    // Dynamic nav
    public ?NavMenu $navMenu = null;
    public $navItems = null;

    #[On('cart-updated')]
    public function updateCartCount(): void
    {
        $this->loadCartCount();
    }

    public function toggleMobileMenu(): void
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }

    #[On('toggle-frontend-dark-mode')]
    public function toggleFrontendDarkMode(): void
    {
        $current = \App\Models\CmsSetting::isEnabled('frontend_dark_mode');
        \App\Models\CmsSetting::set('frontend_dark_mode', $current ? '0' : '1');
    }

    public function setDeviceView(string $device): void
    {
        if (in_array($device, ['desktop', 'tablet', 'mobile'], true)) {
            if ($this->deviceView !== $device) {
                $this->deviceView = $device;
            }
        }
    }

    public function mount(): void
    {
        $this->detectDeviceFromUserAgent();
        $this->loadCartCount();
        $this->loadDynamicNav();
        \App\Services\AbandonedCartService::checkWebTriggeredReminders();
    }

    private function detectDeviceFromUserAgent(): void
    {
        $ua = request()->header('User-Agent', '');
        if (preg_match('/(iPad|PlayBook|Tablet|Silk|Kindle)/i', $ua)) {
            $this->deviceView = 'tablet';
        } elseif (preg_match('/(iPhone|iPod|Mobile|Android|BlackBerry|IEMobile|Opera Mini)/i', $ua)) {
            $this->deviceView = 'mobile';
        } else {
            $this->deviceView = 'desktop';
        }
    }

    private function loadCartCount(): void
    {
        $this->cartCount = 0;
        if (Schema::hasTable('shopping_cart_log')) {
            $cookieSessionId = request()->cookie('cart_session_id', '');
            $userId = auth()->id() ?? 0;
            $this->cartCount = ShoppingCartLog::where('order_id', 0)
                ->where(function ($query) use ($cookieSessionId, $userId) {
                    if ($userId > 0) {
                        $query->where('user_id', $userId)
                              ->orWhere(function ($sub) use ($cookieSessionId) {
                                  $sub->where('cart_log_session', $cookieSessionId)->where('user_id', 0);
                              });
                    } else {
                        $query->where('cart_log_session', $cookieSessionId)->where('user_id', 0);
                    }
                })->sum('item_qty');
        }
    }

    private function loadDynamicNav(): void
    {
        try {
            if (!Schema::hasTable('nav_menus')) return;

            $menu = NavMenu::getPrimary();
            if (!$menu) return;

            $this->navMenu  = $menu;
            $flat           = $menu->items()->withCurrentTranslations()->where('is_active', true)->get();
            $this->navItems = NavItem::buildTree($flat);
        } catch (\Throwable) {
            $this->navMenu  = null;
            $this->navItems = null;
        }
    }

    public function logout(\App\Livewire\Actions\Logout $logout): void
    {
        $logout();
        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        // Load active header blocks
        $hasBlocksTable = Schema::hasTable('cms_builder_blocks');
        $singleHeader   = (\App\Models\CmsSetting::get('single_header_config', '0') === '1');
        $device         = $singleHeader ? 'desktop' : (in_array($this->deviceView, ['desktop', 'tablet', 'mobile']) ? $this->deviceView : 'desktop');
        $headerBlocks   = $hasBlocksTable ? CmsBuilderBlock::header()->where(function($q) use ($singleHeader) {
            if ($singleHeader) {
                $q->where('is_active_desktop', true);
            } else {
                $q->where('is_active_desktop', true)
                  ->orWhere('is_active_tablet', true)
                  ->orWhere('is_active_mobile', true);
            }
        })->sortForDevice($device)->get() : collect();

        // Check if fallback to default navigation is needed
        $useFallback = !$hasBlocksTable || $headerBlocks->isEmpty();

        // Parse content for each block
        $parsedBlocks = [];
        foreach ($headerBlocks as $block) {
            $parsedBlocks[$block->target_element ?? $block->id] = [
                'block'   => $block,
                'content' => HeaderFooterParserService::parse($block->getContentForDevice($device), $block->target_element),
            ];
        }

        // Check if sticky navigation is enabled for full header
        $stickySetting = \App\Models\CmsSetting::get('top_nav_sticky', '1');
        $isSticky      = in_array($stickySetting, ['1', 1, true, 'true'], true);
        $cssVars       = HeaderFooterCssManager::getActiveVariables();

        return view('livewire.public-header', [
            'headerBlocks' => $headerBlocks,
            'parsedBlocks' => $parsedBlocks,
            'useFallback'  => $useFallback,
            'navMenu'      => $this->navMenu,
            'navItems'     => $this->navItems,
            'isSticky'     => $isSticky,
            'cssVars'      => $cssVars,
        ]);
    }
}
