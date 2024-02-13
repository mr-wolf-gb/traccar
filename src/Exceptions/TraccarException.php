<?php
/*
 * Author: WOLF
 * Name: TraccarException.php
 * Modified : mar., 13 févr. 2024 08:38
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Exceptions;

use Exception;

class TraccarException extends Exception
{
    public function __construct(Exception|string $exception)
    {
        if (is_string($exception)) {
            parent::__construct($exception, 404);
        } else {
            parent::__construct($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }
}
