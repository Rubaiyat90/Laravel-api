<?php

/*
 * This config file contains all the configurations for common settings of the applications.
 *
 */

return [
    'api_version' => 'v1',
    'e_pourashava' => [
        'api_url' => env('E_APP_API_URL') ?? 'https://e-pourashava.com/api',
        'applications' => ['assessment', 'collection']
    ],
    'trade_license' => [
        'api_url' => env('TRADE_LICENSE_API_URL') ?? 'https://https://e-pourashava-trade-license.com/api',
        'applications' => ['trade_license']
    ],
];
