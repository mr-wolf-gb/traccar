<?php
/*
 * Author: WOLF
 * Name: SessionResources.php
 * Modified : mar., 13 févr. 2024 09:16
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Session;
use MrWolfGb\Traccar\Services\TraccarService;

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
        return Session::createFromValueArray([
            'data' => $response->json(),
            'session' => $response->cookies()->getCookieByName('JSESSIONID')->toArray() ?? null
        ]);
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
        return Session::createFromValueArray([
            'data' => $response->json(),
            'session' => $response->cookies()->getCookieByName('JSESSIONID')->toArray() ?? null
        ]);
    }
}
