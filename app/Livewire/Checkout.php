<?php

namespace App\Livewire;

use App\Models\CheckoutCustomField;
use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Checkout extends Component
{
    // Shipping fields
    public string $name = '';
    public string $email = '';
    public string $company = '';
    public string $shipping_address1 = '';
    public string $shipping_address2 = '';
    public string $shipping_city = '';
    public string $shopping_postalcode = '';
    public string $shipping_country = 'United States';
    public string $shipping_countrycode = 'US';
    public string $shipping_state = '';

    public function updatedShippingCountrycode($value): void
    {
        $country = \Illuminate\Support\Facades\DB::table('shipping_countries')->where('code', $value)->first();
        if ($country) {
            $this->shipping_country = $country->name;
        }
        $this->shipping_state = '';
    }

    // Guest registration password
    public string $password = '';
    public string $password_confirmation = '';

    // Guest login fields
    public string $loginEmail = '';
    public string $loginPassword = '';
    public bool $showLoginForm = false;

    // Shippable flag
    public bool $requiresShipping = false;

    // Coupon Code
    public string $couponCode = '';

    // Checkout custom fields
    public array $checkoutCustomData = [];   // values keyed by field index
    public bool  $checkoutOptIn      = false; // manual opt-in checkbox (if mode=manual+position=checkout)

    public function applyCoupon(): void
    {
        $this->validate([
            'couponCode' => 'required|string|max:50'
        ]);

        $now = now();
        $coupon = \App\Models\Discount::where('is_active', 1)
            ->where('discount_type_id', 1)
            ->where('code', $this->couponCode)
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('expiration_date')->orWhere('expiration_date', '>=', $now);
            })
            ->first();

        if ($coupon) {
            $items = $this->getCartQuery()->get();
            $discountResult = \App\Services\DiscountService::applyDiscountsToCart($items, Auth::user());
            $subtotal = $discountResult['subtotal'];

            if (($coupon->order_minimum > 0 && $subtotal < $coupon->order_minimum) || ($coupon->order_maximum > 0 && $subtotal > $coupon->order_maximum)) {
                $this->addError('couponCode', "This coupon requires a subtotal between \$" . number_format($coupon->order_minimum, 2) . " and \$" . number_format($coupon->order_maximum, 2) . ".");
                return;
            }

            $totalWeight = 0;
            foreach ($items as $item) {
                $totalWeight += $item->item_qty * $item->item_weight;
            }
            if (($coupon->order_weight_min > 0 && $totalWeight < $coupon->order_weight_min) || ($coupon->order_weight_max > 0 && $totalWeight > $coupon->order_weight_max)) {
                $this->addError('couponCode', "This coupon requires order weight between {$coupon->order_weight_min} and {$coupon->order_weight_max}.");
                return;
            }

            $totalQty = $items->sum('item_qty');
            if (($coupon->order_qty_min > 0 && $totalQty < $coupon->order_qty_min) || ($coupon->order_qty_max > 0 && $totalQty > $coupon->order_qty_max)) {
                $this->addError('couponCode', "This coupon requires order item quantity between {$coupon->order_qty_min} and {$coupon->order_qty_max}.");
                return;
            }

            $userType = (Auth::check() && Auth::user()->isWholesale()) ? 2 : 1;
            if ($coupon->wholesale_only && $userType != 2) {
                $this->addError('couponCode', 'This coupon is only valid for wholesale customers.');
                return;
            }

            session()->put('coupon_code', $this->couponCode);
            session()->flash('status', "Coupon '{$this->couponCode}' applied successfully!");
            $this->couponCode = '';
            $this->dispatch('cart-updated');
        } else {
            $this->addError('couponCode', 'Invalid or expired coupon code.');
        }
    }

    public function removeCoupon(): void
    {
        session()->forget('coupon_code');
        session()->flash('status', 'Coupon code removed.');
        $this->dispatch('cart-updated');
    }

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        if ($this->requiresShipping) {
            $rules = array_merge($rules, [
                'shipping_address1' => 'required|string|max:255',
                'shipping_city' => 'required|string|max:255',
                'shopping_postalcode' => 'required|string|max:20',
                'shipping_country' => 'required|string|max:255',
                'shipping_countrycode' => 'required|string|max:5',
            ]);

            if ($this->shipping_countrycode === 'US') {
                $rules['shipping_state'] = 'required|string|exists:shipping_states,code,country_code,US';
            } elseif ($this->shipping_countrycode === 'CA') {
                $rules['shipping_state'] = 'required|string|exists:shipping_states,code,country_code,CA';
            } else {
                $rules['shipping_state'] = 'nullable|string|max:100';
            }
        }

        return $rules;
    }

    private function getCartSessionId(): string
    {
        return \App\Services\CartSessionService::getCartSessionId();
    }

    private function getCartQuery()
    {
        return \App\Services\CartSessionService::getCartQuery();
    }

    public function mount()
    {
        $items = $this->getCartQuery()->get();
        if ($items->count() === 0) {
            return redirect()->to('/');
        }

        $removed = \App\Services\InventoryCheckService::validateAndCleanCart($items);
        if (!empty($removed)) {
            $msg = \App\Services\InventoryCheckService::formatOutOfStockMessage($removed);
            session()->flash('error', $msg);
            return redirect()->route('shop.cart');
        }

        $this->loadCartDetails();

        // Pre-populate any saved session values from a previous step-1 visit
        if (session()->has('checkout_custom_data')) {
            $this->checkoutCustomData = session('checkout_custom_data', []);
        }
        if (session()->has('checkout_opt_in')) {
            $this->checkoutOptIn = (bool) session('checkout_opt_in');
        }

        // Note: auto-redirect for completed profiles is handled in booted()
        // so it fires on every Livewire lifecycle, not just the initial page load.
    }

    /**
     * Determine whether the current authenticated user has a complete-enough
     * profile to skip the checkout details form entirely.
     */
    private function canBypassCheckout(): bool
    {
        if (request()->has('edit') || !Auth::check()) {
            return false;
        }

        $user = Auth::user();

        if (!$this->requiresShipping) {
            // Download / service order — just needs name + email
            return !empty($user->name) && !empty($user->email);
        }

        // Physical order — needs a complete shipping address
        $hasBaseAddress = !empty($user->name) &&
                          !empty($user->email) &&
                          !empty($user->shipping_address1) &&
                          !empty($user->shipping_city) &&
                          !empty($user->shipping_countrycode) &&
                          !empty($user->shopping_postalcode);

        if (!$hasBaseAddress) {
            return false;
        }

        if (in_array($user->shipping_countrycode, ['US', 'CA']) && empty($user->shipping_state)) {
            return false;
        }

        return true;
    }

    /**
     * booted() runs on every Livewire request (initial + subsequent re-renders).
     * This ensures the bypass redirect fires even after loginUser() triggers
     * a component re-render, which does NOT re-run mount().
     */
    public function booted(): void
    {
        $items = $this->getCartQuery()->get();
        if ($items->count() === 0) {
            return;
        }

        $removed = \App\Services\InventoryCheckService::validateAndCleanCart($items);
        if (!empty($removed)) {
            $msg = \App\Services\InventoryCheckService::formatOutOfStockMessage($removed);
            session()->flash('error', $msg);
            $this->redirect(route('shop.cart'));
            return;
        }

        if ($this->canBypassCheckout()) {
            $user = Auth::user();
            // Associate current guest cart items with this user before leaving
            $this->getCartQuery()->update(['user_id' => $user->id]);
            $this->redirect(route('shop.checkout-review'), navigate: false);
        }
    }

    private function loadCartDetails(): void
    {
        $this->requiresShipping = $this->getCartQuery()->where('item_shippable', 1)->exists();

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->company = $user->company ?? '';
            $this->shipping_address1 = $user->shipping_address1 ?? '';
            $this->shipping_address2 = $user->shipping_address2 ?? '';
            $this->shipping_city = $user->shipping_city ?? '';
            $this->shopping_postalcode = $user->shopping_postalcode ?? '';
            $this->shipping_country = $user->shipping_country ?? 'United States';
            $this->shipping_countrycode = $user->shipping_countrycode ?? 'US';
            $this->shipping_state = $user->shipping_state ?? '';

            // Check if social user needs to fill in their shipping profile
            if (!$this->requiresShipping && !empty($user->provider)) {
                $hasAddress = !empty($user->shipping_address1) && !empty($user->shipping_city) && !empty($user->shopping_postalcode);
                if (!$hasAddress) {
                    $this->requiresShipping = true;
                    session()->flash('info', 'Please complete your shipping address details to proceed with checkout.');
                }
            }
        }
    }

    public function toggleLoginForm(): void
    {
        $this->showLoginForm = !$this->showLoginForm;
    }

    public function loginUser(): void
    {
        $this->validate([
            'loginEmail' => 'required|email',
            'loginPassword' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->loginEmail, 'password' => $this->loginPassword])) {
            // Associate guest cart to logged-in user
            $sessionId = $this->getCartSessionId();
            ShoppingCartLog::where('cart_log_session', $sessionId)
                ->where('user_id', 0)
                ->update(['user_id' => Auth::id()]);

            $this->loadCartDetails();
            $this->showLoginForm = false;
            $this->loginEmail = '';
            $this->loginPassword = '';

            $this->dispatch('cart-updated');

            // If profile is already complete, bypass the details form immediately.
            // canBypassCheckout() + booted() would catch this on the next render,
            // but doing it here avoids the one-render flash of the checkout form.
            if ($this->canBypassCheckout()) {
                $user = Auth::user();
                $this->getCartQuery()->update(['user_id' => $user->id]);
                $this->redirect(route('shop.checkout-review'), navigate: false);
                return;
            }

            session()->flash('status', 'Logged in successfully!');
        } else {
            $this->addError('login_error', 'Invalid email or password.');
        }
    }

    public function saveDetailsAndContinue()
    {
        $this->requiresShipping = $this->getCartQuery()->where('item_shippable', 1)->exists();
        $this->validate();

        // ── Validate checkout-position custom fields ───────────────────────────────────
        $checkoutFields = CheckoutCustomField::where('position', 'checkout')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        foreach ($checkoutFields as $idx => $cf) {
            if (!$cf->is_required) continue;
            $val = trim((string) ($this->checkoutCustomData[$idx] ?? ''));
            $error = $cf->required_error_message ?: 'This field is required.';
            if ($cf->required_type === 'email' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->addError("checkoutCustomData.{$idx}", $error);
                return;
            } elseif ($cf->required_type === 'numeric' && !is_numeric($val)) {
                $this->addError("checkoutCustomData.{$idx}", $error);
                return;
            } elseif (empty($val)) {
                $this->addError("checkoutCustomData.{$idx}", $error);
                return;
            }
        }

        // ── Store custom field values + opt-in in session for OrderReview ─────────────────
        // Build a labelled map so the order record is human-readable
        $labelledData = [];
        foreach ($checkoutFields as $idx => $cf) {
            $labelledData[$cf->label] = $this->checkoutCustomData[$idx] ?? null;
        }
        session(['checkout_custom_data' => $labelledData]);
        session(['checkout_opt_in'       => $this->checkoutOptIn]);

        $items = $this->getCartQuery()->get();

        if ($items->isEmpty()) {
            session()->flash('error', 'Your shopping cart is empty.');
            return;
        }

        // Resolve user account to link order details to
        if (Auth::check()) {
            $userId = Auth::id();
            Auth::user()->update([
                'company' => $this->company,
                'shipping_address1' => $this->shipping_address1,
                'shipping_address2' => $this->shipping_address2,
                'shipping_city' => $this->shipping_city,
                'shopping_postalcode' => $this->shopping_postalcode,
                'shipping_country' => $this->shipping_country,
                'shipping_countrycode' => $this->shipping_countrycode,
                'shipping_state' => $this->shipping_state,
            ]);
        } else {
            // Guest checkout: check if user exists by email
            $user = User::where('email', $this->email)->first();
            
            // Password logic — if the customer provided a real password, hash it.
            // If not, store the plain-text sentinel '[GUEST-USER]' so isGuest() can
            // reliably detect this account and prompt them to set a password later.
            $hasProvidedPassword = !empty($this->password);
            $userPassword = $hasProvidedPassword ? Hash::make($this->password) : \App\Models\User::GUEST_PASSWORD;

            if (!$user) {
                // Create a new user account
                $user = User::create([
                    'name'                => $this->name,
                    'email'               => $this->email,
                    'password'            => $userPassword,
                    'email_verified_at'   => null,
                    'role_id'             => \App\Enums\UserRole::User->value,
                    'company'             => $this->company,
                    'shipping_address1'   => $this->shipping_address1,
                    'shipping_address2'   => $this->shipping_address2,
                    'shipping_city'       => $this->shipping_city,
                    'shopping_postalcode' => $this->shopping_postalcode,
                    'shipping_country'    => $this->shipping_country,
                    'shipping_countrycode'=> $this->shipping_countrycode,
                    'shipping_state'      => $this->shipping_state,
                ]);
            } else {
                // If existing user is non-verified, update details
                if ($user->email_verified_at === null) {
                    $updateData = [
                        'name'                => $this->name,
                        'company'             => $this->company,
                        'shipping_address1'   => $this->shipping_address1,
                        'shipping_address2'   => $this->shipping_address2,
                        'shipping_city'       => $this->shipping_city,
                        'shopping_postalcode' => $this->shopping_postalcode,
                        'shipping_country'    => $this->shipping_country,
                        'shipping_countrycode'=> $this->shipping_countrycode,
                        'shipping_state'      => $this->shipping_state,
                    ];
                    if ($hasProvidedPassword) {
                        // Customer chose a real password this time — hash and save it
                        $updateData['password'] = $userPassword;
                    } elseif ($user->isGuest()) {
                        // Still a guest — keep the sentinel (already stored, no change needed)
                        // but ensure it's set in case a legacy random-hash record is being reused
                        $updateData['password'] = \App\Models\User::GUEST_PASSWORD;
                    }
                    $user->update($updateData);
                }
            }
            // Auto log in guest / newly created/updated unverified user so they can review their order securely
            Auth::login($user);
            $userId = $user->id;
        }


        // Associate current shopping cart records with the resolved user ID
        $this->getCartQuery()->update([
            'user_id' => $userId,
        ]);

        return redirect()->route('shop.checkout-review');
    }

    public function render(): View
    {
        $items = $this->getCartQuery()->get();
        $discountResult = \App\Services\DiscountService::applyDiscountsToCart($items, Auth::user());

        $countries = \Illuminate\Support\Facades\DB::table('shipping_countries')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        $us = $countries->firstWhere('code', 'US');
        $ca = $countries->firstWhere('code', 'CA');
        $gb = $countries->firstWhere('code', 'GB');

        $topCountries = collect(array_filter([$us, $ca, $gb]));
        $dropdownCountries = $topCountries->concat($countries);

        $states = [];
        if ($this->shipping_countrycode === 'US' || $this->shipping_countrycode === 'CA') {
            $states = \Illuminate\Support\Facades\DB::table('shipping_states')
                ->where('country_code', $this->shipping_countrycode)
                ->where('is_active', 1)
                ->orderBy('name', 'asc')
                ->get();
        }

        return view('livewire.checkout', [
            'items' => $discountResult['items'],
            'subtotal' => $discountResult['subtotal'],
            'discounts' => $discountResult['discounts'],
            'total_discount' => $discountResult['total_discount'],
            'total' => $discountResult['adjusted_subtotal'],
            'activeCoupon' => session()->get('coupon_code'),
            'countries' => $dropdownCountries,
            'states' => $states,
        ]);
    }
}
