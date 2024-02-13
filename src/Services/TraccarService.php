<?php
/*
 * Author: WOLF
 * Name: TraccarService.php
 * Modified : mar., 13 févr. 2024 08:45
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services;

use MrWolfGb\Traccar\Services\Concerns\BuildBaseRequest;
use MrWolfGb\Traccar\Services\Concerns\CanSendGetRequest;
use MrWolfGb\Traccar\Services\Concerns\CanSendPostRequest;
use MrWolfGb\Traccar\Services\Resources\DeviceResources;
use MrWolfGb\Traccar\Services\Resources\DriverResources;
use MrWolfGb\Traccar\Services\Resources\EventResources;
use MrWolfGb\Traccar\Services\Resources\GeofenceResources;
use MrWolfGb\Traccar\Services\Resources\GroupResources;
use MrWolfGb\Traccar\Services\Resources\NotificationResources;
use MrWolfGb\Traccar\Services\Resources\PositionResources;
use MrWolfGb\Traccar\Services\Resources\SessionResources;
use MrWolfGb\Traccar\Services\Resources\UserResources;

class TraccarService
{
    use BuildBaseRequest, CanSendGetRequest, CanSendPostRequest;

    /**
     * @param string $baseUrl
     * @param string $email
     * @param string $password
     * @param array $headers
     */
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $email,
        private readonly string $password,
        private readonly array  $headers)
    {
    }


    /**
     * @return SessionResources
     */
    public function sessionRepository(): SessionResources
    {
        return new SessionResources($this);
    }

    /**
     * @return DeviceResources
     */
    public function deviceRepository(): DeviceResources
    {
        return new DeviceResources($this);
    }

    /**
     * @return GroupResources
     */
    public function groupRepository(): GroupResources
    {
        return new GroupResources($this);
    }

    /**
     * @return UserResources
     */
    public function userRepository(): UserResources
    {
        return new UserResources($this);
    }

    /**
     * @return PositionResources
     */
    public function positionRepository(): PositionResources
    {
        return new PositionResources($this);
    }

    /**
     * @return EventResources
     */
    public function eventRepository(): EventResources
    {
        return new EventResources($this);
    }

    /**
     * @return NotificationResources
     */
    public function notificationRepository(): NotificationResources
    {
        return new NotificationResources($this);
    }

    /**
     * @return GeofenceResources
     */
    public function geofenceRepository(): GeofenceResources
    {
        return new GeofenceResources($this);
    }

    /**
     * @return DriverResources
     */
    public function driverRepository(): DriverResources
    {
        return new DriverResources($this);
    }
}
