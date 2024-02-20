<?php
/*
 * Author: WOLF
 * Name: SessionResources.php
 * Modified : mar., 20 févr. 2024 14:07
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

    public function getCookies(): Application|CookieJar|Cookie|\Illuminate\Contracts\Foundation\Application
    {
        $this->checkSessionID();
        $session = Cache::get($this->service->getCacheKey())["session"];
        return cookie(
            name: $session["Name"],
            value: $session["Value"]
        );
    }


    private function checkSessionID(): void
    {
        $session = Cache::get($this->service->getCacheKey())["session"];
        $response = $this->service->get(
            request: $this->service->withBaseUrl(),
            url: 'session/check-sid',
            query: [
                'sid' => explode('.', $session["Value"])[0]
            ]
        );
        try {
            if ($response->json("status") === false) {
                Cache::forget($this->service->getCacheKey());
                $this->createNewSession();
            }
        } catch (TraccarException $e) {
        }
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
            throw new TraccarException($response->body());
        }
        return Session::createFromValueArray(Cache::rememberForever($this->service->getCacheKey(), function () use ($response) {
            return [
                'data' => $response->json(),
                'token' => $response->json("token"),
                'session' => $response->cookies()->getCookieByName('JSESSIONID')->toArray() ?? null
            ];
        }));
    }
}
