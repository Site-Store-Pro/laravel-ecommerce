<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ShoppingCartLog;
use App\Models\NavMenu;
use App\Models\NavItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class PublicNavigation extends Component
{
    public int $cartCount = 0;
    public bool $mobileMenuOpen = false;

    // NOTE: $navMenu and $navItems are intentionally NOT public Livewire properties.
    // Livewire serialises public properties to JSON between requests, which strips
    // eager-loaded Eloquent relations (including `translations`). Instead, the nav
    // tree is rebuilt fresh on every render() call via buildNav() so that
    // HasTranslations::getAttribute() always sees the translations relation loaded.

    #[On('cart-updated')]
    public function updateCartCount(): void
    {
        $this->loadCartCount();
    }

    public function toggleMobileMenu(): void
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }

    public function mount(): void
    {
        $this->loadCartCount();
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

    /**
     * Build the nav tree fresh on every render so that eager-loaded translations
     * are always present. Returns [$navMenu, $navItems] or [null, null] on failure.
     */
    private function buildNav(): array
    {
        try {
            if (!Schema::hasTable('nav_menus')) {
                return [null, null];
            }

            $menu = NavMenu::getPrimary();
            if (!$menu) {
                return [null, null];
            }

            $flat     = $menu->items()
                ->withCurrentTranslations()
                ->where('is_active', true)
                ->get();
            $navItems = NavItem::buildTree($flat);

            return [$menu, $navItems];
        } catch (\Throwable) {
            // Nav tables not yet migrated — degrade gracefully to hardcoded nav
            return [null, null];
        }
    }

    public function logout(\App\Livewire\Actions\Logout $logout): void
    {
        $logout();
        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        [$navMenu, $navItems] = $this->buildNav();

        return view('livewire.public-navigation', [
            'navMenu'  => $navMenu,
            'navItems' => $navItems,
        ]);
    }
}
