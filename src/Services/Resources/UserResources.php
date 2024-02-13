<?php
/*
 * Author: WOLF
 * Name: UserResources.php
 * Modified : mar., 13 févr. 2024 08:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\User;
use MrWolfGb\Traccar\Services\TraccarService;

class UserResources
{
    public function __construct(public TraccarService $service)
    {
    }

    public function fetchListUsers(?int $userId = null): Collection
    {
        $query = [];
        if ($userId != null) {
            $query["userId"] = $userId;
        }
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'users',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return User::createFromValueList($response->json());
    }
}
