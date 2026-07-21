<?php

namespace App\Plugins\Shipping;

use App\Models\Plugin;
use App\Plugins\Contracts\ShippingPlugin;
use App\Plugins\Support\ShippingContext;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedExPlugin implements ShippingPlugin
{
    protected array $serviceMap = [
        'FedEx_Ground'                   => 'FEDEX_GROUND',
        'FedEx_Ground_Home_Delivery'     => 'GROUND_HOME_DELIVERY',
        'FedEx_Express_Saver'            => 'FEDEX_EXPRESS_SAVER',
        'FedEx_2_Day_Air'                => 'FEDEX_2_DAY',
        'FedEx_2_Day_Air_AM'             => 'FEDEX_2_DAY_AM',
        'FedEx_Priority_Overnight'       => 'PRIORITY_OVERNIGHT',
        'FedEx_Standard_Overnight'       => 'STANDARD_OVERNIGHT',
        'FedEx_First_Overnight'          => 'FIRST_OVERNIGHT',
        'FedEx_International_Priority'   => 'INTERNATIONAL_PRIORITY',
        'FedEx_International_Economy'    => 'INTERNATIONAL_ECONOMY',
        'FedEx_International_First'      => 'INTERNATIONAL_FIRST',
        'FedEx_1_Day_Freight'            => 'FEDEX_1_DAY_FREIGHT',
        'FedEx_2_Day_Freight'            => 'FEDEX_2_DAY_FREIGHT',
        'FedEx_3_Day_Freight'            => 'FEDEX_3_DAY_FREIGHT',
    ];

    public function slug(): string
    {
        return 'fedex-api';
    }

    public function name(): string
    {
        return 'Shipping Rates - FedEx REST API (2026)';
    }

    public function getRates(ShippingContext $context, Plugin $plugin): array
    {
        $account = $plugin->getSetting('FedEx_Account');
        $clientId = $plugin->getSetting('FedEx_Access_ID');
        $clientSecret = $plugin->getSetting('FedEx_Password');
        $markup = (float) $plugin->getSetting('FedEx_markup', 0);
        $naEnabled = $plugin->getSetting('FedEx_NorthAmerica') == '1';
        $intlEnabled = $plugin->getSetting('FedEx_International') == '1';

        if (empty($account) || empty($clientId) || empty($clientSecret)) {
            return [];
        }

        // Determine if we should quote based on region
        $isNa = in_array(strtoupper($context->toCountry), ['US', 'CA', 'MX']);
        if ($isNa && !$naEnabled) return [];
        if (!$isNa && !$intlEnabled) return [];

        try {
            // Get Auth Token
            $authResponse = Http::asForm()->post('https://apis.fedex.com/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ]);

            if (!$authResponse->successful()) {
                Log::error('FedEx Auth Error: ' . $authResponse->body());
                return [];
            }

            $token = $authResponse->json('access_token');

            if (!$token) {
                return [];
            }

            // Get Rates
            $payload = [
                'accountNumber' => ['value' => $account],
                'requestedShipment' => [
                    'shipper'    => ['address' => ['postalCode' => $context->fromZip, 'countryCode' => 'US']],
                    'recipient'  => ['address' => ['postalCode' => $context->toZip, 'countryCode' => $context->toCountry]],
                    'shipDateStamp' => now()->addDay()->format('Y-m-d'),
                    'pickupType'  => 'USE_SCHEDULED_PICKUP',
                    'serviceType' => '', // empty = get all rates
                    'rateRequestType' => ['PREFERRED'],
                    'requestedPackageLineItems' => [[
                        'weight' => ['units' => 'LB', 'value' => max(0.1, round($context->weightLbs, 1))],
                    ]],
                ],
            ];

            $rateResponse = Http::withToken($token)->post('https://apis.fedex.com/rate/v1/rates/quotes', $payload);

            if (!$rateResponse->successful()) {
                Log::error('FedEx Rate API Error: ' . $rateResponse->body());
                return [];
            }

            $rates = [];
            $rateReplyDetails = $rateResponse->json('output.rateReplyDetails') ?? [];

            foreach ($rateReplyDetails as $detail) {
                $serviceType = $detail['serviceType'] ?? null;
                $rateDetails = $detail['ratedShipmentDetails'][0] ?? null;
                $totalNetCharge = $rateDetails['totalNetCharge'] ?? null;
                
                if ($serviceType && $totalNetCharge !== null) {
                    $settingKey = array_search($serviceType, $this->serviceMap);
                    
                    if ($settingKey !== false) {
                        $isEnabled = $plugin->getSetting($settingKey) == '1';
                        if ($isEnabled) {
                            $label = ucwords(strtolower(str_replace('_', ' ', $serviceType)));
                            $label = str_replace('Fedex', 'FedEx', $label);

                            $rates[] = [
                                'code' => $serviceType,
                                'label' => $label,
                                'rate' => $totalNetCharge + $markup,
                                'days' => null, // Not always consistently provided without extra parsing
                            ];
                        }
                    }
                }
            }

            return $rates;

        } catch (\Exception $e) {
            Log::error('FedEx Plugin Exception: ' . $e->getMessage());
            return [];
        }
    }
}
