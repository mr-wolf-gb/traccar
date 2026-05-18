<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class DeviceAccumulator extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'deviceId',
        'totalDistance',
        'hours',
    ];

    protected $casts = [
        'deviceId' => 'integer',
        'totalDistance' => 'float',
        'hours' => 'float',
    ];
}
