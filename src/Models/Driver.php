<?php
/*
 * Author: WOLF
 * Name: Driver.php
 * Modified : lun., 26 févr. 2024 10:55
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Driver extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'name',
        'uniqueId',
        'attribs',
    ];

    protected $casts = [
        'attribs' => 'array',
    ];

}
