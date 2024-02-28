<?php
/*
 * Author: WOLF
 * Name: Driver.php
 * Modified : lun., 26 févr. 2024 10:55
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

/**
 * MrWolfGb\Traccar\Models\Driver
 *
 * @property string $name
 * @property string $uniqueId
 * @property array|null $attribs
 * @method static \Illuminate\Database\Eloquent\Builder|Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver query()
 * @mixin \Eloquent
 */
class Driver extends Model
{
    use ArrayToModel;

    protected $fillable = [
        'name',
        'uniqueId',
        'attribs',
    ];

    protected $casts = [
        'attribs' => 'array',
    ];

}
