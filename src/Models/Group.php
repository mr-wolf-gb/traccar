<?php
/*
 * Author: WOLF
 * Name: Group.php
 * Modified : lun., 12 févr. 2024 14:04
 * Description: ...
 *
 * Copyright 2024 -[GHAITH BACCARI]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Group extends Model
{
    use ArrayToModel;

    protected $guarded = [];

}
