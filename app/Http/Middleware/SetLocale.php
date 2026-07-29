<?php

namespace App\Http\Middleware;

use App\Services\LanguageService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(private LanguageService $languageService) {}

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $language = $this->languageService->current();
            app()->setLocale($language->code);

            // Make available globally
            app()->instance('current.language', $language);
        } catch (\Throwable) {
            // Silently fail — don't break the site if languages table doesn't exist yet
        }

        return $next($request);
    }
}
