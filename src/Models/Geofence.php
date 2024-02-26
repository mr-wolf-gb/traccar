<?php
/*
 * Author: WOLF
 * Name: Geofence.php
 * Modified : lun., 26 févr. 2024 08:16
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Geofence extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'name',
        'description',
        'area',
        'calendarId',
        'attribs',
    ];

    protected $casts = [
        'attribs' => 'array',
    ];

}
