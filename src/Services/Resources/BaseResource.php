<?php
/*
 * Author: WOLF
 * Name: BaseResource.php
 * Modified : lun., 26 févr. 2024 11:36
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use MrWolfGb\Traccar\Services\TraccarService;
use MrWolfGb\Traccar\Trait\UrlQueryHelper;

abstract class BaseResource
{
    use UrlQueryHelper;

    public function __construct(public TraccarService $service)
    {
    }
}
