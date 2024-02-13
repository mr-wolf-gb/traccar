<?php
/*
 * Author: WOLF
 * Name: Device.php
 * Modified : mar., 13 févr. 2024 14:33
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Device extends Model
{
    use ArrayToModel;

    protected $table = "traccar_devices";
    protected $guarded = [];
    protected $casts = [
        'attribs' => 'array',
        'geofenceIds' => 'array'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('traccar.database.connection') ?: parent::getConnection();
    }

    public function createOrUpdate()
    {
        $check = static::where('uniqueId', $this->uniqueId)->first();
        if ($check) {
            $check->update($this->attributes);
        } else {
            $check = Device::create($this->attributes);
        }
        return $check;
    }

}
