<?php
/*
 * Author: WOLF
 * Name: PositionResources.php
 * Modified : lun., 26 févr. 2024 12:41
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Device;
use MrWolfGb\Traccar\Models\Position;

class PositionResources extends BaseResource
{

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
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);
        if ($from->greaterThan($to)) {
            throw new TraccarException("The from date cannot be greater than the to date");
        }
        $query["from"] = $from->format('Y-m-d\TH:i:s\Z');
        $query["to"] = $to->format('Y-m-d\TH:i:s\Z');
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
     * @param string $uniqueId The unique identifier for the device. This is a mandatory parameter.
     * @param bool $valid Indicates if the location is valid. Acceptable values are "true", "false", "1", and "0".
     * @param string $timestamp The timestamp of the position. It can be in seconds or milliseconds since epoch, ISO 8601 format, or "yyyy-MM-dd HH:mm:ss" format.
     * @param float $lat Latitude of the position. This should be a double value.
     * @param float $lon Longitude of the position. This should be a double value.
     * @param string|null $location A comma-separated string in the format "latitude,longitude".
     * @param string|null $cell Cell tower information in the format "mcc,mnc,lac,cellId,signalStrength" or "mcc,mnc,lac,cellId".
     * @param string|null $wifi WiFi access point information in the format "macAddress:signalStrength".
     * @param int $speed Speed of the device. Units are configurable with knots as a default.
     * @param string|null $bearing The direction in which the device is moving, in degrees.
     * @param float $altitude Altitude of the device in meters.
     * @param float|null $accuracy Accuracy of the position in meters.
     * @param string|null $hdop Horizontal dilution of precision.
     * @param string|null $batt Battery level of the device.
     * @param string|null $driverUniqueId Unique identifier for the driver.
     * @param bool|null $charge Indicates if the device is charging. Acceptable values are `true` and `false`.
     * @param ...$extra
     * @return void
     * @throws TraccarException
     * @link https://www.traccar.org/osmand/
     * @phpstan-ignore-next-line
     */
    public function OsmAnd(string $uniqueId, bool $valid = true, string $timestamp = '',
                           float  $lat = 0.0, float $lon = 0.0, string $location = null, string $cell = null,
                           string $wifi = null, int $speed = 0, string $bearing = null, float $altitude = 0.0,
                           float  $accuracy = null, string $hdop = null, string $batt = null, string $driverUniqueId = null,
                           bool   $charge = null, ...$extra
    ): void
    {
        $timestamp = empty($timestamp) ? now() : Carbon::parse($timestamp);
        $params = array_merge([
            "id" => $uniqueId, "valid" => $valid, "timestamp" => $timestamp->timestamp, "latitude" => $lat,
            "longitude" => $lon, "location" => $location, "cell" => $cell, "wifi" => $wifi, "speed" => $speed,
            "bearing" => $bearing, "altitude" => $altitude, "accuracy" => $accuracy, "hdop" => $hdop,
            "batt" => $batt, "driverUniqueId" => $driverUniqueId, "charge" => $charge
        ], $extra);
        $response = $this->service->post(
            request: $this->service->withBaseUrl(useAPI: false)->withQueryParameters($params),
            url: "",
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
    }

    /**
     * @param int|Device $device
     * @param string $from
     * @param string $to
     * @return bool
     * @throws TraccarException
     */
    public function deletePositions(int|Device $device, string $from, string $to): bool
    {
        $query = ["deviceId" => ($device instanceof Device ? $device->id : $device)];
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);
        if ($from->greaterThan($to)) {
            throw new TraccarException("The from date cannot be greater than the to date");
        }
        $query["from"] = $from->format('Y-m-d\TH:i:s\Z');
        $query["to"] = $to->format('Y-m-d\TH:i:s\Z');
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth()->withQueryParameters($query),
            url: 'positions'
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }
}
