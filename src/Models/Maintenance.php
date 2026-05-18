<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Maintenance extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'id',
        'name',
        'type',
        'start',
        'period',
        'attribs',
    ];

    protected $casts = [
        'id' => 'integer',
        'start' => 'float',
        'period' => 'float',
        'attribs' => 'array',
    ];
}
