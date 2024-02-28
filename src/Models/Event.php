<?php
/*
 * Author: WOLF
 * Name: Event.php
 * Modified : lun., 26 févr. 2024 11:06
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

/**
 * MrWolfGb\Traccar\Models\Event
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $eventTime
 * @property string $deviceId
 * @property string $positionId
 * @property string $geofenceId
 * @property string $maintenanceId
 * @property array|null $attribs
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @mixin \Eloquent
 */
class Event extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'type',
        'eventTime',
        'deviceId',
        'positionId',
        'geofenceId',
        'maintenanceId',
        'attribs',
    ];

    protected $casts = [
        'eventTime' => 'datetime', // ISO 8601 format : Y-m-d\TH:i:s\Z
        'attribs' => 'array',
    ];

}
