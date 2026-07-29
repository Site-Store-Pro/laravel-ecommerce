<?php

namespace App\Http\Controllers;

use App\Models\CmsPagesCategory;
use Illuminate\Http\Request;

class CmsCategoryPageController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $category = CmsPagesCategory::where('slug', $slug)->firstOrFail();

        $pagesQuery = $category->pages()
            ->withCurrentTranslations()
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->where('requires_code', 0)
            ->whereNull('required_product_id');

        // Sorted by default on date published (created_at DESC) and custom_sorting override (secondary sort ASC)
        $pages = $pagesQuery->orderBy('created_at', 'desc')
            ->orderBy('custom_sorting', 'asc')
            ->get();

        return view('pages.cms-category', compact('category', 'pages'));
    }
}
