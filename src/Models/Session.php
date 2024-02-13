<?php
/*
 * Author: WOLF
 * Name: Session.php
 * Modified : mar., 13 févr. 2024 09:12
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Session extends Model
{
    use ArrayToModel;

    protected $guarded = [];

}
