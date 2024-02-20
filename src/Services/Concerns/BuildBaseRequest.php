<?php
/*
 * Author: WOLF
 * Name: BuildBaseRequest.php
 * Modified : mar., 20 févr. 2024 14:24
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use MrWolfGb\Traccar\Exceptions\TraccarException;

trait BuildBaseRequest
{
    /**
     * @throws TraccarException
     */
    public function buildRequestWithAccessToken(): PendingRequest
    {
        if (empty($this->getToken())) throw new TraccarException("No access token provided.");
        return $this->withBaseUrl()->withQueryParameters(["token" => $this->getToken()]);
    }

    /**
     * @throws TraccarException
     */
    public function buildRequestWithCookies(): PendingRequest
    {
        if (!Cache::has($this->getCacheKey())) throw new TraccarException("No cookies found for this session.");
        $session = Cache::get($this->getCacheKey())["session"];
        return $this->withBaseUrl()->withCookies([$session["Name"] => $session["Value"]], $session["Domain"]);
    }

    public function withBaseUrl(): PendingRequest
    {
        return Http::baseUrl(
            url: $this->baseUrl,
        )->withHeaders($this->headers);
    }

    public function buildRequestWithBasicAuth(null|string $username = null, null|string $password = null): PendingRequest
    {
        return $this->withBaseUrl()->withBasicAuth($username ?? $this->email, $password ?? $this->password);
    }
}
