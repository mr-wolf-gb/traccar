<?php
/*
 * Author: WOLF
 * Name: Group.php
 * Modified : mer., 21 févr. 2024 11:29
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Group extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'name',
        'groupId',
        'attribs',
    ];

    protected $casts = [
        'attribs' => 'array',
    ];

}
