<?php
/*
 * Author: WOLF
 * Name: GroupResources.php
 * Modified : mar., 13 févr. 2024 08:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Group;
use MrWolfGb\Traccar\Services\TraccarService;

class GroupResources
{
    public function __construct(public TraccarService $service)
    {
    }

    public function fetchListGroups(bool $all = true, ?int $userId = null): Collection
    {
        $query = ["all" => $all];
        if ($userId != null) {
            $query["userId"] = $userId;
        }
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'groups',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Group::createFromValueList($response->json());
    }
}
