<?php
/*
 * Author: WOLF
 * Name: DeviceResources.php
 * Modified : mar., 13 févr. 2024 08:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Device;
use MrWolfGb\Traccar\Services\TraccarService;
use MrWolfGb\Traccar\Trait\UrlQueryHelper;

class DeviceResources
{
    use UrlQueryHelper;

    public function __construct(public TraccarService $service)
    {
    }

    /**
     * @param bool $all
     * @param int|null $userId
     * @param array|int|null $id // device id
     * @param array|int|null $uniqueId // device unique id
     * @return Collection
     * @throws TraccarException
     */
    public function fetchListDevices(bool $all = true, ?int $userId = null, null|array|int $id = null, null|array|int $uniqueId = null): Collection
    {
        $query = ["all" => $all];
        if ($userId != null) {
            $query["userId"] = $userId;
        }
        if ($id != null) {
            if (is_array($id)) {
                $query["id"] = $this->prepareMultipleQuery('id', $id);
            } else {
                $query["id"] = $id;
            }
        } elseif ($uniqueId != null) {
            if (is_array($uniqueId)) {
                $query["uniqueId"] = $this->prepareMultipleQuery('uniqueId', $uniqueId);
            } else {
                $query["uniqueId"] = $uniqueId;
            }
        }
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'devices',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Device::createFromValueList($response->json());
    }
}
