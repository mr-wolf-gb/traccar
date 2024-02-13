<?php
/*
 * Author: WOLF
 * Name: PositionResources.php
 * Modified : mar., 13 févr. 2024 09:41
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
        $query["from"] = rtrim(Carbon::parse($from)->toIso8601String(), "+00:00") . "Z";
        $query["to"] = rtrim(Carbon::parse($to)->toIso8601String(), "+00:00") . "Z";
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
}
