<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Order extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'id',
        'uniqueId',
        'description',
        'fromAddress',
        'toAddress',
        'attribs',
    ];

    protected $casts = [
        'id' => 'integer',
        'attribs' => 'array',
    ];
}
