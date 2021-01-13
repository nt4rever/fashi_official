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
    'facebook' => [
        'client_id' => '230489531811041',  //client face của bạn
        'client_secret' => '00ca29d9956305d3660211408b2c1d40',  //client app service face của bạn
        'redirect' => 'https://fashi.com/GIT_fix/public/login-customer/callback' //callback trả về
    ],
    'google' => [
        'client_id' => '313861602513-0ctqvactttgkb0ogb1nd5i6ur2v0380a.apps.googleusercontent.com',
        'client_secret' => 'hWC-PcU9MxkQwFUY5dG_xLg8',
        'redirect' => 'https://fashi.com/GIT_fix/public/login-customer/google/callback'
    ],



];
