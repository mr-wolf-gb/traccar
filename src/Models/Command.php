<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Command extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'id',
        'deviceId',
        'description',
        'type',
        'textChannel',
        'attribs',
    ];

    protected $casts = [
        'id' => 'integer',
        'deviceId' => 'integer',
        'textChannel' => 'boolean',
        'attribs' => 'array',
    ];
}
