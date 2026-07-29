<?php

namespace App\Providers;

use App\Services\LanguageService;
use App\Services\SiteLabelService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load global helper functions (siteLabel, etc.)
        require_once app_path('helpers.php');

        // LanguageService as a request-scoped singleton so the session-resolved
        // language is consistent across SetLocale middleware, HasTranslations
        // getAttribute(), LanguageSwitcher, and all other callers per request.
        $this->app->singleton(LanguageService::class);

        // SiteLabelService as a singleton so the runtime[] in-process map is
        // populated once per request — subsequent @label() calls are O(1) array lookups.
        $this->app->singleton(SiteLabelService::class);

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
        // @label('key', 'fallback') — outputs HTML-escaped site label value.
        // Used in all public-facing blades to replace hardcoded English strings.
        Blade::directive('label', function (string $expression): string {
            return "<?php echo e(siteLabel($expression)); ?>";
        });

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