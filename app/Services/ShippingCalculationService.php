<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ShippingCalculationService
{
    /**
     * Get all available shipping options (names and amounts) for the current cart/address.
     */
    public static function getAvailableOptions(float $subtotal, float $totalWeight, int $itemCount, string $countryCode, ?string $stateCode): array
    {
        $options = [];

        // 1. Retrieve config
        $config = DB::table('shipping_configurations')->first();
        if (!$config) {
            // Default fallback option
            return [
                [
                    'id' => 'grid_flat',
                    'name' => 'Standard Flat Rate Shipping',
                    'amount' => 10.00,
                ]
            ];
        }

        $isUsOrCa = ($countryCode === 'US' || $countryCode === 'CA');
        $useCustomList = $isUsOrCa ? $config->custom_ship_options_us : $config->custom_ship_options_int;

        if ($useCustomList) {
            // Load custom flat-rate list options
            $isInt = !$isUsOrCa;
            $flatRates = DB::table('shipping_flat_rates')
                ->where('is_international', $isInt)
                ->orderBy('sort_order', 'asc')
                ->get();

            foreach ($flatRates as $rate) {
                $options[] = [
                    'id' => 'custom_' . $rate->id,
                    'name' => $rate->name,
                    'amount' => (double)$rate->amount,
                ];
            }
        } else {
            // Load automatically calculated Grid Flat-Rate value
            $gridAmount = self::calculateGridAmount($subtotal, $totalWeight, $itemCount, $countryCode, $stateCode);
            $options[] = [
                'id' => 'grid_flat',
                'name' => 'Flat Rate Shipping',
                'amount' => $gridAmount,
            ];
        }

        // 2. Add real-time carriers mock rates if enabled
        if ($config->realtime_ups) {
            $options[] = [
                'id' => 'carrier_ups_ground',
                'name' => 'UPS Ground Delivery',
                'amount' => 15.00,
            ];
            $options[] = [
                'id' => 'carrier_ups_air',
                'name' => 'UPS Next Day Air',
                'amount' => 45.00,
            ];
        }
        if ($config->realtime_fedex) {
            $options[] = [
                'id' => 'carrier_fedex_ground',
                'name' => 'FedEx Home Delivery',
                'amount' => 14.50,
            ];
            $options[] = [
                'id' => 'carrier_fedex_express',
                'name' => 'FedEx Priority Overnight',
                'amount' => 32.00,
            ];
        }
        if ($config->realtime_usps) {
            $options[] = [
                'id' => 'carrier_usps_priority',
                'name' => 'USPS Priority Mail',
                'amount' => 8.95,
            ];
            $options[] = [
                'id' => 'carrier_usps_express',
                'name' => 'USPS Priority Express',
                'amount' => 26.50,
            ];
        }
        if ($config->realtime_pickup) {
            $options[] = [
                'id' => 'carrier_local_pickup',
                'name' => 'Local Pickup',
                'amount' => 0.00,
            ];
        }

        return $options;
    }

    /**
     * Parse flat-rate range matrix grid values and compute rate.
     */
    public static function calculateGridAmount(float $subtotal, float $totalWeight, int $itemCount, string $countryCode, ?string $stateCode): float
    {
        $valueType = 1;
        $rangeStr = '';

        if ($countryCode === 'US' || $countryCode === 'CA') {
            $state = DB::table('shipping_states')
                ->where('country_code', $countryCode)
                ->where('code', $stateCode)
                ->first();

            if ($state) {
                $valueType = (int)$state->flat_rate_value_type;
                $rangeStr = $state->flat_rate_range;
            }
        } else {
            $country = DB::table('shipping_countries')
                ->where('code', $countryCode)
                ->first();

            if ($country) {
                $valueType = (int)$country->flat_rate_value_type;
                $rangeStr = $country->flat_rate_range;
            }
        }

        if (empty($rangeStr)) {
            return 10.00; // default fallback
        }

        // Determine target metric based on filter type
        $targetValue = 0.00;
        if ($valueType === 1) {
            $targetValue = $totalWeight;
        } elseif ($valueType === 2) {
            $targetValue = $subtotal;
        } elseif ($valueType === 3) {
            $targetValue = (double)$itemCount;
        }

        $parts = explode(',', $rangeStr);
        $otherRate = 10.00;

        foreach ($parts as $part) {
            $part = trim($part);
            if (empty($part)) continue;

            if (stripos($part, 'Other=') === 0) {
                $otherRate = (double)substr($part, 6);
                continue;
            }

            if (preg_match('/^([\d.]+)-([\d.]+)=([\d.]+)$/', $part, $m)) {
                $min = (double)$m[1];
                $max = (double)$m[2];
                $rate = (double)$m[3];

                if ($valueType === 3) {
                    // Item Count: inclusive range X <= value <= Y
                    if ($targetValue >= $min && $targetValue <= $max) {
                        return $targetValue * $rate;
                    }
                } else {
                    // Weight or Subtotal: X <= value < Y
                    if ($targetValue >= $min && $targetValue < $max) {
                        return $rate;
                    }
                }
            }
        }

        // Fallback to "Other"
        if ($valueType === 3) {
            return $targetValue * $otherRate;
        }
        return $otherRate;
    }
}
