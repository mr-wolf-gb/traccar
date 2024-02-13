<?php
/*
 * Author: WOLF
 * Name: User.php
 * Modified : lun., 12 févr. 2024 14:06
 * Description: ...
 *
 * Copyright 2024 -[GHAITH BACCARI]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class User extends Model
{
    use ArrayToModel;

    protected $guarded = [];

}
