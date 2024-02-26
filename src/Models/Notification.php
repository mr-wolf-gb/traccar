<?php
/*
 * Author: WOLF
 * Name: Notification.php
 * Modified : lun., 26 févr. 2024 09:45
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Notification extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'type',
        'notificators',
        'always',
        'commandId',
        'calendarId',
        'attribs',
    ];

    protected $casts = [
        'attribs' => 'array',
    ];

}
