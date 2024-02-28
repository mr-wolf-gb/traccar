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

/**
 * MrWolfGb\Traccar\Models\Group
 *
 * @property string $name
 * @property string $groupId
 * @property array|null $attribs
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @mixin \Eloquent
 */
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
