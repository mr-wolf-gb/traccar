<?php
/*
 * Author: WOLF
 * Name: GeofenceResources.php
 * Modified : mar., 13 févr. 2024 08:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Geofence;
use MrWolfGb\Traccar\Services\TraccarService;
use MrWolfGb\Traccar\Trait\UrlQueryHelper;

class GeofenceResources
{
    use UrlQueryHelper;

    public function __construct(public TraccarService $service)
    {
    }

    public function fetchListGeofences(bool $all = true, ?int $userId = null, ?int $deviceId = null, ?int $groupId = null, bool $refresh = false): Collection
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
            url: 'geofences',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Geofence::createFromValueList($response->json());
    }

}
