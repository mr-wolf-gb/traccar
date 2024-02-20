<?php
/*
 * Author: WOLF
 * Name: Session.php
 * Modified : mar., 20 févr. 2024 14:04
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

    protected $fillable = [
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
        'attributes',
    ];

    protected $casts = [
        'readonly' => 'boolean',
        'administrator' => 'boolean',
        'twelveHourFormat' => 'boolean',
        'disabled' => 'boolean',
        'deviceReadonly' => 'boolean',
        'limitCommands' => 'boolean',
        'fixedEmail' => 'boolean',
        'attributes' => 'array',
    ];

    protected $dates = ['expirationTime'];

}
