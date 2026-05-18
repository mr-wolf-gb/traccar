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

/**
 * MrWolfGb\Traccar\Models\Session
 *
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property bool $readonly
 * @property bool $administrator
 * @property bool $twelveHourFormat
 * @property bool $disabled
 * @property bool $deviceReadonly
 * @property bool $limitCommands
 * @property bool $fixedEmail
 * @property \Illuminate\Support\Carbon|null $expirationTime
 * @property bool $poiLayer
 * @property array|null $attribs
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Session newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Session newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Session query()
 * @mixin \Eloquent
 */
class Session extends Model
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
        'id' => 'integer',
        'readonly' => 'boolean',
        'administrator' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'zoom' => 'integer',
        'twelveHourFormat' => 'boolean',
        'disabled' => 'boolean',
        'deviceReadonly' => 'boolean',
        'limitCommands' => 'boolean',
        'fixedEmail' => 'boolean',
        'expirationTime' => 'datetime',
        'deviceLimit' => 'integer',
        'userLimit' => 'integer',
        'attribs' => 'array',
    ];

}
