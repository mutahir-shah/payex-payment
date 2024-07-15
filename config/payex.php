<?php

return [
    'api_live_url'    => env('PAYMENT_GATEWAY_API_LIVE_URL'),
    'api_sandbox_url' => env('PAYMENT_GATEWAY_API_SANDBOX_URL'),
    'api_username'    => env('PAYMENT_GATEWAY_API_USERNAME'), //user name
    'api_secret'      => env('PAYMENT_GATEWAY_API_SECRET'), //password
    'sandbox'         => env('PAYMENT_GATEWAY_SANDBOX', false),
    "return_url"      => env('RETURN_URL'),
    "callback_url"    => env('CALLBACK_URL'),
    "accept_url"      => env('ACCEPT_URL'),
    "reject_url"      => env('REJECT_URL'),
    "payment_type"    => env('PAYMENT_TYPE');//e.g 
    "payex_currency"  => env('PAYEX_CURRENCY', 'MYR'),
    "country_code"    => env('COUNTRY_CODE', 'MY'),
];
