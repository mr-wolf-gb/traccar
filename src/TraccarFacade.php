<?php

namespace MrWolfGb\Traccar;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MrWolfGb\Traccar\Skeleton\SkeletonClass
 */
class TraccarFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'traccar';
    }
}
