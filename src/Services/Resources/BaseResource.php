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

use Illuminate\Support\Facades\Cache;
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
}
