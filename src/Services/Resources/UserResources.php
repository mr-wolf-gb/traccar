<?php
/*
 * Author: WOLF
 * Name: UserResources.php
 * Modified : lun., 26 févr. 2024 11:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Device;
use MrWolfGb\Traccar\Models\User;

class UserResources extends BaseResource
{
    /**
     * @param string|null $userId Can only be used by admin or manager users
     * @return Collection
     * @throws TraccarException
     */
    public function fetchListUsers(?string $userId = null): Collection
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


    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @param int|null $id
     * @param string $phone
     * @param bool $readonly
     * @param bool $administrator
     * @param string $map
     * @param float $latitude
     * @param float $longitude
     * @param int $zoom
     * @param bool $twelveHourFormat
     * @param string $coordinateFormat
     * @param bool $disabled
     * @param string $expirationTime in IS0 8601 format. eg. 1963-11-22T18:30:00Z
     * @param int $deviceLimit
     * @param int $userLimit
     * @param bool $deviceReadonly
     * @param bool $limitCommands
     * @param bool $fixedEmail
     * @param string $poiLayer
     * @param array $attribs
     * @return User
     * @throws TraccarException
     */
    public function createUser(string $name, string $email, string $password, int $id = null, string $phone = '', bool $readonly = false,
                               bool   $administrator = false, string $map = '', float $latitude = 0, float $longitude = 0, int $zoom = 0,
                               bool   $twelveHourFormat = false, string $coordinateFormat = '0', bool $disabled = false,
                               string $expirationTime = '', int $deviceLimit = 0, int $userLimit = 0, bool $deviceReadonly = false,
                               bool   $limitCommands = false, bool $fixedEmail = false, string $poiLayer = '', array $attribs = []
    ): User
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new TraccarException("Invalid email address !");
        }
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'users',
            payload: [
                "name" => $name, "email" => $email, "password" => $password,
                "id" => $id, "phone" => $phone, "readonly" => $readonly,
                "administrator" => $administrator, "map" => $map, "latitude" => $latitude,
                "longitude" => $longitude, "zoom" => $zoom, "twelveHourFormat" => $twelveHourFormat,
                "coordinateFormat" => $coordinateFormat, "disabled" => $disabled, "expirationTime" => $expirationTime,
                "deviceLimit" => $deviceLimit, "userLimit" => $userLimit, "deviceReadonly" => $deviceReadonly,
                "limitCommands" => $limitCommands, "fixedEmail" => $fixedEmail, "poiLayer" => $poiLayer,
                "attributes" => empty($attribs) ? null : $attribs
            ]
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return User::createFromValueArray($response->json());
    }

    /**
     * @param User $user
     * @return User
     * @throws TraccarException
     */
    public function createNewUser(User $user): User
    {
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new TraccarException("Invalid email address !");
        }
        if ($user->password == '') {
            throw new TraccarException("Password cannot be empty !");
        }
        if ($user->name == '') {
            throw new TraccarException("Name cannot be empty !");
        }
        $postData = $user->toArray();
        $postData["attributes"] = empty($postData["attribs"]) ? null : $postData["attribs"];
        unset($postData["attribs"]);
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'users',
            payload: $postData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return User::createFromValueArray($response->json());
    }


    /**
     * @param User $user
     * @return User
     * @throws TraccarException
     */
    public function updateUser(User $user): User
    {
        $putData = $user->toArray();
        $putData["attributes"] = empty($putData["attribs"]) ? null : $putData["attribs"];
        unset($putData["attribs"]);
        $response = $this->service->put(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'users/' . $user->id,
            payload: $putData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return User::createFromValueArray($response->json());
    }

    /**
     * @param int|User $user to get user id
     * @return bool
     * @throws TraccarException
     */
    public function deleteUser(int|User $user): bool
    {
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'users/' . ($user instanceof User ? $user->id : $user)
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

    /**
     * @param int|User $user
     * @param int|Device $device
     * @return bool
     * @throws TraccarException
     */
    public function assignUserDevice(int|User $user, int|Device $device): bool
    {
        $deviceId = $device instanceof Device ? $device->id : $device;
        $userId = $user instanceof User ? $user->id : $user;
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: "permissions",
            payload: [
                'deviceId' => $deviceId,
                'userId' => $userId
            ]
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

    /**
     * @param int|User $user
     * @param int|Device $device
     * @return bool
     * @throws TraccarException
     */
    public function removeUserDevice(int|User $user, int|Device $device): bool
    {
        $deviceId = $device instanceof Device ? $device->id : $device;
        $userId = $user instanceof User ? $user->id : $user;
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth(),
            url: "permissions",
            payload: [
                'deviceId' => $deviceId,
                'userId' => $userId
            ]
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

}
