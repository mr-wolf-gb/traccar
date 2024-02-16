<?php
/*
 * Author: WOLF
 * Name: config.php
 * Modified : ven., 16 févr. 2024 14:58
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
    ],
    'database' => [
        'connection' => env('TRACCAR_DB_CONNECTION', 'mysql'),
        'chunk' => 1000,
    ],
    'devices' => [
        'store_in_database' => env('TRACCAR_AUTO_SAVE_DEVICES', true)
    ]
];
