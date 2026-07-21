<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class HandlingChargeService
{
    /**
     * Compute handling charges by evaluating active rules against cart metrics.
     */
    public static function calculateHandlingCharge(float $subtotal, float $totalWeight, int $itemCount, string $countryCode): float
    {
        // Retrieve all active handling rules
        $charges = DB::table('handling_charges')
            ->where('is_active', 1)
            ->get();

        $totalHandling = 0.00;

        foreach ($charges as $charge) {
            $meetsCriteria = true;

            // 1. Min Subtotal Surcharge
            if ($charge->min_subtotal !== null && $subtotal < (double)$charge->min_subtotal) {
                $meetsCriteria = false;
            }

            // 2. Max Subtotal Surcharge (e.g. waive handling above a certain order total)
            if ($charge->max_subtotal !== null && $subtotal > (double)$charge->max_subtotal) {
                $meetsCriteria = false;
            }

            // 3. Min Weight Surcharge
            if ($charge->min_weight !== null && $totalWeight < (double)$charge->min_weight) {
                $meetsCriteria = false;
            }

            // 4. Min Items Count Surcharge
            if ($charge->min_items !== null && $itemCount < (int)$charge->min_items) {
                $meetsCriteria = false;
            }

            if ($meetsCriteria) {
                $totalHandling += (double)$charge->fee;
            }
        }

        return $totalHandling;
    }
}
