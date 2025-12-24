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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'innovation_api' => [
        'url' => env('INNOVATION_API_URL'),
        'key' => env('INNOVATION_API_KEY'),
        'timeout' => env('INNOVATION_API_TIMEOUT', 120),
    ],

    'burnout_api' => [
        'url' => env('BURNOUT_API_URL'),
        'token' => env('BURNOUT_API_TOKEN'),
    ],

    'mbti_api' => [
        'url' => env('MBTI_API_URL'),
        'token' => env('MBTI_API_TOKEN'),
    ],

    'disc_api' => [
        'url' => env('DISC_API_URL'),
        'key' => env('DISC_API_KEY'),
    ],

    'papi_api' => [
        'url' => env('PAPI_API_URL'),
        'token' => env('PAPI_API_TOKEN'),
    ],

];
