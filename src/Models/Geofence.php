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

/**
 * MrWolfGb\Traccar\Models\Geofence
 *
 * @property string $name
 * @property string $description
 * @property string $area
 * @property string $calendarId
 * @property array|null $attribs
 * @method static \Illuminate\Database\Eloquent\Builder|Geofence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Geofence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Geofence query()
 * @mixin \Eloquent
 */
class Geofence extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'id',
        'name',
        'description',
        'area',
        'calendarId',
        'attribs',
    ];

    protected $casts = [
        'id' => 'integer',
        'calendarId' => 'integer',
        'attribs' => 'array',
    ];

}
