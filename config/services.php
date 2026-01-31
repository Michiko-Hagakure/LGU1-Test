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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'infrastructure_pm' => [
        'base_url' => env('INFRASTRUCTURE_PM_URL', 'https://infra-pm.local-government-unit-1-ph.com'),
        'api_key' => env('INFRASTRUCTURE_PM_API_KEY'),
        'timeout' => env('INFRASTRUCTURE_PM_TIMEOUT', 30),
    ],

    'community_cim' => [
        'base_url' => env('COMMUNITY_CIM_URL', 'https://community.local-government-unit-1-ph.com'),
        'timeout' => env('COMMUNITY_CIM_TIMEOUT', 30),
    ],

    'urban_planning' => [
        'base_url' => env('URBAN_PLANNING_URL', 'https://planning.local-government-unit-1-ph.com'),
        'timeout' => env('URBAN_PLANNING_TIMEOUT', 30),
    ],

    'energy_efficiency' => [
        'base_url' => env('ENERGY_EFFICIENCY_URL', 'https://energy.local-government-unit-1-ph.com'),
        'timeout' => env('ENERGY_EFFICIENCY_TIMEOUT', 30),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', rtrim(env('APP_URL', config('app.url')), '/') . '/auth/google/callback'),
    ],

];
