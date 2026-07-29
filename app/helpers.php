<?php

if (!function_exists('siteLabel')) {
    /**
     * Retrieve a site label by key.
     *
     * Returns the admin-set custom override when present, otherwise the
     * seeded default value, otherwise the $fallback string.
     *
     * Usage in Blade:  @label('nav.home', 'Home')
     * Usage in PHP:    siteLabel('nav.home', 'Home')
     *
     * @param  string  $key       Label key (e.g. 'nav.home', 'cart.add_to_cart')
     * @param  string  $fallback  Returned when the key is not in the database
     * @param  int     $langId    Language ID (0 = default / English)
     */
    function siteLabel(string $key, string $fallback = '', int $langId = 0): string
    {
        // Pass null (not 0) so SiteLabelService resolves the current session language.
        return app(\App\Services\SiteLabelService::class)->get($key, $fallback, $langId ?: null);
    }
}
