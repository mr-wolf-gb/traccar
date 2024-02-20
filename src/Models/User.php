<?php
/*
 * Author: WOLF
 * Name: User.php
 * Modified : mar., 20 févr. 2024 13:22
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class User extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'readonly',
        'administrator',
        'map',
        'latitude',
        'longitude',
        'zoom',
        'password',
        'twelveHourFormat',
        'coordinateFormat',
        'disabled',
        'expirationTime',
        'deviceLimit',
        'userLimit',
        'deviceReadonly',
        'limitCommands',
        'fixedEmail',
        'poiLayer',
        'attribs',
    ];

    protected $casts = [
        'readonly' => 'boolean',
        'administrator' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'zoom' => 'integer',
        'twelveHourFormat' => 'boolean',
        'disabled' => 'boolean',
        'expirationTime' => 'datetime',
        'deviceReadonly' => 'boolean',
        'limitCommands' => 'boolean',
        'fixedEmail' => 'boolean',
        'attribs' => 'array',
    ];

}
