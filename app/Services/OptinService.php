<?php

namespace App\Services;

use App\Models\CmsForm;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * OptinService — subscribes a contact to a mailing-list provider.
 *
 * Supported providers (configured per-form via optin_provider + optin_list_id):
 *   - mailchimp        MAILCHIMP_API_KEY + MAILCHIMP_SERVER_PREFIX (.env)
 *   - constant_contact CONSTANT_CONTACT_API_KEY (.env)
 *   - klaviyo          KLAVIYO_API_KEY (.env)
 *
 * The email/name values are resolved from the submission data by looking for
 * fields flagged with field_role = 'email' and field_role = 'name'.
 *
 * All failures are caught and logged silently — a provider API error never
 * blocks or modifies the form submission response shown to the visitor.
 */
class OptinService
{
    /**
     * Attempt to subscribe the submitter to the form's configured provider.
     *
     * @param  CmsForm  $form        The submitted form (with fields loaded)
     * @param  array    $values      Raw submission values keyed by field_id
     */
    public function subscribe(CmsForm $form, array $values): void
    {
        if (! $form->auto_optin || ! $form->optin_provider || ! $form->optin_list_id) {
            return;
        }

        // ── Resolve email and name from field roles ───────────────────────
        $email = null;
        $name  = null;

        foreach ($form->fields as $field) {
            if ($field->field_role === 'email' && ! $email) {
                $email = trim((string) ($values[$field->id] ?? ''));
            }
            if ($field->field_role === 'name' && ! $name) {
                $name = trim((string) ($values[$field->id] ?? ''));
            }
        }

        if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::debug('[OptinService] Skipping opt-in — no valid email found in submission', [
                'form_id' => $form->id,
            ]);
            return;
        }

        try {
            match ($form->optin_provider) {
                'mailchimp'        => $this->subscribeMailchimp($form->optin_list_id, $email, $name),
                'constant_contact' => $this->subscribeConstantContact($form->optin_list_id, $email, $name),
                'klaviyo'          => $this->subscribeKlaviyo($form->optin_list_id, $email, $name),
                default            => Log::warning('[OptinService] Unknown provider', ['provider' => $form->optin_provider]),
            };
        } catch (\Throwable $e) {
            Log::error('[OptinService] Uncaught exception during opt-in', [
                'form_id'  => $form->id,
                'provider' => $form->optin_provider,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    /**
     * Sync user opt-in status with third party provider (Mailchimp, Constant Contact, Klaviyo, etc.)
     */
    public static function syncUserOptIn(\App\Models\User $user, bool $optInState): void
    {
        $provider = \App\Models\CmsSetting::get('checkout_optin_provider', '');
        $listId   = \App\Models\CmsSetting::get('checkout_optin_list_id', '');

        $service = new self();
        if ($optInState) {
            $service->subscribeContact($user->email, $user->name, $provider, $listId);
        } else {
            $service->unsubscribeContact($user->email, $provider, $listId);
        }
    }

    public function subscribeContact(string $email, ?string $name = null, ?string $provider = null, ?string $listId = null): void
    {
        $provider = $provider ?: \App\Models\CmsSetting::get('checkout_optin_provider', '');
        $listId   = $listId ?: \App\Models\CmsSetting::get('checkout_optin_list_id', '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            match ($provider) {
                'mailchimp'        => $this->subscribeMailchimp($listId ?: 'default', $email, $name),
                'constant_contact' => $this->subscribeConstantContact($listId ?: 'default', $email, $name),
                'klaviyo'          => $this->subscribeKlaviyo($listId ?: 'default', $email, $name),
                default            => Log::info("[OptinService] Subscribed locally: {$email}"),
            };
        } catch (\Throwable $e) {
            Log::error('[OptinService] Subscribe exception', ['error' => $e->getMessage()]);
        }
    }

    public function unsubscribeContact(string $email, ?string $provider = null, ?string $listId = null): void
    {
        $provider = $provider ?: \App\Models\CmsSetting::get('checkout_optin_provider', '');
        $listId   = $listId ?: \App\Models\CmsSetting::get('checkout_optin_list_id', '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            match ($provider) {
                'mailchimp'        => $this->unsubscribeMailchimp($listId ?: 'default', $email),
                'constant_contact' => $this->unsubscribeConstantContact($listId ?: 'default', $email),
                'klaviyo'          => $this->unsubscribeKlaviyo($listId ?: 'default', $email),
                default            => Log::info("[OptinService] Unsubscribed locally: {$email}"),
            };
        } catch (\Throwable $e) {
            Log::error('[OptinService] Unsubscribe exception', ['error' => $e->getMessage()]);
        }
    }

    protected function unsubscribeMailchimp(string $listId, string $email): void
    {
        $apiKey = config('services.mailchimp.api_key', env('MAILCHIMP_API_KEY', ''));
        $server = config('services.mailchimp.server_prefix', env('MAILCHIMP_SERVER_PREFIX', 'us1'));

        if (empty($apiKey)) return;

        Http::withBasicAuth('anystring', $apiKey)
            ->put("https://{$server}.api.mailchimp.com/3.0/lists/{$listId}/members/" . md5(strtolower($email)), [
                'email_address' => $email,
                'status'        => 'unsubscribed',
            ]);
    }

    protected function unsubscribeConstantContact(string $listId, string $email): void
    {
        $apiKey = config('services.constant_contact.api_key', env('CONSTANT_CONTACT_API_KEY', ''));

        if (empty($apiKey)) return;

        Http::withToken($apiKey)
            ->post('https://api.cc.email/v3/contacts/sign_up_form', [
                'email_address' => ['address' => $email, 'permission_to_send' => 'explicit_out'],
            ]);
    }

    protected function unsubscribeKlaviyo(string $listId, string $email): void
    {
        $apiKey = config('services.klaviyo.api_key', env('KLAVIYO_API_KEY', ''));

        if (empty($apiKey)) return;

        Http::withHeaders([
            'Authorization' => 'Klaviyo-API-Key ' . $apiKey,
            'revision'      => '2023-02-22',
        ])->post('https://a.klaviyo.com/api/profile-unsubscriptions/', [
            'data' => [
                'type'       => 'profile-unsubscription',
                'attributes' => [
                    'emails'  => [$email],
                    'list_id' => $listId,
                ],
            ],
        ]);
    }

    // ── Mailchimp ──────────────────────────────────────────────────────────

    /**
     * Subscribe via Mailchimp Marketing API v3.
     * Env: MAILCHIMP_API_KEY, MAILCHIMP_SERVER_PREFIX (e.g. "us1")
     */
    protected function subscribeMailchimp(string $listId, string $email, ?string $name): void
    {
        $apiKey = config('services.mailchimp.api_key', env('MAILCHIMP_API_KEY', ''));
        $server = config('services.mailchimp.server_prefix', env('MAILCHIMP_SERVER_PREFIX', 'us1'));

        if (empty($apiKey)) {
            Log::debug('[OptinService] Mailchimp: no API key configured');
            return;
        }

        $mergeFields = [];
        if ($name) {
            // Split into FNAME / LNAME on first space
            $parts = explode(' ', $name, 2);
            $mergeFields['FNAME'] = $parts[0];
            if (isset($parts[1])) {
                $mergeFields['LNAME'] = $parts[1];
            }
        }

        $response = Http::withBasicAuth('anystring', $apiKey)
            ->put("https://{$server}.api.mailchimp.com/3.0/lists/{$listId}/members/" . md5(strtolower($email)), [
                'email_address' => $email,
                'status_if_new' => 'subscribed',
                'merge_fields'  => $mergeFields ?: new \stdClass(),
            ]);

        if ($response->failed()) {
            Log::warning('[OptinService] Mailchimp subscribe failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } else {
            Log::debug('[OptinService] Mailchimp subscribe ok', ['email' => $email]);
        }
    }

    // ── Constant Contact ───────────────────────────────────────────────────

    /**
     * Subscribe via Constant Contact v3 API.
     * Env: CONSTANT_CONTACT_API_KEY
     */
    protected function subscribeConstantContact(string $listId, string $email, ?string $name): void
    {
        $apiKey = config('services.constant_contact.api_key', env('CONSTANT_CONTACT_API_KEY', ''));

        if (empty($apiKey)) {
            Log::debug('[OptinService] Constant Contact: no API key configured');
            return;
        }

        $payload = [
            'email_address' => ['address' => $email, 'permission_to_send' => 'implicit'],
            'list_memberships' => [$listId],
        ];

        if ($name) {
            $parts = explode(' ', $name, 2);
            $payload['first_name'] = $parts[0];
            if (isset($parts[1])) {
                $payload['last_name'] = $parts[1];
            }
        }

        $response = Http::withToken($apiKey)
            ->post('https://api.cc.email/v3/contacts/sign_up_form', $payload);

        if ($response->failed()) {
            Log::warning('[OptinService] Constant Contact subscribe failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } else {
            Log::debug('[OptinService] Constant Contact subscribe ok', ['email' => $email]);
        }
    }

    // ── Klaviyo ────────────────────────────────────────────────────────────

    /**
     * Subscribe via Klaviyo API (2023-02-22 revision).
     * Env: KLAVIYO_API_KEY
     */
    protected function subscribeKlaviyo(string $listId, string $email, ?string $name): void
    {
        $apiKey = config('services.klaviyo.api_key', env('KLAVIYO_API_KEY', ''));

        if (empty($apiKey)) {
            Log::debug('[OptinService] Klaviyo: no API key configured');
            return;
        }

        $profileAttributes = ['email' => $email];
        if ($name) {
            $parts = explode(' ', $name, 2);
            $profileAttributes['first_name'] = $parts[0];
            if (isset($parts[1])) {
                $profileAttributes['last_name'] = $parts[1];
            }
        }

        $response = Http::withHeaders([
            'Authorization' => 'Klaviyo-API-Key ' . $apiKey,
            'revision'      => '2023-02-22',
        ])->post('https://a.klaviyo.com/api/profile-subscription-bulk-create-jobs/', [
            'data' => [
                'type'       => 'profile-subscription-bulk-create-job',
                'attributes' => [
                    'list_id'  => $listId,
                    'subscriptions' => [[
                        'channels'   => ['email' => ['MARKETING']],
                        'profile'    => [
                            'data' => [
                                'type'       => 'profile',
                                'attributes' => $profileAttributes,
                            ],
                        ],
                    ]],
                ],
            ],
        ]);

        if ($response->failed()) {
            Log::warning('[OptinService] Klaviyo subscribe failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } else {
            Log::debug('[OptinService] Klaviyo subscribe ok', ['email' => $email]);
        }
    }
}
