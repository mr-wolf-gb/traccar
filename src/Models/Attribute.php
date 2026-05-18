<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Attribute extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'id',
        'description',
        'attribute',
        'expression',
        'type',
    ];

    protected $casts = [
        'id' => 'integer',
    ];
}
