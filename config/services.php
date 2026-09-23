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

    'suitecrm' => [
        'base_url' => env('SUITECRM_BASE_URL'),
        'token' => env('SUITECRM_TOKEN'),
        'timeout' => env('SUITECRM_TIMEOUT', 10),
    ],

    'waggies_ai' => [
        'enabled' => env('WAGGIES_AI_ENABLED', true),
        'provider' => env('WAGGIES_AI_PROVIDER', 'openai'),
        'model' => env('WAGGIES_AI_MODEL', 'gpt-4o-mini'),
        'vector_store_id' => env('WAGGIES_AI_VECTOR_STORE_ID'),
        'create_vector_store' => env('WAGGIES_AI_CREATE_VECTOR_STORE', false),
        'sync_provider' => env('WAGGIES_AI_SYNC_PROVIDER'),
        'max_message_chars' => 1200,
        'max_history_messages' => 8,
    ],

];
