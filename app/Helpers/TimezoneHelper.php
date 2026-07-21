<?php

namespace App\Helpers;

class TimezoneHelper
{
    /**
     * Returns all PHP timezones grouped by region, with friendly labels.
     *
     * Structure: ['Region Label' => ['Timezone/Identifier' => 'Friendly Name', ...], ...]
     */
    public static function grouped(): array
    {
        return [
            '— Americas / North America —' => [
                'Pacific/Honolulu'    => 'Hawaii (HST, UTC-10)',
                'America/Anchorage'   => 'Anchorage / Alaska (AKST, UTC-9)',
                'America/Los_Angeles' => 'Los Angeles / Pacific Time (PT, UTC-8)',
                'America/Denver'      => 'Denver / Mountain Time (MT, UTC-7)',
                'America/Phoenix'     => 'Phoenix / Arizona (MST, UTC-7 no DST)',
                'America/Chicago'     => 'Chicago / Central Time (CT, UTC-6)',
                'America/New_York'    => 'New York / Eastern Time (ET, UTC-5)',
                'America/Halifax'     => 'Halifax / Atlantic Time (AT, UTC-4)',
                'America/St_Johns'    => 'St. John\'s / Newfoundland (NST, UTC-3:30)',
            ],
            '— Americas / Canada (additional) —' => [
                'America/Vancouver'   => 'Vancouver (PT, UTC-8)',
                'America/Edmonton'    => 'Edmonton (MT, UTC-7)',
                'America/Winnipeg'    => 'Winnipeg (CT, UTC-6)',
                'America/Toronto'     => 'Toronto (ET, UTC-5)',
                'America/Moncton'     => 'Moncton (AT, UTC-4)',
            ],
            '— Americas / Latin America —' => [
                'America/Mexico_City' => 'Mexico City (CST, UTC-6)',
                'America/Bogota'      => 'Bogota / Lima (COT, UTC-5)',
                'America/Caracas'     => 'Caracas (VET, UTC-4)',
                'America/Sao_Paulo'   => 'São Paulo / Brasília (BRT, UTC-3)',
                'America/Santiago'    => 'Santiago (CLT, UTC-3)',
                'America/Argentina/Buenos_Aires' => 'Buenos Aires (ART, UTC-3)',
            ],
            '— Europe / Western —' => [
                'Atlantic/Reykjavik' => 'Reykjavik / Iceland (GMT, UTC+0)',
                'Europe/London'      => 'London / Dublin (GMT/BST, UTC+0/+1)',
                'Europe/Dublin'      => 'Dublin (IST, UTC+1)',
                'Europe/Lisbon'      => 'Lisbon (WET/WEST, UTC+0/+1)',
                'Europe/Madrid'      => 'Madrid (CET/CEST, UTC+1/+2)',
                'Europe/Paris'       => 'Paris / Brussels (CET/CEST, UTC+1/+2)',
                'Europe/Amsterdam'   => 'Amsterdam (CET/CEST, UTC+1/+2)',
                'Europe/Berlin'      => 'Berlin (CET/CEST, UTC+1/+2)',
                'Europe/Rome'        => 'Rome (CET/CEST, UTC+1/+2)',
                'Europe/Zurich'      => 'Zurich (CET/CEST, UTC+1/+2)',
            ],
            '— Europe / Northern —' => [
                'Europe/Stockholm'   => 'Stockholm (CET/CEST, UTC+1/+2)',
                'Europe/Oslo'        => 'Oslo (CET/CEST, UTC+1/+2)',
                'Europe/Copenhagen'  => 'Copenhagen (CET/CEST, UTC+1/+2)',
                'Europe/Helsinki'    => 'Helsinki (EET/EEST, UTC+2/+3)',
            ],
            '— Europe / Eastern —' => [
                'Europe/Warsaw'      => 'Warsaw (CET/CEST, UTC+1/+2)',
                'Europe/Budapest'    => 'Budapest (CET/CEST, UTC+1/+2)',
                'Europe/Bucharest'   => 'Bucharest (EET/EEST, UTC+2/+3)',
                'Europe/Sofia'       => 'Sofia (EET/EEST, UTC+2/+3)',
                'Europe/Athens'      => 'Athens (EET/EEST, UTC+2/+3)',
                'Europe/Kiev'        => 'Kyiv (EET/EEST, UTC+2/+3)',
                'Europe/Moscow'      => 'Moscow (MSK, UTC+3)',
                'Europe/Istanbul'    => 'Istanbul (TRT, UTC+3)',
            ],
            '— Africa & Middle East —' => [
                'Africa/Cairo'       => 'Cairo (EET, UTC+2)',
                'Africa/Johannesburg'=> 'Johannesburg (SAST, UTC+2)',
                'Africa/Nairobi'     => 'Nairobi (EAT, UTC+3)',
                'Africa/Lagos'       => 'Lagos (WAT, UTC+1)',
                'Asia/Dubai'         => 'Dubai / UAE (GST, UTC+4)',
                'Asia/Riyadh'        => 'Riyadh / Saudi Arabia (AST, UTC+3)',
                'Asia/Baghdad'       => 'Baghdad (AST, UTC+3)',
            ],
            '— Asia / South & Central —' => [
                'Asia/Karachi'       => 'Karachi / Pakistan (PKT, UTC+5)',
                'Asia/Kolkata'       => 'Kolkata / Mumbai / India (IST, UTC+5:30)',
                'Asia/Dhaka'         => 'Dhaka / Bangladesh (BST, UTC+6)',
                'Asia/Rangoon'       => 'Yangon / Myanmar (MMT, UTC+6:30)',
                'Asia/Bangkok'       => 'Bangkok / Jakarta (ICT, UTC+7)',
            ],
            '— Asia / East —' => [
                'Asia/Shanghai'      => 'Beijing / Shanghai (CST, UTC+8)',
                'Asia/Hong_Kong'     => 'Hong Kong (HKT, UTC+8)',
                'Asia/Singapore'     => 'Singapore (SGT, UTC+8)',
                'Asia/Manila'        => 'Manila (PST, UTC+8)',
                'Asia/Kuala_Lumpur'  => 'Kuala Lumpur (MYT, UTC+8)',
                'Asia/Seoul'         => 'Seoul (KST, UTC+9)',
                'Asia/Tokyo'         => 'Tokyo (JST, UTC+9)',
            ],
            '— Pacific / Oceania —' => [
                'Australia/Perth'    => 'Perth / Western Australia (AWST, UTC+8)',
                'Australia/Darwin'   => 'Darwin (ACST, UTC+9:30)',
                'Australia/Adelaide' => 'Adelaide (ACST/ACDT, UTC+9:30/+10:30)',
                'Australia/Brisbane' => 'Brisbane / Queensland (AEST, UTC+10)',
                'Australia/Sydney'   => 'Sydney / Melbourne (AEST/AEDT, UTC+10/+11)',
                'Pacific/Auckland'   => 'Auckland / Wellington (NZST/NZDT, UTC+12/+13)',
                'Pacific/Fiji'       => 'Fiji (FJT, UTC+12)',
            ],
            '— UTC —' => [
                'UTC' => 'UTC / Coordinated Universal Time (UTC+0)',
            ],
        ];
    }

    /**
     * Returns a flat list of all timezone identifiers for validation.
     */
    public static function all(): array
    {
        $flat = [];
        foreach (self::grouped() as $group => $zones) {
            foreach ($zones as $tz => $label) {
                $flat[] = $tz;
            }
        }
        return $flat;
    }
}
