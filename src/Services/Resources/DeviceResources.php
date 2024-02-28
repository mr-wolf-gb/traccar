<?php
/*
 * Author: WOLF
 * Name: DeviceResources.php
 * Modified : lun., 26 févr. 2024 11:34
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Device;
use MrWolfGb\Traccar\Models\Geofence;
use MrWolfGb\Traccar\Models\Notification;

class DeviceResources extends BaseResource
{

    /**
     * @param int $deviceId
     * @return Device|null
     * @throws TraccarException
     */
    public function getDeviceById(int $deviceId): null|Device
    {
        $d = $this->fetchListDevices(all: false, id: $deviceId)->first();
        return $d instanceof Device ? $d : null;
    }

    /**
     * @param bool $all
     * @param int|null $userId
     * @param array|int|null $id // device id
     * @param array|string|null $uniqueId // device unique id
     * @return Collection
     * @throws TraccarException
     */
    public function fetchListDevices(bool $all = true, ?int $userId = null, null|array|int $id = null, null|array|string $uniqueId = null): Collection
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

    /**
     * @param int $userId
     * @return Collection
     * @throws TraccarException
     */
    public function getUserDevices(int $userId): Collection
    {
        return $this->fetchListDevices(all: false, userId: $userId);
    }

    /**
     * @param string $uniqueId
     * @return Device|null
     * @throws TraccarException
     */
    public function getDeviceByUniqueId(string $uniqueId): null|Device
    {
        $d = $this->fetchListDevices(all: false, uniqueId: $uniqueId)->first();
        return $d instanceof Device ? $d : null;
    }

    /**
     * @param string $name
     * @param string $uniqueId
     * @param int $id
     * @param string $status
     * @param bool $disabled
     * @param string|null $lastUpdate
     * @param int $positionId
     * @param int $groupId
     * @param string $phone
     * @param string $model
     * @param string $contact
     * @param string $category
     * @param array $attribs
     * @return Device
     * @throws TraccarException
     */
    public function createDevice(string $name, string $uniqueId, int $id = -1, string $status = "",
                                 bool   $disabled = false, ?string $lastUpdate = null, int $positionId = 0,
                                 int    $groupId = 0, string $phone = "", string $model = "", string $contact = "",
                                 string $category = "", array $attribs = []
    ): Device
    {
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'devices',
            payload: [
                'id' => $id,
                'name' => $name,
                'uniqueId' => $uniqueId,
                'status' => $status,
                'disabled' => $disabled,
                'lastUpdate' => $lastUpdate,
                'positionId' => $positionId,
                'groupId' => $groupId,
                'phone' => $phone,
                'model' => $model,
                'contact' => $contact,
                'category' => $category,
                'attributes' => empty($attribs) ? null : $attribs
            ]
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Device::createFromValueArray($response->json());
    }

    /**
     * @param Device $device
     * @return Device
     * @throws TraccarException
     */
    public function createNewDevice(Device $device): Device
    {
        if ($device->uniqueId == '') {
            throw new TraccarException("UniqueId cannot be empty !");
        }
        if ($device->name == '') {
            throw new TraccarException("Name cannot be empty !");
        }
        $postData = $device->toArray();
        $postData["attributes"] = empty($postData["attribs"]) ? null : $postData["attribs"];
        unset($postData["attribs"]);
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'devices',
            payload: $postData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Device::createFromValueArray($response->json());
    }

    /**
     * @param Device $device
     * @return Device
     * @throws TraccarException
     */
    public function updateDevice(Device $device): Device
    {
        $putData = $device->toArray();
        $putData["attributes"] = empty($putData["attribs"]) ? null : $putData["attribs"];
        unset($putData["attribs"]);
        $response = $this->service->put(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'devices/' . $device->id,
            payload: $putData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Device::createFromValueArray($response->json());
    }

    /**
     * @param int|Device $device
     * @return bool
     * @throws TraccarException
     */
    public function deleteDevice(int|Device $device): bool
    {
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'devices/' . ($device instanceof Device ? $device->id : $device)
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

    /**
     * @param int|Device $device
     * @param int $totalDistance
     * @param int $hours
     * @return bool
     * @throws TraccarException
     */
    public function updateTotalDistanceAndHoursOfDevice(int|Device $device, int $totalDistance = 0, int $hours = 0): bool
    {
        $deviceId = $device instanceof Device ? $device->id : $device;
        $response = $this->service->put(
            request: $this->service->buildRequestWithBasicAuth(),
            url: "devices/$deviceId/accumulators",
            payload: [
                'deviceId' => $deviceId,
                'totalDistance' => $totalDistance,
                'hours' => $hours
            ]
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

    /**
     * @param int|Device $device
     * @param int|Geofence $geofence
     * @return bool
     * @throws TraccarException
     */
    public function assignDeviceGeofence(int|Device $device, int|Geofence $geofence): bool
    {
        $deviceId = $device instanceof Device ? $device->id : $device;
        $geofenceId = $geofence instanceof Geofence ? $geofence->id : $geofence;
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: "permissions",
            payload: [
                'deviceId' => $deviceId,
                'geofenceId' => $geofenceId
            ]
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

    /**
     * @param int|Device $device
     * @param int|Geofence $geofence
     * @return bool
     * @throws TraccarException
     */
    public function removeDeviceGeofence(int|Device $device, int|Geofence $geofence): bool
    {
        $deviceId = $device instanceof Device ? $device->id : $device;
        $geofenceId = $geofence instanceof Geofence ? $geofence->id : $geofence;
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth(),
            url: "permissions",
            payload: [
                'deviceId' => $deviceId,
                'geofenceId' => $geofenceId
            ]
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

    /**
     * @param int|Device $device
     * @param int|Notification $notification
     * @return bool
     * @throws TraccarException
     */
    public function assignDeviceNotification(int|Device $device, int|Notification $notification): bool
    {
        $deviceId = $device instanceof Device ? $device->id : $device;
        $notificationId = $notification instanceof Notification ? $notification->id : $notification;
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: "permissions",
            payload: [
                'deviceId' => $deviceId,
                'notificationId' => $notificationId
            ]
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

    /**
     * @param int|Device $device
     * @param int|Notification $notification
     * @return bool
     * @throws TraccarException
     */
    public function removeDeviceNotification(int|Device $device, int|Notification $notification): bool
    {
        $deviceId = $device instanceof Device ? $device->id : $device;
        $notificationId = $notification instanceof Notification ? $notification->id : $notification;
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth(),
            url: "permissions",
            payload: [
                'deviceId' => $deviceId,
                'notificationId' => $notificationId
            ]
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }
}
