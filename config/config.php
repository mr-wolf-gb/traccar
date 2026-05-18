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
    'websocket_cookie' => [
        'enabled' => env('TRACCAR_SOCKET_COOKIE_ENABLED', true),
        'domain' => env('TRACCAR_SOCKET_COOKIE_DOMAIN'),
        'same_site' => env('TRACCAR_SOCKET_COOKIE_SAME_SITE', 'lax'),
        'secure' => env('TRACCAR_SOCKET_COOKIE_SECURE', false),
        'http_only' => env('TRACCAR_SOCKET_COOKIE_HTTP_ONLY', true),
    ],

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
        'store_in_database' => env('TRACCAR_AUTO_SAVE_DEVICES', true),
    ],
];
