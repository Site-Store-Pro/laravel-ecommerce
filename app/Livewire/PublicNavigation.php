<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ShoppingCartLog;
use App\Models\NavMenu;
use App\Models\NavItem;
use Illuminate\Support\Facades\Schema;

class PublicNavigation extends Component
{
    public int $cartCount = 0;
    public bool $mobileMenuOpen = false;

    // Dynamic nav — null means fall back to the hardcoded nav
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

    public function mount(): void
    {
        $this->loadCartCount();
        $this->loadDynamicNav();
    }

    private function loadCartCount(): void
    {
        $this->cartCount = 0;
        if (Schema::hasTable('shopping_cart_log')) {
            $cookieSessionId = request()->cookie('cart_session_id', '');
            $userId = auth()->id() ?? 0;
            $this->cartCount = ShoppingCartLog::where('order_id', 0)
                ->where(function($query) use ($cookieSessionId, $userId) {
                    if ($userId > 0) {
                        $query->where('user_id', $userId)
                              ->orWhere(function($sub) use ($cookieSessionId) {
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
            $flat           = $menu->items()->where('is_active', true)->get();
            $this->navItems = NavItem::buildTree($flat);
        } catch (\Throwable) {
            // Nav tables not yet migrated — degrade gracefully to hardcoded nav
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
        return view('livewire.public-navigation', [
            'navMenu'  => $this->navMenu,
            'navItems' => $this->navItems,
        ]);
    }
}
