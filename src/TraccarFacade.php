<?php
/*
 * Author: WOLF
 * Name: TraccarFacade.php
 * Modified : mar., 27 févr. 2024 11:29
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar;

use Illuminate\Support\Facades\Facade;

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
