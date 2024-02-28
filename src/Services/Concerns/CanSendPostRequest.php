<?php
/*
 * Author: WOLF
 * Name: CanSendPostRequest.php
 * Modified : mar., 13 févr. 2024 08:58
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Concerns;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

trait CanSendPostRequest
{
    /**
     * @param PendingRequest $request
     * @param string $url
     * @return PromiseInterface|Response
     */
    public function session(PendingRequest $request, string $url): PromiseInterface|Response
    {
        return $request->withoutVerifying()->asForm()
            ->post(
                url: $url,
                data: [
                    'email' => $this->email,
                    'password' => $this->password
                ]
            );
    }

    /**
     * @param PendingRequest $request
     * @param string $url
     * @param array $payload
     * @return PromiseInterface|Response
     * @phpstan-ignore-next-line
     */
    public function post(PendingRequest $request, string $url, array $payload = []): PromiseInterface|Response
    {
        return $request->withoutVerifying()->post(
            url: $url,
            data: [
                ...$payload,
            ],
        );
    }
}
