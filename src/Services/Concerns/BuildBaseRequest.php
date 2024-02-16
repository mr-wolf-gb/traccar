<?php
/*
 * Author: WOLF
 * Name: BuildBaseRequest.php
 * Modified : ven., 16 févr. 2024 14:45
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

trait BuildBaseRequest
{
    public function buildRequestWithAccessToken(): PendingRequest
    {
        $authArray = Cache::get($this->getCacheKey());
        return $this->withBaseUrl()->withQueryParameters(["token" => $authArray["token"] ?: config('traccar.token')]);
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
