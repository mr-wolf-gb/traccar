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

/**
 * MrWolfGb\Traccar\Models\Notification
 *
 * @property string $type
 * @property string $notificators
 * @property bool $always
 * @property string $commandId
 * @property string $calendarId
 * @property array|null $attribs
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @mixin \Eloquent
 */
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
