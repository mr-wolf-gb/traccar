<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Statistic extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'captureTime',
        'activeUsers',
        'activeDevices',
        'requests',
        'messagesReceived',
        'messagesStored',
    ];

    protected $casts = [
        'captureTime' => 'datetime',
        'activeUsers' => 'integer',
        'activeDevices' => 'integer',
        'requests' => 'integer',
        'messagesReceived' => 'integer',
        'messagesStored' => 'integer',
    ];
}
