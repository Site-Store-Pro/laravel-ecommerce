<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /*
         * Trust reverse proxies (Cloudflare, load balancers, etc.)
         * so Laravel correctly detects HTTPS requests.
         *
         * Without this, signed URLs (Livewire uploads, password resets,
         * email verification, etc.) may incorrectly generate http:// URLs
         * when the original request was HTTPS.
         */
        $middleware->trustProxies(at: '*');

        $middleware->encryptCookies(except: [
            'frontend_theme',
            'theme_mode',
            'app_theme',
            'cart_session_id',
            'app_language',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\ProcessShortcodes::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'staff' => \App\Http\Middleware\EnsureUserIsStaff::class,
            'ticket_manager' => \App\Http\Middleware\EnsureUserIsTicketManager::class,
            'order_processor' => \App\Http\Middleware\EnsureUserIsOrderProcessor::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
            'admin/cms-pages/upload-image',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();