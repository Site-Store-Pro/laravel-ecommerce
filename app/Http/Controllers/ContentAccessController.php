<?php

namespace App\Http\Controllers;

use App\Models\ContentAccessToken;
use Illuminate\Http\RedirectResponse;

class ContentAccessController extends Controller
{
    /**
     * Redeem a content access token.
     *
     * Validates the token, marks first access, grants CMS page purchase-gate
     * bypass via session, then redirects to the pre-resolved destination URL.
     *
     * No authentication is required — this supports guest purchasers who
     * receive the link in their order confirmation email.
     */
    public function redeem(string $token): RedirectResponse
    {
        $record = ContentAccessToken::where('token', $token)->first();

        if (!$record) {
            abort(404, 'Content access link not found.');
        }

        if ($record->isExpired()) {
            abort(403, 'This content access link has expired.');
        }

        // Record first access timestamp (non-blocking — don't fail if it errors)
        if ($record->accessed_at === null) {
            $record->update(['accessed_at' => now()]);
        }

        // Grant CMS product purchase gate access for this session so the
        // destination page (if gated by required_product_id) passes without login.
        if ($record->product_id) {
            $verified = session('verified_purchased_products', []);
            if (!in_array($record->product_id, $verified)) {
                $verified[] = $record->product_id;
                session(['verified_purchased_products' => $verified]);
            }
        }

        return redirect()->away($record->redirect_url);
    }
}
