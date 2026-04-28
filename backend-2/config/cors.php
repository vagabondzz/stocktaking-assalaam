<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Config
    |--------------------------------------------------------------------------
    | File ini untuk mengatur agar frontend (Vue di localhost:8080)
    | bisa akses backend Laravel (port 8002).
    */

    'paths' => ['api/*', 'admin/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://192.168.137.249:5173'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
