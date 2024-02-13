<?php
/*
 * Author: WOLF
 * Name: EventResources.php
 * Modified : mar., 13 févr. 2024 08:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Event;
use MrWolfGb\Traccar\Services\TraccarService;
use MrWolfGb\Traccar\Trait\UrlQueryHelper;

class EventResources
{
    use UrlQueryHelper;

    public function __construct(public TraccarService $service)
    {
    }

    /**
     * @param int $id // event id
     * @return Event
     * @throws TraccarException
     */
    public function fetchEventInformation(int $id): Event
    {
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'events/' . $id,
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Event::createFromValueArray($response->json());
    }
}
