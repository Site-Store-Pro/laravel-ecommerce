<?php

namespace App\Plugins\Shipping;

use App\Models\Plugin;
use App\Plugins\Contracts\ShippingPlugin;
use App\Plugins\Support\ShippingContext;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * USPS Shipping Plugin — uses the USPS REST API v3 (OAuth 2.0)
 * Docs: https://developer.usps.com/api/81 (Prices API v3)
 *
 * Note: USPS legacy XML Web Tools were deprecated 2026-01-15.
 * This plugin uses the new USPS APIs portal: https://developer.usps.com
 */
class UspsPlugin implements ShippingPlugin
{
    // Maps our setting key => USPS mail class
    protected array $serviceMap = [
        'USPS_Priority_Mail'               => 'PRIORITY_MAIL',
        'USPS_Priority_Mail_Express'       => 'PRIORITY_MAIL_EXPRESS',
        'USPS_Ground_Advantage'            => 'USPS_GROUND_ADVANTAGE',
        'USPS_Parcel_Select'               => 'PARCEL_SELECT',
        'USPS_Priority_Mail_Cubic'         => 'PRIORITY_MAIL_CUBIC',
        'USPS_Priority_Mail_Express_Intl'  => 'PRIORITY_MAIL_EXPRESS_INTERNATIONAL',
        'USPS_Priority_Mail_Intl'          => 'PRIORITY_MAIL_INTERNATIONAL',
        'USPS_First_Class_Package_Intl'    => 'FIRST-CLASS_PACKAGE_INTERNATIONAL_SERVICE',
    ];

    protected array $serviceLabels = [
        'PRIORITY_MAIL'                             => 'USPS Priority Mail',
        'PRIORITY_MAIL_EXPRESS'                     => 'USPS Priority Mail Express',
        'USPS_GROUND_ADVANTAGE'                     => 'USPS Ground Advantage',
        'PARCEL_SELECT'                             => 'USPS Parcel Select',
        'PRIORITY_MAIL_CUBIC'                       => 'USPS Priority Mail Cubic',
        'PRIORITY_MAIL_EXPRESS_INTERNATIONAL'       => 'USPS Priority Mail Express International',
        'PRIORITY_MAIL_INTERNATIONAL'               => 'USPS Priority Mail International',
        'FIRST-CLASS_PACKAGE_INTERNATIONAL_SERVICE' => 'USPS First-Class Package International',
    ];

    // Transit days by mail class (approximate, for display only)
    protected array $transitDays = [
        'PRIORITY_MAIL'                             => 2,
        'PRIORITY_MAIL_EXPRESS'                     => 1,
        'USPS_GROUND_ADVANTAGE'                     => 5,
        'PARCEL_SELECT'                             => 7,
        'PRIORITY_MAIL_CUBIC'                       => 2,
        'PRIORITY_MAIL_EXPRESS_INTERNATIONAL'       => 3,
        'PRIORITY_MAIL_INTERNATIONAL'               => 10,
        'FIRST-CLASS_PACKAGE_INTERNATIONAL_SERVICE' => 14,
    ];

    public function slug(): string
    {
        return 'usps-api';
    }

    public function name(): string
    {
        return 'Shipping Rates - USPS REST API v3 (2026)';
    }

    public function getRates(ShippingContext $context, Plugin $plugin): array
    {
        $clientId     = $plugin->getSetting('USPS_Client_ID');
        $clientSecret = $plugin->getSetting('USPS_Client_Secret');
        $fromZip      = $plugin->getSetting('USPS_From_Zip', $context->fromZip);
        $markup       = (float) $plugin->getSetting('USPS_Markup', 0);

        if (empty($clientId) || empty($clientSecret)) {
            return [];
        }

        // USPS API only supports domestic US shipments with the Prices v3 endpoint
        // International is available via separate Prices endpoint
        $isDomestic = strtoupper($context->toCountry) === 'US';

        try {
            // Step 1: OAuth2 token
            $authResponse = Http::asForm()->post('https://api.usps.com/oauth2/v3/token', [
                'grant_type'    => 'client_credentials',
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
            ]);

            if (!$authResponse->successful()) {
                Log::error('USPS Auth Error: ' . $authResponse->body());
                return [];
            }

            $token = $authResponse->json('access_token');
            if (!$token) {
                Log::error('USPS: No access token returned.');
                return [];
            }

            $rates = [];

            if ($isDomestic) {
                $rates = $this->getDomesticRates($token, $context, $plugin, $fromZip, $markup);
            } else {
                $rates = $this->getInternationalRates($token, $context, $plugin, $fromZip, $markup);
            }

            return $rates;

        } catch (\Exception $e) {
            Log::error('USPS Plugin Exception: ' . $e->getMessage());
            return [];
        }
    }

    protected function getDomesticRates(string $token, ShippingContext $context, Plugin $plugin, string $fromZip, float $markup): array
    {
        $weightLbs = max(0.01, round((float)$context->weightLbs, 3));

        // Domestic services to quote
        $domesticServices = [
            'USPS_Priority_Mail',
            'USPS_Priority_Mail_Express',
            'USPS_Ground_Advantage',
            'USPS_Parcel_Select',
            'USPS_Priority_Mail_Cubic',
        ];

        $rates = [];

        foreach ($domesticServices as $settingKey) {
            $val = $plugin->getSetting($settingKey);
            if (!in_array(strtolower(trim((string)$val)), ['1', 'true', 'on', 'yes'], true)) {
                continue;
            }

            $mailClass = $this->serviceMap[$settingKey] ?? null;
            if (!$mailClass) continue;

            try {
                $response = Http::withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post('https://api.usps.com/prices/v3/total-rates/search', [
                        'originZIPCode'      => $fromZip,
                        'destinationZIPCode' => $context->toZip,
                        'weight'             => $weightLbs,
                        'length'             => 6,
                        'width'              => 4,
                        'height'             => 1,
                        'mailClass'          => $mailClass,
                        'processingCategory' => 'MACHINABLE',
                        'destinationEntryFacilityType' => 'NONE',
                        'rateIndicator'      => 'SP',
                        'priceType'          => 'COMMERCIAL',
                    ]);

                if ($response->successful()) {
                    $totalPrice = $this->extractRateFromResponse($response->json());

                    if ($totalPrice !== null && (float)$totalPrice > 0) {
                        $label = $this->serviceLabels[$mailClass] ?? $settingKey;
                        $rates[] = [
                            'code'  => 'USPS_' . $mailClass,
                            'label' => $label,
                            'rate'  => (float) $totalPrice + $markup,
                            'days'  => $this->transitDays[$mailClass] ?? null,
                        ];
                    }
                } else {
                    Log::debug('USPS rate skip for ' . $mailClass . ': ' . $response->status() . ' - ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::debug('USPS service error for ' . $mailClass . ': ' . $e->getMessage());
            }
        }

        return $rates;
    }

    protected function getInternationalRates(string $token, ShippingContext $context, Plugin $plugin, string $fromZip, float $markup): array
    {
        $weightLbs = max(0.01, round((float)$context->weightLbs, 3));

        $intlServices = [
            'USPS_Priority_Mail_Express_Intl' => 'PRIORITY_MAIL_EXPRESS_INTERNATIONAL',
            'USPS_Priority_Mail_Intl'         => 'PRIORITY_MAIL_INTERNATIONAL',
            'USPS_First_Class_Package_Intl'   => 'FIRST-CLASS_PACKAGE_INTERNATIONAL_SERVICE',
        ];

        $rates = [];

        foreach ($intlServices as $settingKey => $mailClass) {
            $val = $plugin->getSetting($settingKey);
            if (!in_array(strtolower(trim((string)$val)), ['1', 'true', 'on', 'yes'], true)) {
                continue;
            }

            try {
                $response = Http::withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post('https://api.usps.com/international-prices/v3/total-rates/search', [
                        'originZIPCode'        => $fromZip,
                        'foreignPostalCode'    => $context->toZip,
                        'destinationCountryCode' => strtoupper($context->toCountry),
                        'weight'               => $weightLbs,
                        'length'               => 6,
                        'width'                => 4,
                        'height'               => 1,
                        'mailClass'            => $mailClass,
                        'processingCategory'   => 'MACHINABLE',
                        'rateIndicator'        => 'SP',
                        'priceType'            => 'COMMERCIAL',
                    ]);

                if ($response->successful()) {
                    $totalPrice = $this->extractRateFromResponse($response->json());

                    if ($totalPrice !== null && (float)$totalPrice > 0) {
                        $label = $this->serviceLabels[$mailClass] ?? $settingKey;
                        $rates[] = [
                            'code'  => 'USPS_INTL_' . $mailClass,
                            'label' => $label,
                            'rate'  => (float) $totalPrice + $markup,
                            'days'  => $this->transitDays[$mailClass] ?? null,
                        ];
                    }
                } else {
                    Log::debug('USPS intl rate skip for ' . $mailClass . ': ' . $response->status() . ' - ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::debug('USPS intl service error for ' . $mailClass . ': ' . $e->getMessage());
            }
        }

        return $rates;
    }

    /**
     * Extract the standard single-piece package price from USPS Prices API v3 response.
     */
    protected function extractRateFromResponse(array $data): ?float
    {
        if (empty($data['rateOptions'])) {
            $fallback = $data['totalBasePrice'] ?? $data['price'] ?? null;
            return $fallback !== null ? (float)$fallback : null;
        }

        // 1. Look for standard Single-Piece rate (SP for standard, PA for Express)
        foreach ($data['rateOptions'] as $option) {
            $firstRate = $option['rates'][0] ?? [];
            $indicator = $firstRate['rateIndicator'] ?? '';
            $desc = strtolower($firstRate['description'] ?? '');
            if (in_array($indicator, ['SP', 'PA'], true) && !str_contains($desc, 'pmod') && !str_contains($desc, 'ddu') && !str_contains($desc, 'dhub')) {
                $price = $option['totalBasePrice'] ?? $firstRate['price'] ?? null;
                if ($price !== null && (float)$price > 0) {
                    return (float)$price;
                }
            }
        }

        // 2. Look for any standard single-piece description
        foreach ($data['rateOptions'] as $option) {
            $firstRate = $option['rates'][0] ?? [];
            $desc = strtolower($firstRate['description'] ?? '');
            if (str_contains($desc, 'single-piece') && !str_contains($desc, 'pmod')) {
                $price = $option['totalBasePrice'] ?? $firstRate['price'] ?? null;
                if ($price !== null && (float)$price > 0) {
                    return (float)$price;
                }
            }
        }

        // 3. Fallback: lowest valid price in rateOptions
        $prices = [];
        foreach ($data['rateOptions'] as $option) {
            $price = $option['totalBasePrice'] ?? $option['rates'][0]['price'] ?? null;
            if ($price !== null && (float)$price > 0) {
                $prices[] = (float)$price;
            }
        }

        return !empty($prices) ? min($prices) : null;
    }
}
