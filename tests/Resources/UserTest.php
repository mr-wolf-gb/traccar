<?php
/*
 * Author: WOLF
 * Name: UserTest.php
 * Modified : mar., 27 févr. 2024 10:22
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */


use MrWolfGb\Traccar\Services\TraccarService;

it('can fetch list of users', function (TraccarService $TraccarService) {
    $TraccarService->userRepository()->fetchListUsers();
    // Add your test code here
});

it('can create a new user', function () {
    // Add your test code here
});

it('can create a new user with specific attributes', function () {
    // Add your test code here
});

it('can update a user', function () {
    // Add your test code here
});
