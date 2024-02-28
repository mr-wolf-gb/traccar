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

/**
 * MrWolfGb\Traccar\Models\User
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
 * @property array|null $attribs
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @mixin \Eloquent
 */
class User extends Model
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
