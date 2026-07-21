<?php

namespace App\Services;

use Illuminate\Hashing\HashManager;

class CustomHashManager extends HashManager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'custom_ripemd';
    }

    /**
     * Create an instance of the custom ripemd256 driver.
     *
     * @return \App\Services\CustomRipemdHasher
     */
    public function createCustomRipemdDriver()
    {
        return new CustomRipemdHasher();
    }
}
