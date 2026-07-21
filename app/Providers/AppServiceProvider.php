<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env('custom_login_security')) {
            $this->app->singleton('hash', function ($app) {
                return new \App\Services\CustomHashManager($app);
            });
        }
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load AI credentials as global variables
        $GLOBALS['AI_PROVIDER'] = env('AI_PROVIDER');
        $GLOBALS['AI_PROVIDER_API_KEY'] = env('AI_PROVIDER_API_KEY');
        $GLOBALS['AI_PROVIDER_ACCOUNT_ID'] = env('AI_PROVIDER_ACCOUNT_ID');
        $GLOBALS['OPENAI_API_KEY'] = env('OPENAI_API_KEY');

        // Dynamically include custom AI response files if they exist
        $ticketPath = app_path('Includes/ai_ticket_response.php');
        if (file_exists($ticketPath)) {
            include_once $ticketPath;
        }

        $kbPath = app_path('Includes/ai_kb_article_content.php');
        if (file_exists($kbPath)) {
            include_once $kbPath;
        }

        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });

        // Apply site timezone from CMS settings (admin-configurable).
        // Wrapped in a try/catch so the app still boots during fresh installs
        // before the cms_settings table has been migrated.
        try {
            $tz = \Illuminate\Support\Facades\DB::table('cms_settings')
                ->where('key', 'timezone')
                ->value('value');

            if ($tz && in_array($tz, \DateTimeZone::listIdentifiers(), true)) {
                config(['app.timezone' => $tz]);
                date_default_timezone_set($tz);
            }
        } catch (\Throwable) {
            // Table may not exist yet on a fresh install — silently ignore.
        }
    }
}