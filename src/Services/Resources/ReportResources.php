<?php
/*
 * Author: WOLF
 * Name: ReportResources.php
 * Modified : lun., 26 févr. 2024 11:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Event;

class ReportResources extends BaseResource
{
    /**
     * @param int $eventID
     * @return Event
     * @throws TraccarException
     */
    public function fetchEventInformation(int $eventID): Event
    {
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'events/' . $eventID,
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Event::createFromValueArray($response->json());
    }
}
