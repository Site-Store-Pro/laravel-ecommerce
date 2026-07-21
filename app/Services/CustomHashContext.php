<?php

namespace App\Services;

class CustomHashContext
{
    /**
     * Stash the email being authenticated, so our custom Hasher can access it.
     */
    public static ?string $currentEmail = null;
}
