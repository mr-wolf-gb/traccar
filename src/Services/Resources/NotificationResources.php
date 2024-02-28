<?php
/*
 * Author: WOLF
 * Name: NotificationResources.php
 * Modified : lun., 26 févr. 2024 11:34
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Enums\NotificatorType;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Notification;

class NotificationResources extends BaseResource
{

    /**
     * @param bool $all Can only be used by admins or managers to fetch all entities
     * @param int|null $userId Standard users can use this only with their own userId
     * @param int|null $deviceId Standard users can use this only with _deviceId_s, they have access to
     * @param int|null $groupId Standard users can use this only with _groupId_s, they have access to
     * @param bool $refresh
     * @return Collection
     * @throws TraccarException
     */
    public function fetchListNotifications(bool $all = true, ?int $userId = null, ?int $deviceId = null, ?int $groupId = null, bool $refresh = false): Collection
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
            url: 'notifications',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Notification::createFromValueList($response->json());
    }

    /**
     * @param string $type
     * @param array $notificators
     * @param bool $always
     * @param int|null $commandId
     * @param int|null $calendarId
     * @param array $attribs
     * @return Notification
     * @throws TraccarException
     */
    public function createNotification(string $type, array $notificators, bool $always = false, int $commandId = null, int $calendarId = null, array $attribs = []): Notification
    {
        if (empty($type)) {
            throw new TraccarException("Type cannot be empty !");
        }
        if (empty($notificators)) {
            throw new TraccarException("Notificators cannot be empty !");
        }
        if (in_array(NotificatorType::COMMAND->value, $notificators, true)) {
            if (empty($commandId)) {
                throw new TraccarException("Command ID required !");
            }
        }
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'notifications',
            payload: [
                "type" => $type,
                "notificators" => implode(',', $notificators),
                "always" => $always,
                "commandId" => $commandId,
                "calendarId" => $calendarId,
                "attributes" => empty($attribs) ? null : $attribs
            ]
        );
        if (!$response->ok()) {
            throw new TraccarException($response->body());
        }
        return Notification::createFromValueArray($response->json());
    }

    /**
     * @param Notification $notification
     * @return Notification
     * @throws TraccarException
     */
    public function createNewNotification(Notification $notification): Notification
    {
        if ($notification->type == '') {
            throw new TraccarException("Type cannot be empty !");
        }
        if (empty($notification->notificators)) {
            throw new TraccarException("Notificators cannot be empty !");
        }
        if (in_array(NotificatorType::COMMAND->value, explode(',', $notification->notificators), true)) {
            if (empty($notification->commandId)) {
                throw new TraccarException("Command ID required !");
            }
        }
        $postData = $notification->toArray();
        $postData["attributes"] = empty($postData["attribs"]) ? null : $postData["attribs"];
        unset($postData["attribs"]);
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'notifications',
            payload: $postData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Notification::createFromValueArray($response->json());
    }

    /**
     * @param Notification $notification
     * @return Notification
     * @throws TraccarException
     */
    public function updateNotification(Notification $notification): Notification
    {
        $putData = $notification->toArray();
        $putData["attributes"] = empty($putData["attribs"]) ? null : $putData["attribs"];
        unset($putData["attribs"]);
        $response = $this->service->put(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'notifications/' . $notification->id,
            payload: $putData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Notification::createFromValueArray($response->json());
    }

    /**
     * @param int|Notification $notification
     * @return bool
     * @throws TraccarException
     */
    public function deleteNotification(int|Notification $notification): bool
    {
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'notifications/' . ($notification instanceof Notification ? $notification->id : $notification)
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }

    /**
     * @return array
     * @throws TraccarException
     */
    public function fetchNotificationTypes(): array
    {
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'notifications/types',
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        $res = $response->json();
        return is_array($res) ? $res : [];
    }

    /**
     * @param string $type
     * @param string $notificator
     * @param bool $always
     * @param int|null $commandId
     * @return void
     * @throws TraccarException
     */
    public function sendTestNotification(string $type, string $notificator, bool $always = false, int $commandId = null): void
    {
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'notifications/test/' . $notificator,
            payload: [
                "type" => $type,
                "notificators" => $notificator,
                "always" => $always,
                "commandId" => $commandId,
            ]
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->body());
        }
    }

}
