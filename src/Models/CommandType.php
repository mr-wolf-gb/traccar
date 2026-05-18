<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class CommandType extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'type',
    ];
}
