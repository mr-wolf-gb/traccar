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

use Illuminate\Support\Facades\Cache;
use MrWolfGb\Traccar\Exceptions\TraccarException;
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
     * @throws TraccarException
     */
    public function __construct(
        private string $baseUrl,
        private string $email,
        private string $password,
        private array  $headers)
    {
        if (!Cache::has('traccar_auth_array')){
            $this->sessionRepository()->createNewSession();
        }
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): TraccarService
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): TraccarService
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): TraccarService
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers): TraccarService
    {
        $this->headers = $headers;
        return $this;
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
