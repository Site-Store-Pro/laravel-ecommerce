<?php

namespace App\Services;

use App\Models\ShoppingCartLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CartSessionService
{
    public const COOKIE_NAME = 'cart_session_id';
    public const COOKIE_LIFETIME_MINUTES = 525600; // 1 year (365 days)

    /**
     * Get the active cart session ID from query parameters (abandoned cart links),
     * existing cookie, or generate a new UUID. Always queues/refreshes persistent cookie.
     */
    public static function getCartSessionId(): string
    {
        // 1. Check if token passed in URL query (e.g. from abandoned cart email link)
        $urlToken = request()->query('cart_token') ?: request()->query('cart_session');
        if (!empty($urlToken) && is_string($urlToken)) {
            $token = trim($urlToken);
            cookie()->queue(self::COOKIE_NAME, $token, self::COOKIE_LIFETIME_MINUTES);
            return $token;
        }

        // 2. Check existing cookie in request
        $cookieSessionId = request()->cookie(self::COOKIE_NAME);
        if (!empty($cookieSessionId) && is_string($cookieSessionId)) {
            $sessionId = trim($cookieSessionId);
            cookie()->queue(self::COOKIE_NAME, $sessionId, self::COOKIE_LIFETIME_MINUTES);
            return $sessionId;
        }

        // 3. Generate a new UUID if no session exists yet
        $newSessionId = (string) Str::uuid();
        cookie()->queue(self::COOKIE_NAME, $newSessionId, self::COOKIE_LIFETIME_MINUTES);

        return $newSessionId;
    }

    /**
     * Get active shopping cart query (order_id = 0) for current user / session.
     * Allows guest users to view retained carts matching their cookie/token
     * without forcing login.
     */
    public static function getCartQuery(?string $sessionId = null): Builder
    {
        $sessionId = $sessionId ?: self::getCartSessionId();
        $userId = auth()->id() ?? 0;

        return ShoppingCartLog::query()
            ->where('order_id', 0)
            ->where(function (Builder $query) use ($sessionId, $userId) {
                if ($userId > 0) {
                    $query->where('user_id', $userId)
                          ->orWhere('cart_log_session', $sessionId);
                } else {
                    $query->where('cart_log_session', $sessionId);
                }
            });
    }

    /**
     * Associate unassigned cart items with logged in user while preserving cart_log_session.
     */
    public static function associateCartOnLogin(int $userId, ?string $sessionId = null): void
    {
        if ($userId <= 0) {
            return;
        }

        $sessionId = $sessionId ?: self::getCartSessionId();
        ShoppingCartLog::where('cart_log_session', $sessionId)
            ->where('order_id', 0)
            ->where('user_id', 0)
            ->update(['user_id' => $userId]);
    }

    /**
     * Calculate total item count in active cart.
     */
    public static function getCartCount(?string $sessionId = null): float
    {
        return (float) self::getCartQuery($sessionId)->sum('item_qty');
    }
}
