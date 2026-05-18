<?php
/*
 * Author: WOLF
 * Name: EventResources.php
 * Modified : lun., 26 févr. 2024 11:34
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Event;

class EventResources extends BaseResource
{

    /**
     * @param int $eventID
     * @return Event
     * @throws TraccarException
     */
    public function fetchEventInformation(int $eventID): Event
    {
        $response = $this->service->get(
            request: $this->service->buildRequestWithConfiguredAuth(),
            url: 'events/' . $eventID,
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Event::createFromValueArray($response->json());
    }
}
