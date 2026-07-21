<?php

namespace App\Plugins\Shipping;

use App\Models\Plugin;
use App\Plugins\Contracts\ShippingPlugin;
use App\Plugins\Support\ShippingContext;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * UPS Shipping Plugin — uses the UPS REST Rating API (OAuth 2.0)
 * Docs: https://developer.ups.com/api/reference/rating/business-rules
 */
class UpsPlugin implements ShippingPlugin
{
    // Maps our setting key => UPS service code
    protected array $serviceMap = [
        'UPS_Ground'                    => '03',
        'UPS_Ground_Saver'              => '70',
        'UPS_3_Day_Select'              => '12',
        'UPS_2nd_Day_Air'               => '02',
        'UPS_2nd_Day_Air_AM'            => '59',
        'UPS_Next_Day_Air_Saver'        => '13',
        'UPS_Next_Day_Air'              => '01',
        'UPS_Next_Day_Air_Early'        => '14',
        'UPS_International_Economy'     => '17',
        'UPS_International_Expedited'   => '08',
        'UPS_Worldwide_Express'         => '07',
        'UPS_Worldwide_Express_Plus'    => '54',
        'UPS_Worldwide_Saver'           => '65',
    ];

    // Human-readable labels for each service code
    protected array $serviceLabels = [
        '03' => 'UPS Ground',
        '70' => 'UPS Ground Saver',
        '12' => 'UPS 3 Day Select',
        '02' => 'UPS 2nd Day Air',
        '59' => 'UPS 2nd Day Air A.M.',
        '13' => 'UPS Next Day Air Saver',
        '01' => 'UPS Next Day Air',
        '14' => 'UPS Next Day Air Early',
        '17' => 'UPS Worldwide Economy',
        '08' => 'UPS Worldwide Expedited',
        '07' => 'UPS Worldwide Express',
        '54' => 'UPS Worldwide Express Plus',
        '65' => 'UPS Worldwide Saver',
    ];

    public function slug(): string
    {
        return 'ups-api';
    }

    public function name(): string
    {
        return 'Shipping Rates - UPS REST API (2026)';
    }

    public function getRates(ShippingContext $context, Plugin $plugin): array
    {
        $clientId     = $plugin->getSetting('UPS_Client_ID');
        $clientSecret = $plugin->getSetting('UPS_Client_Secret');
        $accountNumber = $plugin->getSetting('UPS_Account_Number');
        $fromZip      = $plugin->getSetting('UPS_From_Zip', $context->fromZip);
        $fromCountry  = $plugin->getSetting('UPS_From_Country', 'US');
        $markup       = (float) $plugin->getSetting('UPS_Markup', 0);

        if (empty($clientId) || empty($clientSecret)) {
            return [];
        }

        try {
            // Step 1: OAuth2 token
            $authResponse = Http::asForm()->post('https://onlinetools.ups.com/security/v1/oauth/token', [
                'grant_type' => 'client_credentials',
            ])->withBasicAuth($clientId, $clientSecret);

            if (!$authResponse->successful()) {
                Log::error('UPS Auth Error: ' . $authResponse->body());
                return [];
            }

            $token = $authResponse->json('access_token');
            if (!$token) {
                Log::error('UPS: No access token returned.');
                return [];
            }

            // Step 2: Build rate request — ShopRates to get all services at once
            $payload = [
                'RateRequest' => [
                    'Request' => [
                        'RequestOption' => 'Shop', // returns all available services
                        'TransactionReference' => [
                            'CustomerContext' => 'RateRequest',
                        ],
                    ],
                    'Shipment' => [
                        'Shipper' => [
                            'Address' => [
                                'PostalCode'  => $fromZip,
                                'CountryCode' => strtoupper($fromCountry),
                            ],
                            'ShipperNumber' => $accountNumber ?: '',
                        ],
                        'ShipTo' => [
                            'Address' => [
                                'PostalCode'  => $context->toZip,
                                'CountryCode' => strtoupper($context->toCountry),
                                'ResidentialAddressIndicator' => '',
                            ],
                        ],
                        'ShipFrom' => [
                            'Address' => [
                                'PostalCode'  => $fromZip,
                                'CountryCode' => strtoupper($fromCountry),
                            ],
                        ],
                        'Package' => [
                            'PackagingType' => ['Code' => '02'], // Customer Supplied Package
                            'PackageWeight' => [
                                'UnitOfMeasurement' => ['Code' => 'LBS'],
                                'Weight' => (string) max(0.1, round($context->weightLbs, 1)),
                            ],
                        ],
                    ],
                ],
            ];

            $rateResponse = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://onlinetools.ups.com/api/rating/v2205/Shop', $payload);

            if (!$rateResponse->successful()) {
                Log::error('UPS Rate API Error: ' . $rateResponse->body());
                return [];
            }

            $ratedShipments = $rateResponse->json('RateResponse.RatedShipment') ?? [];

            $rates = [];
            foreach ($ratedShipments as $shipment) {
                $serviceCode  = $shipment['Service']['Code'] ?? null;
                $totalCharges = $shipment['TotalCharges']['MonetaryValue'] ?? null;

                if (!$serviceCode || $totalCharges === null) {
                    continue;
                }

                // Find the setting key for this service code
                $settingKey = array_search($serviceCode, $this->serviceMap);
                if ($settingKey === false) {
                    continue;
                }

                // Check if this service is enabled in plugin settings
                if ($plugin->getSetting($settingKey) != '1') {
                    continue;
                }

                $label = $this->serviceLabels[$serviceCode] ?? 'UPS Service ' . $serviceCode;
                $businessDays = $shipment['GuaranteedDelivery']['BusinessDaysInTransit'] ?? null;

                $rates[] = [
                    'code'  => 'UPS_' . $serviceCode,
                    'label' => $label,
                    'rate'  => (float) $totalCharges + $markup,
                    'days'  => $businessDays ? (int) $businessDays : null,
                ];
            }

            return $rates;

        } catch (\Exception $e) {
            Log::error('UPS Plugin Exception: ' . $e->getMessage());
            return [];
        }
    }
}
