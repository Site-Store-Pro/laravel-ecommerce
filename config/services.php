<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') . '/auth/google/callback',
    ],

    'recaptcha' => [
        'site_key'  => env('RECAPTCHA_SITE_KEY', ''),
        'secret'    => env('RECAPTCHA_SECRET_KEY', ''),
        'threshold' => env('RECAPTCHA_THRESHOLD', 0.5),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/facebook/callback',
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/github/callback',
    ],

    'inbound' => [
        'secret' => env('INBOUND_WEBHOOK_SECRET'),
    ],

    'stripe' => [
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'sandbox_publishable_key' => env('STRIPE_SANDBOX_PUBLISHABLE_KEY'),
        'sandbox_secret_key' => env('STRIPE_SANDBOX_SECRET_KEY'),
    ],

    'paddle' => [
        'client_token' => env('PADDLE_CLIENT_TOKEN'),
        'sandbox_client_token' => env('PADDLE_SANDBOX_CLIENT_TOKEN'),
        'api_key' => env('PADDLE_API_KEY'),
        'sandbox_api_key' => env('PADDLE_SANDBOX_API_KEY'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'sandbox_client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'sandbox_client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
    ],

];
