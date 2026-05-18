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

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Session;
use Symfony\Component\HttpFoundation\Cookie;

class SessionResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function getCookies(): Cookie
    {
        $this->ensureSessionCookieExists();
        $session = $this->getSessionCache();

        return Cookie::create(
            name: $session['Name'],
            value: $session['Value'],
            domain: $session['Domain'],
        );
    }

    /**
     * @throws TraccarException
     */
    private function ensureSessionCookieExists(): void
    {
        $session = $this->getSessionCache();

        if (! $this->hasSessionCookie($session)) {
            $this->createNewSession();

            return;
        }

        $response = $this->service->get(
            request: $this->service->withBaseUrl()->withCookies(
                [$session['Name'] => $session['Value']],
                $session['Domain']
            ),
            url: 'session'
        );

        if (! $response instanceof Response || ! $response->ok()) {
            Cache::forget($this->service->getCacheKey());
            $this->createNewSession();
        }
    }

    private function hasSessionCookie(array $session): bool
    {
        return ! empty($session['Name']) && ! empty($session['Value']) && ! empty($session['Domain']);
    }

    /**
     * @throws TraccarException
     */
    public function createNewSession(): Session
    {
        $response = $this->service->session(
            request: $this->service->withBaseUrl(),
            url: 'session',
        );
        if (! $response instanceof Response || ! $response->ok()) {
            throw new TraccarException($response->toException());
        }
        $this->cacheSessionResponse($response);

        return Session::createFromValueArray($response->json());
    }

    /**
     * @throws TraccarException
     */
    public function fetchSessionInformation(?string $token = null): Session
    {
        return $this->fetchSessionInformationWithToken($token);
    }

    /**
     * @throws TraccarException
     */
    public function fetchSessionInformationWithToken(?string $token = null): Session
    {
        $token ??= $this->service->getToken();
        if (empty($token)) {
            throw new TraccarException('No session token provided.');
        }

        $response = $this->service->get(
            request: $this->service->withBaseUrl(),
            url: 'session',
            query: ['token' => $token]
        );
        if (! $response instanceof Response || ! $response->ok()) {
            throw new TraccarException($response instanceof Response ? $response->body() : 'Unexpected response.');
        }
        $this->cacheSessionResponse($response, $token);

        return Session::createFromValueArray($response->json());
    }

    /**
     * Alias for the credential-based session endpoint, POST /session.
     *
     * @throws TraccarException
     */
    public function fetchSessionInformationWithCredentials(): Session
    {
        return $this->createNewSession();
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
        if (! $response->noContent()) {
            throw new TraccarException($response->toException());
        }
        Cache::forget($this->service->getCacheKey());

        return true;
    }

    /**
     * @throws TraccarException
     */
    public function generateSessionToken(?string $expiration = null): string
    {
        $response = $this->service->post(
            request: $this->service->buildAuthorizedRequest()->asForm(),
            url: 'session/token',
            payload: array_filter(compact('expiration'), fn ($value) => $value !== null)
        );
        if (! $response->ok()) {
            throw new TraccarException($response->toException());
        }

        return $response->body();
    }

    /**
     * @throws TraccarException
     */
    public function revokeSessionToken(string $token): bool
    {
        $response = $this->service->post(
            request: $this->service->buildAuthorizedRequest()->asForm(),
            url: 'session/token/revoke',
            payload: compact('token')
        );
        if (! $response->noContent()) {
            throw new TraccarException($response->toException());
        }

        return true;
    }

    /**
     * @throws TraccarException
     */
    public function fetchOpenIdAuth(): string
    {
        $response = $this->service->get(
            request: $this->service->buildAuthorizedRequest(),
            url: 'session/openid/auth'
        );
        if (! $response->ok()) {
            throw new TraccarException($response->toException());
        }

        return $response->body();
    }

    /**
     * @throws TraccarException
     */
    public function openIdCallback(array $query = []): string
    {
        $response = $this->service->get(
            request: $this->service->buildAuthorizedRequest(),
            url: 'session/openid/callback',
            query: $this->query($query)
        );
        if (! $response->ok()) {
            throw new TraccarException($response->toException());
        }

        return $response->body();
    }

    private function cacheSessionResponse(Response $response, ?string $token = null): void
    {
        $sessionCookie = $response->cookies()->getCookieByName('JSESSIONID');

        Cache::forever($this->service->getCacheKey(), [
            'data' => $response->json(),
            'token' => $token ?? $response->json('token') ?? $this->service->getToken(),
            'session' => $sessionCookie?->toArray(),
        ]);
    }
}
