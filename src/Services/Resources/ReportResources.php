<?php
/*
 * Author: WOLF
 * Name: ReportResources.php
 * Modified : lun., 26 févr. 2024 12:57
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Report;

/**
 *
 */
class ReportResources extends BaseResource
{
    /**
     * @param string $from
     * @param string $to
     * @param int $deviceId
     * @return Collection
     * @throws TraccarException
     */
    public function reportRoute(string $from, string $to, int $deviceId): Collection
    {
        $query = $this->getReportData($deviceId, $from, $to);
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'reports/route',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Report::createFromValueList($response->json());
    }

    /**
     * @param int|array|null $deviceId
     * @param string $from
     * @param string $to
     * @return array
     * @throws TraccarException
     */
    protected function getReportData(int|array|null $deviceId, string $from, string $to): array
    {
        $query = [];
        if (!empty($deviceId)) {
            if (is_array($deviceId)) {
                $query["deviceId"] = $this->prepareMultipleQuery('deviceId', $deviceId);
            } else {
                $query["deviceId"] = $deviceId;
            }
        }
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);
        if ($from->greaterThan($to)) {
            throw new TraccarException("The [from] date cannot be greater than the [to] date.");
        }
        $query["from"] = $from->format('Y-m-d\TH:i:s\Z');
        $query["to"] = $to->format('Y-m-d\TH:i:s\Z');
        return $query;
    }

    /**
     * @param string $from
     * @param string $to
     * @param int $deviceId
     * @param array|string|null $type
     * @return Collection
     * @throws TraccarException
     */
    public function reportEvents(string $from, string $to, int $deviceId, null|array|string $type = 'allEvents'): Collection
    {
        $query = $this->getReportData($deviceId, $from, $to);
        if (!empty($type)) {
            if (is_array($type)) {
                $query["type"] = $this->prepareMultipleQuery('type', $type);
            } else {
                $query["type"] = $type;
            }
        }
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'reports/events',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Report::createFromValueList($response->json());
    }

    /**
     * @param string $from
     * @param string $to
     * @param array|int|null $deviceId
     * @param array|int|null $groupId
     * @param bool|null $daily
     * @return Collection
     * @throws TraccarException
     */
    public function reportSummary(string $from, string $to, null|array|int $deviceId = null, null|array|int $groupId = null, bool|null $daily = null): Collection
    {
        $query = $this->getReportData($deviceId, $from, $to);
        if (!empty($groupId)) {
            if (is_array($groupId)) {
                $query["groupId"] = $this->prepareMultipleQuery('groupId', $groupId);
            } else {
                $query["groupId"] = $groupId;
            }
        }
        $query["daily"] = $daily ? 'true' : 'false';
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'reports/summary',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Report::createFromValueList($response->json());
    }

    /**
     * @param string $from
     * @param string $to
     * @param int $deviceId
     * @return Collection
     * @throws TraccarException
     */
    public function reportTrips(string $from, string $to, int $deviceId): Collection
    {
        $query = $this->getReportData($deviceId, $from, $to);
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'reports/trips',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Report::createFromValueList($response->json());
    }

    /**
     * @param string $from
     * @param string $to
     * @param int $deviceId
     * @return Collection
     * @throws TraccarException
     */
    public function reportStops(string $from, string $to, int $deviceId): Collection
    {
        $query = $this->getReportData($deviceId, $from, $to);
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'reports/stops',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Report::createFromValueList($response->json());
    }

    /**
     * @param string $from
     * @param string $to
     * @param array|int|null $deviceId
     * @param array|int|null $groupId
     * @return Collection
     * @throws TraccarException
     */
    public function reportCombined(string $from, string $to, null|array|int $deviceId = null, null|array|int $groupId = null): Collection
    {
        $query = $this->getReportData($deviceId, $from, $to);
        if (!empty($groupId)) {
            if (is_array($groupId)) {
                $query["groupId"] = $this->prepareMultipleQuery('groupId', $groupId);
            } else {
                $query["groupId"] = $groupId;
            }
        }
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'reports/combined',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Report::createFromValueList($response->json());
    }

}
