<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * Verify a reCAPTCHA v3 token with Google's API.
     *
     * Returns true when the token is valid, the action matches,
     * and the score meets the configured threshold.
     * Always returns true when no site key is configured (local dev).
     */
    public function verify(string $token, string $action): bool
    {
        $siteKey = config('services.recaptcha.site_key', '');

        // Skip verification when keys are not configured (local / CI environments)
        if (empty($siteKey) || empty(config('services.recaptcha.secret'))) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => config('services.recaptcha.secret'),
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            $data = $response->json();

            if (! ($data['success'] ?? false)) {
                Log::debug('reCAPTCHA verification failed', ['errors' => $data['error-codes'] ?? []]);
                return false;
            }

            $threshold = (float) config('services.recaptcha.threshold', 0.5);
            $score     = (float) ($data['score'] ?? 0.0);

            if ($score < $threshold) {
                Log::debug('reCAPTCHA score too low', ['score' => $score, 'threshold' => $threshold]);
                return false;
            }

            if (($data['action'] ?? '') !== $action) {
                Log::debug('reCAPTCHA action mismatch', ['expected' => $action, 'got' => $data['action'] ?? '']);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA request failed', ['error' => $e->getMessage()]);

            // Fail open on network errors so a Google outage doesn't lock users out
            return true;
        }
    }
}
