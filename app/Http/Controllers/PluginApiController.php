<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Http\JsonResponse;

class PluginApiController extends Controller
{
    /**
     * Return active display-type plugins for the TinyMCE Insert Plugin button.
     */
    public function listDisplay(): JsonResponse
    {
        $displayTypes = ['display', 'quickcart', 'searchbar', 'checkout-features', 'product-features'];

        $plugins = Plugin::whereIn('type', $displayTypes)
            ->where('activation_status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'shortcode', 'type', 'description'])
            ->map(fn ($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'shortcode'   => $p->shortcode,
                'type'        => $p->type,
                'description' => $p->description,
            ]);

        return response()->json($plugins);
    }

    /**
     * Live search — translation-aware JSON endpoint (web route, session available).
     *
     * The SetLocale middleware resolves the visitor's active language and stores
     * it in the service container under 'current.language' before this action runs,
     * so language detection from session works correctly here.
     */
    public function liveSearchApi(\Illuminate\Http\Request $request): JsonResponse
    {
        $q = trim($request->query('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        // Resolve active and default language IDs.
        // 'current.language' is bound by the SetLocale middleware on every web request.
        $currentLang   = app()->bound('current.language') ? app('current.language') : null;
        $defaultLang   = \App\Models\Language::getDefault();
        $langId        = $currentLang?->id ?? $defaultLang->id;
        $defaultLangId = $defaultLang->id;

        $results = (new \App\Services\LiveSearchService())->search($q, $langId, $defaultLangId);

        // Limit the inline dropdown to 15 entries
        return response()->json(array_slice($results, 0, 15));
    }
}
