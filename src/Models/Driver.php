<?php
/*
 * Author: WOLF
 * Name: Driver.php
 * Modified : mar., 13 févr. 2024 08:20
 * Description: ...
 *
 * Copyright 2024 -[GHAITH BACCARI]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Driver extends Model
{
    use ArrayToModel;

    protected $guarded = [];

}
