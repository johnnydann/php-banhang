<?php
// config/momo.php
return [
    /*
    |--------------------------------------------------------------------------
    | MoMo Payment Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration for MoMo Payment Gateway
    |
    */

    'api_url' => env('MOMO_API_URL', 'https://test-payment.momo.vn/gw_payment/transactionProcessor'),
    'secret_key' => env('MOMO_SECRET_KEY', 'my-srecet-key'),
    'access_key' => env('MOMO_ACCESS_KEY', 'my-accesskey'),
    'return_url' => env('MOMO_RETURN_URL', 'http://localhost:8000/api/cart/payment-callback'),
    'notify_url' => env('MOMO_NOTIFY_URL', 'http://localhost:8000/api/cart/payment-callback'),
    'partner_code' => env('MOMO_PARTNER_CODE', 'my-partner-code'),
    'request_type' => env('MOMO_REQUEST_TYPE', 'captureMoMoWallet'),
];