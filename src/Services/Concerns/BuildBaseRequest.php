<?php
/*
 * Author: WOLF
 * Name: BuildBaseRequest.php
 * Modified : ven., 23 févr. 2024 09:24
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
        return $this->withBaseUrl()->withToken($this->getToken());
    }

    /**
     * @throws TraccarException
     */
    public function buildAuthorizedRequest(): PendingRequest
    {
        if ($this->shouldUseBasicAuth()) {
            return $this->buildRequestWithConfiguredAuth(forceBasicAuth: true);
        }

        return $this->buildRequestWithAccessToken();
    }

    public function withBaseUrl(bool $useAPI = true): PendingRequest
    {
        return Http::baseUrl(
            url: rtrim($this->baseUrl, "/") . ($useAPI ? "/api/" : ""),
        )->withHeaders($this->headers);
    }

    /**
     * @throws TraccarException
     */
    public function buildRequestWithCookies(): PendingRequest
    {
        if (!Cache::has($this->getCacheKey())) throw new TraccarException("No cookies found for this session.");
        $cachedSession = Cache::get($this->getCacheKey());
        if (!is_array($cachedSession)) throw new TraccarException("No cookies found for this session.");
        $session = $cachedSession["session"];
        return $this->withBaseUrl()->withCookies([$session["Name"] => $session["Value"]], $session["Domain"]);
    }

    public function buildRequestWithConfiguredAuth(null|string $username = null, null|string $password = null, bool $forceBasicAuth = false): PendingRequest
    {
        if (!$forceBasicAuth && $username === null && $password === null && !$this->shouldUseBasicAuth()) {
            return $this->buildRequestWithAccessToken();
        }

        return $this->withBaseUrl()->withBasicAuth($username ?? $this->email, $password ?? $this->password);
    }
}
