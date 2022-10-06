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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sms' => [
        'jadu' => [
            'api_key' => env('SMS_JADU_API_KEY'),
            'sender' => env("SMS_SENDER_NAME", env('APP_NAME'))
        ],
        'clickatell' => [
            'api_key' => env('SMS_CLICKATELL_API_KEY'),
            'sender' => env("SMS_SENDER_NAME", env('APP_NAME'))
        ],
        'nexmo' => [
            'api_key' => env('SMS_NEXMO_API_KEY'),
            'api_secret' => env('SMS_NEXMO_API_SECRET'),
            'sender' => env("SMS_SENDER_NAME", env('APP_NAME'))
        ],
        'twilio' => [
            'sid' => env('SMS_TWILIO_SID'),
            'token' => env('SMS_TWILIO_TOKEN'),
            'sender' => env("SMS_SENDER_NAME", env('APP_NAME'))
        ],
    ],
    'payment' => [
        'aamarpay' => [
            'client_id' => env('AAMARPAY_CLIENT_ID'),
            'client_secret' => env('AAMARPAY_CLIENT_SECRET'),
            'sandbox' => env('AAMARPAY_SANDBOX', false)
        ],
        'paypal' => [
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
            'sandbox' => env('PAYPAL_SANDBOX', false)
        ],
        'portwallet' => [
            'app_key' => env('PORTWALLET_API_KEY'),
            'app_secret' => env('PORTWALLET_API_SECRET'),
            'sandbox' => (bool) env('PORTWALLET_SANDBOX', true)
        ]
    ],
    'zoom' => [
        'client_id' => 'clPiqXqMQJ2Q2wqKFD1OAw',
        'client_secret' => 'UNfJqo1roAH35IaNldHC3caWVoqv5Ptd'
    ]

];
