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
}
