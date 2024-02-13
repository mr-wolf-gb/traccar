<?php
/*
 * Author: WOLF
 * Name: Geofence.php
 * Modified : lun., 12 févr. 2024 14:09
 * Description: ...
 *
 * Copyright 2024 -[GHAITH BACCARI]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Geofence extends Model
{
    use ArrayToModel;

    protected $guarded = [];

}
