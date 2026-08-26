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

    'acumatica_mpesa' => [
        'base_url' => env('ACUMATICA_MPESA_API_URL'),
        'base_get_pos_url' => env('ACUMATICA_MPESA_API_GET_POS_URL'),
        'login_url' => env('ACUMATICA_MPESA_API_LOGIN_URL'),
        'client_id' => env('ACUMATICA_MPESA_API_CLIENT_ID'),
        'client_secret' => env('ACUMATICA_MPESA_API_CLIENT_SECRET'),
        'username' => env('ACUMATICA_MPESA_API_USERNAME'),
        'password' => env('ACUMATICA_MPESA_API_PASSWORD'),
    
    ],


    'mpesa' => [
        'base_url' => env('MPESA_API_URL'),
        'consumer_key' => env('MPESA_API_CONSUMER_KEY'),
        'consumer_secret' => env('MPESA_API_CONSUMER_SECRET'),
        'shortcode' => env('MPESA_API_SHORTCODE'),
        'passkey' => env('MPESA_API_PASSKEY'),
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

];
