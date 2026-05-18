<?php

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Calendar;

class CalendarResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function fetchListCalendars(?bool $all = null, ?int $userId = null, ?int $limit = null, ?int $offset = null, ?string $keyword = null): Collection
    {
        return $this->getModelCollection('calendars', Calendar::class, compact('all', 'userId', 'limit', 'offset', 'keyword'));
    }

    /**
     * @throws TraccarException
     */
    public function createCalendar(array|Calendar $calendar): Calendar
    {
        return $this->createModelResource('calendars', $this->payload($calendar), Calendar::class);
    }

    /**
     * @throws TraccarException
     */
    public function updateCalendar(int $id, array|Calendar $calendar): Calendar
    {
        return $this->updateModelResource("calendars/$id", $this->payload($calendar), Calendar::class);
    }

    /**
     * @throws TraccarException
     */
    public function deleteCalendar(int $id): bool
    {
        return $this->deleteResource("calendars/$id");
    }

    private function payload(array|Calendar $calendar): array
    {
        $payload = $calendar instanceof Calendar ? $calendar->toArray() : $calendar;
        if (array_key_exists('attribs', $payload)) {
            $payload['attributes'] = $payload['attribs'];
            unset($payload['attribs']);
        }

        return $payload;
    }
}
