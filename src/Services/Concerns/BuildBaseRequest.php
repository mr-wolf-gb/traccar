<?php
/*
 * Author: WOLF
 * Name: BuildBaseRequest.php
 * Modified : lun., 12 févr. 2024 14:00
 * Description: ...
 *
 * Copyright 2024 -[GHAITH BACCARI]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

trait BuildBaseRequest
{
    public function buildRequestWithAccessToken(): PendingRequest
    {
        $authArray = Cache::get('traccar_auth_array');
        return $this->withBaseUrl()->withQueryParameters(["token" => $authArray["token"]]);
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
