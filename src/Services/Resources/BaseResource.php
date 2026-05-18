<?php
/*
 * Author: WOLF
 * Name: BaseResource.php
 * Modified : lun., 26 févr. 2024 12:33
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Services\TraccarService;
use MrWolfGb\Traccar\Trait\UrlQueryHelper;

/**
 *
 */
abstract class BaseResource
{
    use UrlQueryHelper;

    /**
     * @param TraccarService $service
     */
    public function __construct(public TraccarService $service)
    {
    }

    protected function getSessionCache(): array
    {
        $emptyArray = ['data' => '', 'token' => '', 'session' => ''];
        $cachedSession = Cache::get($this->service->getCacheKey());
        if (!is_array($cachedSession)) return $emptyArray;
        if (!array_key_exists('session', $cachedSession)) return $emptyArray;
        return $cachedSession["session"];
    }

    protected function query(array $query): string
    {
        return urldecode(http_build_query(array_filter($query, fn($value) => $value !== null), '', '&'));
    }

    /**
     * @throws TraccarException
     */
    protected function ensureOk(PromiseInterface|Response $response): Response
    {
        if (!$response instanceof Response || !$response->successful()) {
            throw new TraccarException($response instanceof Response ? $response->toException() : 'Unexpected response.');
        }

        return $response;
    }

    /**
     * @throws TraccarException
     */
    protected function ensureNoContent(PromiseInterface|Response $response): bool
    {
        if (!$response instanceof Response || (!$response->noContent() && !$response->successful())) {
            throw new TraccarException($response instanceof Response ? $response->toException() : 'Unexpected response.');
        }

        return true;
    }

    /**
     * @return Collection<int|string, mixed>
     * @throws TraccarException
     */
    protected function getCollection(string $url, array $query = []): Collection
    {
        $response = $this->ensureOk($this->service->get(
            request: $this->service->buildAuthorizedRequest(),
            url: $url,
            query: $this->query($query)
        ));

        $data = $response->json();

        return collect(is_array($data) ? $data : []);
    }

    /**
     * @template T of Model
     * @param class-string<T> $modelClass
     * @return Collection<int, T>
     * @throws TraccarException
     */
    protected function getModelCollection(string $url, string $modelClass, array $query = []): Collection
    {
        $response = $this->ensureOk($this->service->get(
            request: $this->service->buildAuthorizedRequest(),
            url: $url,
            query: $this->query($query)
        ));

        /** @phpstan-ignore-next-line */
        $collection = $modelClass::createFromValueList($response->json());

        return $collection;
    }

    /**
     * @template T of Model
     * @param class-string<T> $modelClass
     * @return T
     * @throws TraccarException
     */
    protected function createModelResource(string $url, array $payload, string $modelClass): Model
    {
        $response = $this->ensureOk($this->service->post(
            request: $this->service->buildAuthorizedRequest(),
            url: $url,
            payload: $payload
        ));

        /** @phpstan-ignore-next-line */
        $model = $modelClass::createFromValueArray($response->json());

        return $model;
    }

    /**
     * @template T of Model
     * @param class-string<T> $modelClass
     * @return T
     * @throws TraccarException
     */
    protected function updateModelResource(string $url, array $payload, string $modelClass): Model
    {
        $response = $this->ensureOk($this->service->put(
            request: $this->service->buildAuthorizedRequest(),
            url: $url,
            payload: $payload
        ));

        /** @phpstan-ignore-next-line */
        $model = $modelClass::createFromValueArray($response->json());

        return $model;
    }

    /**
     * @throws TraccarException
     */
    protected function getArray(string $url, array $query = []): array
    {
        $response = $this->ensureOk($this->service->get(
            request: $this->service->buildAuthorizedRequest(),
            url: $url,
            query: $this->query($query)
        ));

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    /**
     * @throws TraccarException
     */
    protected function createResource(string $url, array $payload): array
    {
        $response = $this->ensureOk($this->service->post(
            request: $this->service->buildAuthorizedRequest(),
            url: $url,
            payload: $payload
        ));

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    /**
     * @throws TraccarException
     */
    protected function updateResource(string $url, array $payload): array
    {
        $response = $this->ensureOk($this->service->put(
            request: $this->service->buildAuthorizedRequest(),
            url: $url,
            payload: $payload
        ));

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    /**
     * @throws TraccarException
     */
    protected function deleteResource(string $url, array $payload = []): bool
    {
        return $this->ensureNoContent($this->service->delete(
            request: $this->service->buildAuthorizedRequest(),
            url: $url,
            payload: $payload
        ));
    }
}
