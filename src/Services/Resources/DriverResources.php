<?php
/*
 * Author: WOLF
 * Name: DriverResources.php
 * Modified : mar., 13 févr. 2024 08:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Driver;
use MrWolfGb\Traccar\Services\TraccarService;

class DriverResources
{
    public function __construct(public TraccarService $service)
    {
    }

    public function fetchListDrivers(bool $all = true, ?int $userId = null, ?int $deviceId = null, ?int $groupId = null, bool $refresh = false): Collection
    {
        $query = ["all" => $all, "refresh" => $refresh];
        if ($userId != null) {
            $query["userId"] = $userId;
        }
        if ($deviceId != null) {
            $query["deviceId"] = $deviceId;
        }
        if ($groupId != null) {
            $query["groupId"] = $groupId;
        }
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'drivers',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Driver::createFromValueList($response->json());
    }
}
