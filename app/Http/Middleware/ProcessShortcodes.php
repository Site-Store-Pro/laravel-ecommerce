<?php

namespace App\Http\Middleware;

use App\Services\ListMenuRenderer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProcessShortcodes
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only process successful HTML responses
        if ($response->isRedirection() 
            || !$response->headers->has('Content-Type') 
            || !str_contains($response->headers->get('Content-Type'), 'text/html')
        ) {
            return $response;
        }

        // Do not process shortcodes inside the admin area (e.g. plugins list page)
        if ($request->is('admin*')) {
            return $response;
        }

        $content = $response->getContent();
        if (empty($content)) {
            return $response;
        }

        // 1. Process all [list:id] shortcodes
        $content = preg_replace_callback('/\[list:(\d+)\]/i', function (array $matches) {
            $id = (int) $matches[1];
            return ListMenuRenderer::render($id);
        }, $content);

        // 2. Process any remaining [page:id], [product:id], [category:id], [brand:id] shortcodes placed outside list menus (e.g. in page content or footer)
        $content = ListMenuRenderer::parseItemContent($content);

        $response->setContent($content);

        return $response;
    }
}
