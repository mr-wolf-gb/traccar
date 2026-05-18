<?php

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class NotificationMessage extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'subject',
        'digest',
        'body',
        'priority',
    ];

    protected $casts = [
        'priority' => 'boolean',
    ];
}
