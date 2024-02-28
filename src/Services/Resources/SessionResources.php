<?php
/*
 * Author: WOLF
 * Name: SessionResources.php
 * Modified : mar., 27 févr. 2024 12:17
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Facades\Cache;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Session;
use Symfony\Component\HttpFoundation\Cookie;

class SessionResources extends BaseResource
{
    public function getCookies(): Cookie
    {
        $this->checkSessionID();
        $session = $this->getSessionCache();
        return Cookie::create(
            name: $session["Name"],
            value: $session["Value"],
            domain: $session["Domain"],
        );
    }


    private function checkSessionID(): void
    {
        $session = $this->getSessionCache();
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
                'session' => $response->cookies()->getCookieByName('JSESSIONID')->toArray() ?? null //@phpstan-ignore-line
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
                'session' => $response->cookies()->getCookieByName('JSESSIONID')->toArray() ?? null //@phpstan-ignore-line
            ];
        }));
    }

    /**
     * @throws TraccarException
     */
    public function closeSession(): bool
    {
        $response = $this->service->delete(
            request: $this->service->buildRequestWithCookies(),
            url: 'session'
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        Cache::forget($this->service->getCacheKey());
        return true;
    }
}
