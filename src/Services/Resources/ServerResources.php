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

use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Server;

class ServerResources extends BaseResource
{

    /**
     * @throws TraccarException
     */
    public function fetchServerInformation(): Server
    {
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
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
            'bingKey', 'mapUrl', 'poiLayer', 'latitude', 'longitude', 'zoom', 'twelveHourFormat', 'version', 'forceSettings',
            'coordinateFormat', 'openIdEnabled', 'openIdForce', 'attribs');
        $putData["attributes"] = empty($putData["attribs"]) ? null : $putData["attribs"];
        unset($putData["attribs"]);
        $putData = array_map(function ($value) {
            if (is_bool($value)) {
                return $value ? 'true' : 'false';
            }
            return $value;
        }, $putData);
        $response = $this->service->put(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'server',
            payload: $putData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Server::createFromValueArray($response->json());
    }
}
