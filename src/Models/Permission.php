<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Permission extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'userId',
        'deviceId',
        'groupId',
        'geofenceId',
        'notificationId',
        'calendarId',
        'attributeId',
        'driverId',
        'managedUserId',
        'commandId',
    ];

    protected $casts = [
        'userId' => 'integer',
        'deviceId' => 'integer',
        'groupId' => 'integer',
        'geofenceId' => 'integer',
        'notificationId' => 'integer',
        'calendarId' => 'integer',
        'attributeId' => 'integer',
        'driverId' => 'integer',
        'managedUserId' => 'integer',
        'commandId' => 'integer',
    ];
}
