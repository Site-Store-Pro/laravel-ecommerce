<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeaderFooterPreviewController extends Controller
{
    public function __invoke(Request $request)
    {
        // Restrict to Admin
        abort_unless(auth()->check() && auth()->user()->role_id === UserRole::Admin, 403, 'Unauthorized');

        $device = $request->query('device', 'desktop');
        if (!in_array($device, ['desktop', 'tablet', 'mobile'])) {
            $device = 'desktop';
        }

        $tab = $request->query('tab', 'header');
        if (!in_array($tab, ['header', 'footer', 'full_preview'])) {
            $tab = 'header';
        }

        return view('admin.header-footer-preview-frame', [
            'device' => $device,
            'tab'    => $tab,
        ]);
    }
}
