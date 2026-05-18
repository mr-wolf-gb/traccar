<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Calendar extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'id',
        'name',
        'data',
        'attribs',
    ];

    protected $casts = [
        'id' => 'integer',
        'attribs' => 'array',
    ];
}
