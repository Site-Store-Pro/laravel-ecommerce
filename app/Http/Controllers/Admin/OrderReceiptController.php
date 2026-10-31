<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsSetting;
use App\Models\Order;
use App\Services\CurrencyService;
use Illuminate\View\View;

class OrderReceiptController extends Controller
{
    /**
     * Display a printer-friendly receipt for an order.
     */
    public function show(int $id): View
    {
        $order = Order::with([
            'user',
            'details.variant.product',
            'details.contentAccessToken',
            'payments.refunds',
            'refunds.payment',
            'statusList',
        ])->findOrFail($id);

        // Invoice / Receipt Header Settings managed in /admin/settings
        $companyName = trim((string) CmsSetting::get('invoice_company_name', ''));
        if ($companyName === '') {
            $companyName = CmsSetting::getSiteName();
        }
        $companyAddress = trim((string) CmsSetting::get('invoice_company_address', ''));
        $companyPhoneEmail = trim((string) CmsSetting::get('invoice_company_phone_email', ''));

        // Tax / VAT & Checkout display flags
        $showSubtotal = CmsSetting::isEnabled('checkout_show_subtotal', true);
        $showTax = CmsSetting::isEnabled('checkout_show_tax', true);
        $showShipping = CmsSetting::isEnabled('checkout_show_shipping', true);

        $buyerCountry = (string) ($order->user?->shipping_countrycode ?: CurrencyService::merchantCountry());
        $taxLabel = CurrencyService::taxLabel($buyerCountry);
        $isVatInclusive = CurrencyService::isVatInclusive();
        $isExport = CurrencyService::isCrossBorderExport($buyerCountry);
        $vatIncludedAmount = ($isVatInclusive && ! $isExport)
            ? CurrencyService::extractVat(
                (float) $order->order_subtotal - (float) $order->order_discounts,
                CurrencyService::merchantVatRate()
            )
            : 0.0;

        return view('admin.orders.receipt', [
            'order' => $order,
            'companyName' => $companyName,
            'companyAddress' => $companyAddress,
            'companyPhoneEmail' => $companyPhoneEmail,
            'showSubtotal' => $showSubtotal,
            'showTax' => $showTax,
            'showShipping' => $showShipping,
            'taxLabel' => $taxLabel,
            'isVatInclusive' => $isVatInclusive,
            'isExport' => $isExport,
            'vatIncludedAmount' => $vatIncludedAmount,
        ]);
    }
}
