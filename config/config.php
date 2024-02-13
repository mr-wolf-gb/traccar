<?php
/*
 * Author: WOLF
 * Name: config.php
 * Modified : mar., 13 févr. 2024 12:36
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

return [
    'base_url' => env('TRACCAR_BASE_URL'),
    'websocket_url' => env('TRACCAR_BASE_URL') . 'socket?token=',
    'token' => env('TRACCAR_ACCESS_TOKEN'),

    'auth' => [
        'username' => env('TRACCAR_USERNAME'),
        'password' => env('TRACCAR_PASSWORD'),
    ],
    'inject_assets' => false,
    'database' => [
        'connection' => env('TRACCAR_DB_CONNECTION', 'mysql'),
        'chunk' => 1000,
    ],
    'devices' => [
        'store_in_database' => env('TRACCAR_AUTO_SAVE_DEVICES', true)
    ]
];
