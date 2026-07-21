<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TaxCalculationService
{
    /**
     * Calculate Sales Tax or VAT for the order.
     *
     * For US/CA merchants (tax-exclusive):
     *   Returns the tax amount to be ADDED to the subtotal.
     *
     * For VAT-inclusive merchants (UK, AU, EU, etc.):
     *   If customer is in the same VAT zone  → returns 0 (tax already in price).
     *     The VAT component is extracted separately via extractedVat().
     *   If customer is cross-border export   → returns 0 (VAT stripped from price).
     *     The subtotal is already adjusted by adjustSubtotalForVat().
     */
    public static function calculateTax(float $subtotal, array $shippingAddress): float
    {
        // 1. Hook for third-party automated tax providers (e.g. Avalara)
        if (config('services.tax_provider') === 'avalara') {
            return self::calculateAvalaraTax($subtotal, $shippingAddress);
        }

        $countryCode  = strtoupper($shippingAddress['country_code'] ?? 'US');
        $stateCode    = $shippingAddress['state_code'] ?? '';
        $merchantIsVatInclusive = CurrencyService::isVatInclusive();

        // 2. VAT-inclusive merchants: tax is already baked into the stored price
        if ($merchantIsVatInclusive) {
            // For cross-border export (US/CA buyer), VAT has been stripped from
            // subtotal already in calculateTotals() — no additional tax to add.
            return 0.0;
        }

        // 3. US / Canada: retrieve state-level tax rate
        if ($countryCode === 'US' || $countryCode === 'CA') {
            $state = DB::table('shipping_states')
                ->where('country_code', $countryCode)
                ->where('code', $stateCode)
                ->where('is_active', 1)
                ->first();

            if ($state) {
                $rate = ($countryCode === 'CA')
                    ? ($state->vat_rate ?? 0)
                    : ($state->sales_tax_rate ?? 0);

                return $subtotal * ($rate / 100);
            }

            return 0.0;
        }

        // 4. International buyer from a US/CA merchant: retrieve country VAT rate
        $country = DB::table('shipping_countries')
            ->where('code', $countryCode)
            ->where('is_active', 1)
            ->first();

        if ($country && $country->charge_vat) {
            $rate = $country->custom_vat_rate ?? 0;
            return $subtotal * ($rate / 100);
        }

        return 0.0;
    }

    /**
     * For VAT-inclusive merchants: extract the VAT portion that is already
     * embedded in the subtotal (for display on the order summary).
     *
     * extractedVat = inclusiveSubtotal × rate / (100 + rate)
     *
     * Returns 0 when the merchant is tax-exclusive (US/CA) or VAT rate is 0.
     */
    public static function extractedVat(float $inclusiveSubtotal, string $customerCountryCode): float
    {
        if (!CurrencyService::isVatInclusive()) {
            return 0.0;
        }

        // Cross-border export: no VAT to show (already stripped)
        if (CurrencyService::isCrossBorderExport($customerCountryCode)) {
            return 0.0;
        }

        // Domestic or same-zone: show the embedded VAT amount
        $rate = CurrencyService::merchantVatRate();
        return CurrencyService::extractVat($inclusiveSubtotal, $rate);
    }

    /**
     * For VAT-inclusive merchants + cross-border export scenario:
     * Adjust (strip) VAT from the subtotal before placing the order.
     *
     * Returns the original subtotal if VAT stripping is not applicable.
     */
    public static function adjustSubtotalForVat(float $subtotal, string $customerCountryCode): float
    {
        if (!CurrencyService::isCrossBorderExport($customerCountryCode)) {
            return $subtotal;
        }

        $rate = CurrencyService::merchantVatRate();
        return CurrencyService::stripVat($subtotal, $rate);
    }

    /**
     * Placeholder method for third-party Avalara integration.
     */
    protected static function calculateAvalaraTax(float $subtotal, array $shippingAddress): float
    {
        // To plug in Avalara:
        // 1. Initialize Avalara Client using API keys from config/services.php
        // 2. Format a transaction request with line item details and shipping address
        // 3. Request rate and return response total tax amount
        // E.g.:
        // $taxService = new \Avalara\TransactionBuilder($client, "CompanyCode", \Avalara\DocumentType::C_SALESORDER, "CustomerCode");
        // $taxService->withAddress("ShipFrom", ...)->withAddress("ShipTo", ...)->withLine($subtotal, 1, "ProductCode", "TaxCode");
        // $res = $taxService->create();
        // return (double)$res->totalTax;

        return 0.00;
    }
}
