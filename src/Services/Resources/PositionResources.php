<?php
/*
 * Author: WOLF
 * Name: PositionResources.php
 * Modified : mer., 21 févr. 2024 13:30
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Position;
use MrWolfGb\Traccar\Services\TraccarService;
use MrWolfGb\Traccar\Trait\UrlQueryHelper;

class PositionResources
{
    use UrlQueryHelper;

    public function __construct(public TraccarService $service)
    {
    }

    /**
     * @param string $from
     * @param string $to
     * @param int|null $deviceId
     * @param array|int|null $id
     * @return Collection
     * @throws TraccarException
     */
    public function fetchListPositions(string $from, string $to, ?int $deviceId = null, null|array|int $id = null): Collection
    {
        $query = [];
        if ($deviceId != null) {
            $query["deviceId"] = $deviceId;
        } elseif ($id != null) {
            if (is_array($id)) {
                $query["id"] = $this->prepareMultipleQuery('id', $id);
            } else {
                $query["id"] = $id;
            }
        }
        $query["from"] = Carbon::parse($from)->format('Y-m-d\TH:i:s\Z');
        $query["to"] = Carbon::parse($to)->format('Y-m-d\TH:i:s\Z');
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'positions',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Position::createFromValueList($response->json());
    }

    /**
     * @param string $uniqueId
     * @param bool $valid
     * @param string $timestamp
     * @param float $lat
     * @param float $lon
     * @param string|null $location
     * @param string|null $cell
     * @param string|null $wifi
     * @param int $speed
     * @param string|null $bearing
     * @param float $altitude
     * @param float|null $accuracy
     * @param string|null $hdop
     * @param string|null $batt
     * @param string|null $driverUniqueId
     * @param bool|null $charge
     * @param ...$extra
     * @return void
     * @throws TraccarException
     * @docs //https://www.traccar.org/osmand/
     */
    public function OsmAnd(string $uniqueId, bool $valid = true, string $timestamp = '',
                           float  $lat = 0.0, float $lon = 0.0, string $location = null, string $cell = null,
                           string $wifi = null, int $speed = 0, string $bearing = null, float $altitude = 0.0,
                           float  $accuracy = null, string $hdop = null, string $batt = null, string $driverUniqueId = null,
                           bool   $charge = null, ...$extra
    ): void
    {
        $timestamp = Carbon::parse($timestamp ?: now())->format('Y-m-d H:i:s');
        $params = array_merge([
            "id" => $uniqueId, "valid" => $valid, "timestamp" => $timestamp, "latitude" => $lat,
            "longitude" => $lon, "location" => $location, "cell" => $cell, "wifi" => $wifi, "speed" => $speed,
            "bearing" => $bearing, "altitude" => $altitude, "accuracy" => $accuracy, "hdop" => $hdop,
            "batt" => $batt, "driverUniqueId" => $driverUniqueId, "charge" => $charge
        ], $extra['extra']);
        $response = $this->service->post(
            request: $this->service->withBaseUrlWithoutApi()->withQueryParameters($params),
            url: "",
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
    }
}
