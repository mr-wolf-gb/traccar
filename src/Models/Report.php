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

/**
 * MrWolfGb\Traccar\Models\Report
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @mixin \Eloquent
 */
class Report extends Model
{
    use ArrayToModel;

    protected $guarded = [];

    protected $casts = [
        'deviceId' => 'integer',
        'geofenceId' => 'integer',
        'maxSpeed' => 'float',
        'averageSpeed' => 'float',
        'distance' => 'float',
        'spentFuel' => 'float',
        'engineHours' => 'integer',
        'duration' => 'integer',
        'startTime' => 'datetime',
        'endTime' => 'datetime',
        'startLat' => 'float',
        'startLon' => 'float',
        'endLat' => 'float',
        'endLon' => 'float',
        'lat' => 'float',
        'lon' => 'float',
    ];

}
