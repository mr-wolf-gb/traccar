<?php
/*
 * Author: WOLF
 * Name: Report.php
 * Modified : lun., 26 févr. 2024 12:24
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Report extends Model
{
    use ArrayToModel;

    protected $guarded = [];

}
