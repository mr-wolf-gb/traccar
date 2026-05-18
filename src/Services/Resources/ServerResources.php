<?php
/*
 * Author: WOLF
 * Name: ServerResources.php
 * Modified : lun., 26 févr. 2024 11:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Server;

class ServerResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function checkHealth(): string
    {
        $response = $this->ensureOk($this->service->get(
            request: $this->service->withBaseUrl(),
            url: 'health'
        ));

        return $response->body();
    }

    /**
     * @throws TraccarException
     */
    public function fetchServerInformation(): Server
    {
        $response = $this->service->get(
            request: $this->service->buildRequestWithConfiguredAuth(),
            url: 'server'
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Server::createFromValueArray($response->json());
    }

    /**
     * @param Server $server
     * @return Server
     * @throws TraccarException
     */
    public function updateServerInformation(Server $server): Server
    {
        $putData = $server->only('id', 'registration', 'readonly', 'deviceReadonly', 'limitCommands', 'map',
            'bingKey', 'mapUrl', 'poiLayer', 'announcement', 'latitude', 'longitude', 'zoom', 'twelveHourFormat', 'version', 'forceSettings',
            'coordinateFormat', 'openIdEnabled', 'openIdForce', 'attribs');
        $putData["attributes"] = empty($putData["attribs"]) ? null : $putData["attribs"];
        unset($putData["attribs"]);
        $response = $this->service->put(
            request: $this->service->buildRequestWithConfiguredAuth(),
            url: 'server',
            payload: $putData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Server::createFromValueArray($response->json());
    }

    /**
     * @throws TraccarException
     */
    public function reverseGeocode(float $latitude, float $longitude): string
    {
        $response = $this->ensureOk($this->service->get(
            request: $this->service->buildAuthorizedRequest(),
            url: 'server/geocode',
            query: compact('latitude', 'longitude')
        ));

        return $response->body();
    }

    /**
     * @throws TraccarException
     */
    public function fetchTimezones(): Collection
    {
        return $this->getCollection('server/timezones');
    }

    /**
     * @throws TraccarException
     */
    public function uploadServerFile(string $path, string $localFile): bool
    {
        $response = $this->service->buildAuthorizedRequest()
            ->withoutVerifying()
            ->withBody(File::get($localFile), 'application/octet-stream')
            ->post('server/file/' . ltrim($path, '/'));

        return $this->ensureNoContent($response);
    }

    /**
     * @throws TraccarException
     */
    public function triggerGarbageCollection(): bool
    {
        $response = $this->service->get(
            request: $this->service->buildAuthorizedRequest(),
            url: 'server/gc'
        );

        return $this->ensureNoContent($response);
    }

    /**
     * @throws TraccarException
     */
    public function fetchCacheDiagnostics(): string
    {
        $response = $this->ensureOk($this->service->get(
            request: $this->service->buildAuthorizedRequest(),
            url: 'server/cache'
        ));

        return $response->body();
    }

    /**
     * @throws TraccarException
     */
    public function rebootServer(): bool
    {
        $response = $this->service->post(
            request: $this->service->buildAuthorizedRequest(),
            url: 'server/reboot'
        );

        return $this->ensureNoContent($response);
    }
}
