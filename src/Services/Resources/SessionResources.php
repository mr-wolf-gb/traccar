<?php
/*
 * Author: WOLF
 * Name: SessionResources.php
 * Modified : ven., 16 févr. 2024 14:45
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Cookie\CookieJar;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Session;
use MrWolfGb\Traccar\Services\TraccarService;
use Symfony\Component\HttpFoundation\Cookie;

class SessionResources
{

    public function __construct(public TraccarService $service)
    {
    }

    /**
     * @throws TraccarException
     */
    public function fetchServerInformation(): array
    {
        $response = $this->service->get(
            request: $this->service->buildRequestWithAccessToken(),
            url: 'server'
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return $response->json();
    }

    /**
     * @return Session
     * @throws TraccarException
     */
    public function createNewSession(): Session
    {
        $response = $this->service->session(
            request: $this->service->withBaseUrl(),
            url: 'session',
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Session::createFromValueArray(Cache::rememberForever($this->service->getCacheKey(), function () use ($response) {
            return [
                'data' => $response->json(),
                'token' => $response->json("token"),
                'session' => $response->cookies()->getCookieByName('JSESSIONID')->toArray() ?? null
            ];
        }));
    }

    /**
     * @return Session
     * @throws TraccarException
     */
    public function fetchSessionInformation(): Session
    {
        $response = $this->service->get(
            request: $this->service->buildRequestWithAccessToken(),
            url: 'session'
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Session::createFromValueArray(Cache::rememberForever($this->service->getCacheKey(), function () use ($response) {
            return [
                'data' => $response->json(),
                'token' => $response->json("token"),
                'session' => $response->cookies()->getCookieByName('JSESSIONID')->toArray() ?? null
            ];
        }));
    }

    public function getCookies(): Application|CookieJar|Cookie|\Illuminate\Contracts\Foundation\Application
    {
        $session = Cache::get($this->service->getCacheKey())["session"];
        return cookie(
            name: $session["Name"],
            value: $session["Value"],
            path: $session["Path"],
            domain: $session["Domain"],
            secure: false,
            httpOnly: false,
        );
    }
}
