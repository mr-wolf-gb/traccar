<?php
/*
 * Author: WOLF
 * Name: Event.php
 * Modified : lun., 12 févr. 2024 14:08
 * Description: ...
 *
 * Copyright 2024 -[GHAITH BACCARI]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Event extends Model
{
    use ArrayToModel;

    protected $guarded = [];

}
