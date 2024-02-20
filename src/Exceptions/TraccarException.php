<?php
/*
 * Author: WOLF
 * Name: TraccarException.php
 * Modified : mar., 20 févr. 2024 13:15
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Exceptions;

use Exception;

class TraccarException extends Exception
{
    public function __construct(null|Exception|string $exception)
    {
        if (is_string($exception)) {
            parent::__construct($exception, 404);
        } elseif (is_null($exception)) {
            parent::__construct("No error message", 404);
        } else {
            parent::__construct($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }
}
