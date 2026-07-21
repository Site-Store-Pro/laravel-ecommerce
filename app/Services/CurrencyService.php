<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * CurrencyService
 *
 * Centralises all currency formatting so that swapping the store's currency
 * (e.g. USD → GBP) automatically propagates to every price on the site.
 *
 * All values are loaded from the shipping_configurations row (id=1) and
 * cached for 60 minutes. Calling Cache::forget('currency_config') after
 * an admin update will force a reload on the next request.
 */
class CurrencyService
{
    private const CACHE_KEY = 'currency_config';
    private const CACHE_TTL = 60; // minutes

    // Countries that are NOT US/CA and therefore use VAT-inclusive pricing
    private const VAT_INCLUSIVE_COUNTRY_CODES = [
        'GB', 'AU', 'NZ', 'DE', 'FR', 'IT', 'ES', 'PT', 'NL', 'BE',
        'AT', 'SE', 'NO', 'DK', 'FI', 'IE', 'PL', 'CZ', 'HU', 'RO',
        'BG', 'HR', 'SK', 'SI', 'LT', 'LV', 'EE', 'LU', 'MT', 'CY',
        'CH', 'IS', 'GR', 'RS', 'BA', 'MK', 'AL', 'ME', 'XK',
        'IN', 'SG', 'MY', 'TH', 'VN', 'PH', 'ID', 'JP', 'KR', 'ZA',
        'NG', 'KE', 'GH', 'EG', 'MA', 'TN',
        'MX', 'BR', 'AR', 'CL', 'CO', 'PE', 'UY', 'EC',
        'AE', 'SA', 'QA', 'KW', 'BH', 'OM',
    ];

    /**
     * Load and cache the full config row.
     */
    private static function config(): object
    {
        $data = Cache::remember(self::CACHE_KEY, self::CACHE_TTL * 60, function () {
            $row = DB::table('shipping_configurations')->where('id', 1)->first();
            if (!$row) {
                return [
                    'merchant_country_code' => 'US',
                    'currency_code'         => 'USD',
                    'currency_symbol'       => '$',
                    'vat_inclusive_pricing' => false,
                ];
            }
            // Cast to array before caching — stdClass objects can produce
            // "incomplete object" errors when unserialized by some cache drivers.
            return (array) $row;
        });

        return (object) $data;
    }

    /**
     * Flush the cached config (call after admin saves shipping config).
     */
    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    // ────────────────────────────────────────────────────────────────────────
    // Basic accessors
    // ────────────────────────────────────────────────────────────────────────

    public static function symbol(): string
    {
        return self::config()->currency_symbol ?? '$';
    }

    public static function code(): string
    {
        return self::config()->currency_code ?? 'USD';
    }

    public static function merchantCountry(): string
    {
        return strtoupper(self::config()->merchant_country_code ?? 'US');
    }

    /**
     * True when the merchant's home country applies VAT-inclusive pricing
     * (i.e. NOT US and NOT Canada).
     */
    public static function isVatInclusive(): bool
    {
        $country = self::merchantCountry();
        return !in_array($country, ['US', 'CA'], true);
    }

    /**
     * The merchant's standard VAT rate (%), read from shipping_countries.
     * Returns 0 if the country has no VAT configured.
     */
    public static function merchantVatRate(): float
    {
        $country = self::merchantCountry();
        if (in_array($country, ['US', 'CA'], true)) {
            return 0.0;
        }

        $row = DB::table('shipping_countries')
            ->where('code', $country)
            ->where('is_active', 1)
            ->where('charge_vat', 1)
            ->first();

        return $row ? (float)($row->custom_vat_rate ?? 0) : 0.0;
    }

    // ────────────────────────────────────────────────────────────────────────
    // Formatting helpers
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Format a numeric amount with the configured currency symbol prefixed.
     * e.g.  format(19.99) → "$19.99" or "£19.99"
     */
    public static function format(float $amount): string
    {
        return self::symbol() . number_format($amount, 2);
    }

    /**
     * Format with a forced sign-prefix (for negatives/discounts).
     * e.g.  formatSigned(-5.00) → "-$5.00"
     */
    public static function formatSigned(float $amount): string
    {
        if ($amount < 0) {
            return '-' . self::symbol() . number_format(abs($amount), 2);
        }
        return self::symbol() . number_format($amount, 2);
    }

    // ────────────────────────────────────────────────────────────────────────
    // Tax label helpers
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Return the correct tax label for the customer's country code.
     *
     * US  → "Sales Tax"
     * CA  → "GST/HST"
     * *   → "VAT"
     */
    public static function taxLabel(string $customerCountryCode): string
    {
        return match (strtoupper($customerCountryCode)) {
            'US'    => 'Sales Tax',
            'CA'    => 'GST/HST',
            default => 'VAT',
        };
    }

    // ────────────────────────────────────────────────────────────────────────
    // VAT-inclusive pricing helpers
    // ────────────────────────────────────────────────────────────────────────

    /**
     * For a VAT-inclusive merchant: compute the VAT-inclusive display price
     * from a stored (ex-VAT) base price.
     *
     * storedPrice × (1 + rate/100)
     */
    public static function applyVatToPrice(float $basePrice): float
    {
        $rate = self::merchantVatRate();
        if ($rate <= 0) {
            return $basePrice;
        }
        return $basePrice * (1 + $rate / 100);
    }

    /**
     * Extract the VAT component from a VAT-inclusive price.
     *
     * vatAmount = inclusivePrice × rate / (100 + rate)
     */
    public static function extractVat(float $inclusivePrice, float $rate): float
    {
        if ($rate <= 0) {
            return 0.0;
        }
        return $inclusivePrice * $rate / (100 + $rate);
    }

    /**
     * For cross-border export (e.g. US buyer from UK merchant):
     * Strip VAT from an inclusive price to get the net (ex-VAT) amount.
     *
     * netPrice = inclusivePrice / (1 + rate/100)
     */
    public static function stripVat(float $inclusivePrice, float $rate): float
    {
        if ($rate <= 0) {
            return $inclusivePrice;
        }
        return $inclusivePrice / (1 + $rate / 100);
    }

    /**
     * Determine if a customer from $customerCountry buying from the merchant
     * should have VAT stripped (cross-border export scenario).
     *
     * Rule: VAT is stripped when the merchant IS VAT-inclusive
     *       but the customer is in a non-VAT country (US or CA).
     */
    public static function isCrossBorderExport(string $customerCountryCode): bool
    {
        if (!self::isVatInclusive()) {
            return false; // US/CA merchant — never strip
        }
        return in_array(strtoupper($customerCountryCode), ['US', 'CA'], true);
    }
}
