<?php
return [
    'passport' => [
        'login_endpoint' => env('PASSPORT_LOGIN_ENDPOINT'),
        'client_id' => env('PASSPORT_CLIENT_ID'),
        'client_secret' => env('PASSPORT_CLIENT_SECRET'),
    ],
    'ameise' => [
        'login_endpoint' => env('AMEISE_LOGIN_ENDPOINT'),
        'client_id' => env('AMEISE_CLIENT_ID'),
        'client_secret' => env('AMEISE_CLIENT_SECRET'),
        'scope' => env('AMEISE_CLIENT_SCOPE'),
    ],
];
//  