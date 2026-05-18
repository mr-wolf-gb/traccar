<?php
/*
 * Author: WOLF
 * Name: config.php
 * Modified : mar., 20 févr. 2024 14:25
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

return [
    'base_url' => env('TRACCAR_BASE_URL'),
    'websocket_url' => env('TRACCAR_SOCKET_URL'),

    'auth' => [
        'username' => env('TRACCAR_USERNAME'),
        'password' => env('TRACCAR_PASSWORD'),
        'token' => env('TRACCAR_TOKEN'),
        'method' => env('TRACCAR_AUTH_METHOD', 'auto'), // auto, token, basic
    ],
    'database' => [
        'connection' => env('TRACCAR_DB_CONNECTION', 'mysql'),
        'chunk' => 1000,
    ],
    'devices' => [
        'store_in_database' => env('TRACCAR_AUTO_SAVE_DEVICES', true)
    ]
];
