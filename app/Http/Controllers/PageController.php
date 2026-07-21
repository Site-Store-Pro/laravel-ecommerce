<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $page = CmsPage::where('slug', $slug)->firstOrFail();

        // Draft mode check: if page is not active, only admins can view
        if (!$page->is_active) {
            if (!auth()->check() || !auth()->user()->isEcommerceAdmin()) {
                abort(404);
            }
        }

        // 1. Expiration check
        if ($page->expires_at && $page->expires_at->isPast()) {
            abort(404);
        }

        // 2. Gating checks
        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;

        // A. Product purchase gate
        if ($page->required_product_id) {
            $hasPurchased = false;

            // Check session memory
            $verifiedPurchases = session('verified_purchased_products', []);
            if (in_array($page->required_product_id, $verifiedPurchases)) {
                $hasPurchased = true;
            } else {
                // Verify via DB if authenticated
                if (auth()->check()) {
                    $hasPurchased = auth()->user()->hasPurchasedProduct($page->required_product_id);
                    if ($hasPurchased) {
                        $verifiedPurchases[] = $page->required_product_id;
                        session(['verified_purchased_products' => $verifiedPurchases]);
                    }
                }
            }

            if (!$hasPurchased) {
                // Save original destination in session so they can redirect back after login/purchase
                session(['url.intended' => route('page.show', $page->slug)]);
                return redirect()->route('login')->with('error', 'Viewing this page requires purchasing a specific product.');
            }
        }

        // B. Access code gate
        if ($page->requires_code && !empty($page->access_code)) {
            $unlockedCodes = session('unlocked_access_codes', []);
            if (!in_array($page->access_code, $unlockedCodes)) {
                return view('pages.cms-password', compact('page'));
            }
        }

        return view('pages.cms', compact('page', 'userType'));
    }

    public function unlock(Request $request, int $id)
    {
        $page = CmsPage::findOrFail($id);

        $request->validate([
            'code' => 'required|string',
        ]);

        if ($request->code === $page->access_code) {
            $unlockedCodes = session('unlocked_access_codes', []);
            if (!in_array($page->access_code, $unlockedCodes)) {
                $unlockedCodes[] = $page->access_code;
                session(['unlocked_access_codes' => $unlockedCodes]);
            }
            return redirect()->route('page.show', $page->slug);
        }

        return back()->withErrors(['code' => 'Invalid access code. Please try again.']);
    }
}
