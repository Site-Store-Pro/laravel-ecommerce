<?php

namespace App\Services;

use Illuminate\Contracts\Hashing\Hasher;
use App\Models\User;

class CustomRipemdHasher implements Hasher
{
    /**
     * Get information about the given hashed value.
     */
    public function info($hashedValue)
    {
        return [
            'algo' => 'ripemd256',
            'algoName' => 'ripemd256',
        ];
    }

    /**
     * Hash the given value.
     */
    public function make($value, array $options = [])
    {
        if (str_starts_with($value, 'custom_raw:')) {
            return $value;
        }
        return 'custom_raw:' . $value;
    }

    /**
     * Check the given plain value against a hash.
     */
    public function check($value, $hashedValue, array $options = [])
    {
        if (empty($hashedValue)) {
            return false;
        }

        // 1. If we have the email stashed from the login request, use it!
        if (CustomHashContext::$currentEmail) {
            $user = User::where('email', CustomHashContext::$currentEmail)->first();
            if ($user) {
                $computed = hash_hmac('ripemd256', $value, $user->user_token_1);
                return hash_equals($hashedValue, $computed);
            }
        }

        // 2. If there is an authenticated user and their password matches, use them
        if (auth()->check() && auth()->user()->password === $hashedValue) {
            $user = auth()->user();
            $computed = hash_hmac('ripemd256', $value, $user->user_token_1);
            return hash_equals($hashedValue, $computed);
        }

        // 3. Fallback: Search user by password hash
        $user = User::where('password', $hashedValue)->first();
        if ($user) {
            $computed = hash_hmac('ripemd256', $value, $user->user_token_1);
            return hash_equals($hashedValue, $computed);
        }

        return false;
    }

    /**
     * Check if the given hash has been hashed using the given options.
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}
