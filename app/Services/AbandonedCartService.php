<?php

namespace App\Services;

use App\Models\CmsSetting;
use App\Models\ShoppingCartLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AbandonedCartService
{
    /**
     * Web-triggered rate-limited check (runs automatically on web requests without needing server cron).
     */
    public static function checkWebTriggeredReminders(): void
    {
        if (\Illuminate\Support\Facades\Cache::add('abandoned_cart_web_check_lock', time(), 3600)) {
            try {
                self::processReminders();
            } catch (\Throwable $e) {
                Log::error("Web-triggered abandoned cart reminder error: " . $e->getMessage());
            }
        }
    }

    /**
     * Process and send abandoned cart reminder emails.
     *
     * @return array Summary stats of sent emails
     */
    public static function processReminders(): array
    {
        $sentDay1 = 0;
        $sentWeek1 = 0;

        $enabledDay1  = CmsSetting::isEnabled('enable_abandoned_cart_reminder_1', true);
        $enabledWeek1 = CmsSetting::isEnabled('enable_abandoned_cart_reminder_2', true);

        // 1. Process 24-Hour (Day 1) Abandoned Cart Reminders
        if ($enabledDay1) {
            $cutoffDay1 = now()->subHours(24);

            $day1Sessions = ShoppingCartLog::query()
                ->where('order_id', 0)
                ->where('created_at', '<=', $cutoffDay1)
                ->whereNull('abandoned_reminder_1_sent_at')
                ->where('cart_log_session', '!=', '')
                ->select('cart_log_session', 'user_id', 'guest_email')
                ->groupBy('cart_log_session', 'user_id', 'guest_email')
                ->get();

            foreach ($day1Sessions as $session) {
                $recipient = self::resolveRecipient($session->user_id, $session->guest_email);
                if (!$recipient['email']) {
                    continue;
                }

                $cartHtml = EmailTemplateService::renderCartItemsHtml($session->cart_log_session);
                if (empty($cartHtml)) {
                    continue;
                }

                $appUrl = rtrim(config('app.url'), '/');
                $checkoutUrl = $appUrl . route('shop.cart', ['cart_token' => $session->cart_log_session], false);

                $vars = [
                    'customer_name'    => $recipient['name'],
                    'cart_items_table' => $cartHtml,
                    'checkout_url'     => $checkoutUrl,
                    'app_name'         => config('app.name'),
                    'year'             => date('Y'),
                ];

                $sent = EmailTemplateService::sendEmail(
                    'abandoned_cart_reminder_1',
                    $recipient['email'],
                    $recipient['name'],
                    $vars
                );

                if ($sent) {
                    ShoppingCartLog::where('cart_log_session', $session->cart_log_session)
                        ->where('order_id', 0)
                        ->update(['abandoned_reminder_1_sent_at' => now()]);
                    $sentDay1++;
                }
            }
        }

        // 2. Process 7-Day (Week 1) Abandoned Cart Reminders
        if ($enabledWeek1) {
            $cutoffWeek1 = now()->subDays(7);

            $week1Sessions = ShoppingCartLog::query()
                ->where('order_id', 0)
                ->where('created_at', '<=', $cutoffWeek1)
                ->whereNotNull('abandoned_reminder_1_sent_at')
                ->whereNull('abandoned_reminder_2_sent_at')
                ->where('cart_log_session', '!=', '')
                ->select('cart_log_session', 'user_id', 'guest_email')
                ->groupBy('cart_log_session', 'user_id', 'guest_email')
                ->get();

            foreach ($week1Sessions as $session) {
                $recipient = self::resolveRecipient($session->user_id, $session->guest_email);
                if (!$recipient['email']) {
                    continue;
                }

                $cartHtml = EmailTemplateService::renderCartItemsHtml($session->cart_log_session);
                if (empty($cartHtml)) {
                    continue;
                }

                $appUrl = rtrim(config('app.url'), '/');
                $checkoutUrl = $appUrl . route('shop.cart', ['cart_token' => $session->cart_log_session], false);

                $vars = [
                    'customer_name'    => $recipient['name'],
                    'cart_items_table' => $cartHtml,
                    'checkout_url'     => $checkoutUrl,
                    'app_name'         => config('app.name'),
                    'year'             => date('Y'),
                ];

                $sent = EmailTemplateService::sendEmail(
                    'abandoned_cart_reminder_2',
                    $recipient['email'],
                    $recipient['name'],
                    $vars
                );

                if ($sent) {
                    ShoppingCartLog::where('cart_log_session', $session->cart_log_session)
                        ->where('order_id', 0)
                        ->update(['abandoned_reminder_2_sent_at' => now()]);
                    $sentWeek1++;
                }
            }
        }

        return [
            'sent_24h' => $sentDay1,
            'sent_7d'  => $sentWeek1,
        ];
    }

    /**
     * Resolve recipient email and display name from user_id or guest_email.
     */
    protected static function resolveRecipient(int $userId, ?string $guestEmail): array
    {
        if ($userId > 0) {
            $user = User::find($userId);
            if ($user && !empty($user->email)) {
                return [
                    'email' => $user->email,
                    'name'  => $user->name ?? 'Valued Customer',
                ];
            }
        }

        if (!empty($guestEmail) && filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
            return [
                'email' => $guestEmail,
                'name'  => 'Valued Customer',
            ];
        }

        return ['email' => null, 'name' => ''];
    }
}
