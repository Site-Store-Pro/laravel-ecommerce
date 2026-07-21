<?php

namespace App\Http\Controllers;

use App\Models\CmsPagesTag;
use Illuminate\Http\Request;

class CmsTagPageController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $tag = CmsPagesTag::where('slug', $slug)->firstOrFail();

        // Only list pages that are active and NOT gated for security (no access code and no product purchase required)
        $pages = $tag->pages()
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->where('requires_code', 0)
            ->whereNull('required_product_id')
            ->orderBy('created_at', 'desc')
            ->orderBy('custom_sorting', 'asc')
            ->get();

        return view('pages.cms-tag', compact('tag', 'pages'));
    }
}
