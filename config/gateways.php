<?php

return [
    'nagad' => [
        'sandbox_mode' => env('NAGAD_MODE', 'production'),
        'merchant_id' => env('NAGAD_MERCHANT_ID','689444303535399'),
        'merchant_number' => env('NAGAD_MERCHANT_NUMBER','01944430353'),
        'callback_url' => env('NAGAD_MERCHANT_CALLBACK_URL', env('APP_URL').'/nagad/callback')
    ],

    'sandbox_nagad' => [
        'sandbox_mode' => env('SANDBOX_NAGAD_MODE', 'sandbox'),
        'merchant_id' => env('SANDBOX_NAGAD_MERCHANT_ID','689444303535399'),
        'merchant_number' => env('SANDBOX_NAGAD_MERCHANT_NUMBER','01944430353'),
        'callback_url' => env('SANDBOX_NAGAD_MERCHANT_CALLBACK_URL', env('APP_URL').'/nagad/callback')
    ],
];
