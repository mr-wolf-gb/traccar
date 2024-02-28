<?php
/*
 * Author: WOLF
 * Name: Server.php
 * Modified : mar., 20 févr. 2024 13:24
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

/**
 * MrWolfGb\Traccar\Models\Server
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
 * @mixin \Eloquent
 */
class Server extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'id',
        'registration',
        'readonly',
        'deviceReadonly',
        'limitCommands',
        'map',
        'bingKey',
        'mapUrl',
        'poiLayer',
        'latitude',
        'longitude',
        'zoom',
        'twelveHourFormat',
        'version',
        'forceSettings',
        'coordinateFormat',
        'openIdEnabled',
        'openIdForce',
        'attribs',
    ];

    protected $casts = [
        'registration' => 'boolean',
        'readonly' => 'boolean',
        'deviceReadonly' => 'boolean',
        'limitCommands' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'zoom' => 'integer',
        'twelveHourFormat' => 'boolean',
        'forceSettings' => 'boolean',
        'openIdEnabled' => 'boolean',
        'openIdForce' => 'boolean',
        'attribs' => 'array',
    ];

}
