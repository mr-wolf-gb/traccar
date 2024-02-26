<?php
/*
 * Author: WOLF
 * Name: GeofenceResources.php
 * Modified : lun., 26 févr. 2024 15:17
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Geofence;

class GeofenceResources extends BaseResource
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

    /**
     * @param string $name
     * @param string $area
     * @param int $id
     * @param string|null $description
     * @param int|null $calendarId
     * @param array $attribs
     * @return Geofence
     * @throws TraccarException
     */
    public function createGeofence(string $name, string $area, int $id = -1, string $description = null, int $calendarId = null, array $attribs = []): Geofence
    {
        if (empty($name)) {
            throw new TraccarException("Name cannot be empty !");
        }
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'geofences',
            payload: [
                "name" => $name,
                "id" => $id,
                "description" => $description,
                "area" => $area,
                "calendarId" => $calendarId,
                "attributes" => empty($attribs) ? null : $attribs
            ]
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Geofence::createFromValueArray($response->json());
    }

    /**
     * @param Geofence $geofence
     * @return Geofence
     * @throws TraccarException
     */
    public function createNewGeofence(Geofence $geofence): Geofence
    {
        if ($geofence->area == '') {
            throw new TraccarException("Area cannot be empty !");
        }
        if ($geofence->name == '') {
            throw new TraccarException("Name cannot be empty !");
        }
        $postData = $geofence->toArray();
        $postData["attributes"] = empty($postData["attribs"]) ? null : $postData["attribs"];
        unset($postData["attribs"]);
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'geofences',
            payload: $postData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Geofence::createFromValueArray($response->json());
    }

    /**
     * @param Geofence $geofence
     * @return Geofence
     * @throws TraccarException
     */
    public function updateGeofence(Geofence $geofence): Geofence
    {
        $putData = $geofence->toArray();
        $putData["attributes"] = empty($putData["attribs"]) ? null : $putData["attribs"];
        unset($putData["attribs"]);
        $response = $this->service->put(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'geofences/' . $geofence->id,
            payload: $putData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Geofence::createFromValueArray($response->json());
    }

    /**
     * @param int|Geofence $geofence
     * @return bool
     * @throws TraccarException
     */
    public function deleteGeofence(int|Geofence $geofence): bool
    {
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'geofences/' . ($geofence instanceof Geofence ? $geofence->id : $geofence)
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

}
