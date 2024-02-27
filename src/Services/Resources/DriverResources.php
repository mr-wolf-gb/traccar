<?php
/*
 * Author: WOLF
 * Name: DriverResources.php
 * Modified : mar., 27 févr. 2024 08:21
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Driver;

class DriverResources extends BaseResource
{

    /**
     * @param bool $all
     * @param int|null $userId
     * @param int|null $deviceId
     * @param int|null $groupId
     * @param bool $refresh
     * @return Collection
     * @throws TraccarException
     */
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

    /**
     * @param string $name
     * @param string $uniqueId
     * @param array $attribs
     * @return Driver
     * @throws TraccarException
     */
    public function createDriver(string $name, string $uniqueId, array $attribs = []): Driver
    {
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'drivers',
            payload: [
                "name" => $name,
                "uniqueId" => $uniqueId,
                "attributes" => empty($attribs) ? null : $attribs
            ]
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Driver::createFromValueArray($response->json());
    }

    /**
     * @param Driver $driver
     * @return Driver
     * @throws TraccarException
     */
    public function createNewDriver(Driver $driver): Driver
    {
        if ($driver->uniqueId == '') {
            throw new TraccarException("Unique Id cannot be empty !");
        }
        if ($driver->name == '') {
            throw new TraccarException("Name cannot be empty !");
        }
        $postData = $driver->toArray();
        $postData["attributes"] = empty($postData["attribs"]) ? null : $postData["attribs"];
        unset($postData["attribs"]);
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'drivers',
            payload: $postData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Driver::createFromValueArray($response->json());
    }

    /**
     * @param Driver $driver
     * @return Driver
     * @throws TraccarException
     */
    public function updateDriver(Driver $driver): Driver
    {
        $putData = $driver->toArray();
        $putData["attributes"] = empty($putData["attribs"]) ? null : $putData["attribs"];
        unset($putData["attribs"]);
        $response = $this->service->put(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'drivers/' . $driver->id,
            payload: $putData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Driver::createFromValueArray($response->json());
    }

    /**
     * @param int|Driver $driver
     * @return bool
     * @throws TraccarException
     */
    public function deleteDriver(int|Driver $driver): bool
    {
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'drivers/' . ($driver instanceof Driver ? $driver->id : $driver)
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }
}
