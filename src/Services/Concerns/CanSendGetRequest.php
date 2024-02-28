<?php
/*
 * Author: WOLF
 * Name: CanSendGetRequest.php
 * Modified : mar., 13 févr. 2024 08:54
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Concerns;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

trait CanSendGetRequest
{
    /**
     * @param PendingRequest $request
     * @param string $url
     * @param array|string|null $query
     * @return PromiseInterface|Response
     * @phpstan-ignore-next-line
     */
    public function get(PendingRequest $request, string $url, array|string|null $query = []): PromiseInterface|Response
    {
        return $request->withoutVerifying()->get(
            url: $url,
            query: $query
        );
    }
}
