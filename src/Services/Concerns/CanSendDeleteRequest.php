<?php
/*
 * Author: WOLF
 * Name: CanSendDeleteRequest.php
 * Modified : mar., 20 févr. 2024 10:26
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Concerns;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

trait CanSendDeleteRequest
{
    /**
     * @param PendingRequest $request
     * @param string $url
     * @param array $payload
     * @return PromiseInterface|Response
     * @phpstan-ignore-next-line
     */
    public function delete(PendingRequest $request, string $url, array $payload = []): PromiseInterface|Response
    {
        return $request->withoutVerifying()->delete(
            url: $url,
            data: [
                ...$payload,
            ],
        );
    }
}
