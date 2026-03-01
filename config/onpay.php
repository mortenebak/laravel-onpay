<?php

return [
    'api_token' => env('ONPAY_API_TOKEN'),
    'gateway_id' => env('ONPAY_GATEWAY_ID'),
    'secret' => env('ONPAY_SECRET'),
    'base_url' => env('ONPAY_BASE_URL', 'https://api.onpay.io'),
    'window_url' => env('ONPAY_WINDOW_URL', 'https://onpay.io/window/v3/'),
];
