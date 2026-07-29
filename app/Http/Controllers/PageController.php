<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $page = CmsPage::withCurrentTranslations()->where('slug', $slug)->firstOrFail();

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

        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;

        // 2. Determine which gates are configured
        $productGateActive = (bool) $page->required_product_id;
        $codeGateActive    = $page->requires_code && !empty($page->access_code);

        // If neither gate is active, serve the page immediately
        if (!$productGateActive && !$codeGateActive) {
            return view('pages.cms', compact('page', 'userType'));
        }

        // ── Evaluate each gate independently ──────────────────────────────

        // A. Product purchase gate
        $productGatePassed = false;
        if ($productGateActive) {
            $verifiedPurchases = session('verified_purchased_products', []);
            if (in_array($page->required_product_id, $verifiedPurchases)) {
                $productGatePassed = true;
            } elseif (auth()->check()) {
                $productGatePassed = auth()->user()->hasPurchasedProduct($page->required_product_id);
                if ($productGatePassed) {
                    $verifiedPurchases[] = $page->required_product_id;
                    session(['verified_purchased_products' => $verifiedPurchases]);
                }
            }
        }

        // B. Access code gate
        $codeGatePassed = false;
        if ($codeGateActive) {
            $unlockedCodes  = session('unlocked_access_codes', []);
            $codeGatePassed = in_array($page->access_code, $unlockedCodes);
        }

        // ── Decision: pass if ANY active gate is satisfied ────────────────

        // Product gate only — and it passed
        if ($productGateActive && !$codeGateActive && $productGatePassed) {
            return view('pages.cms', compact('page', 'userType'));
        }

        // Code gate only — and it passed
        if ($codeGateActive && !$productGateActive && $codeGatePassed) {
            return view('pages.cms', compact('page', 'userType'));
        }

        // Both gates active — either one passing is sufficient
        if ($productGateActive && $codeGateActive && ($productGatePassed || $codeGatePassed)) {
            return view('pages.cms', compact('page', 'userType'));
        }

        // ── Access denied — determine the appropriate denial response ──────

        // Always store the intended URL so login redirects back here
        session(['url.intended' => route('page.show', $page->slug)]);

        // Product gate only — no code alternative — send to login
        if ($productGateActive && !$codeGateActive) {
            return redirect()->route('login')
                ->with('error', 'Viewing this page requires purchasing a specific product. Please log in to verify your purchase.');
        }

        // Code gate only — show the access code prompt (no login CTA needed)
        if ($codeGateActive && !$productGateActive) {
            return view('pages.cms-password', [
                'page'         => $page,
                'dualGate'     => false,
            ]);
        }

        // Both gates active — show the access code prompt WITH the login alternative
        return view('pages.cms-password', [
            'page'     => $page,
            'dualGate' => true,
        ]);
    }

    public function unlock(Request $request, int $id, RecaptchaService $recaptcha)
    {
        $page = CmsPage::findOrFail($id);

        $request->validate([
            'code' => 'required|string',
        ]);

        // reCAPTCHA v3 verification (skipped gracefully when keys are not configured)
        if (! $recaptcha->verify($request->input('_recaptcha_token', ''), 'page_unlock')) {
            return back()->withErrors(['code' => 'Verification failed. Please try again.']);
        }

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
