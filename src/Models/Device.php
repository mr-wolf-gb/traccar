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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use MrWolfGb\Traccar\Trait\ArrayToModel;

class Device extends Model
{
    use ArrayToModel;

    protected $table = "traccar_devices";
    protected $guarded = [];
    protected $casts = [
        'attribs' => 'array',
        'geofenceIds' => 'array',
        'lastUpdate' => 'timestamp'
    ];

    protected function lastUpdate(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : $value,
            set: fn (?string $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : $value,
        );
    }

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('traccar.database.connection'));
        }
        parent::__construct($attributes);
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

    public function scopeWhereName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }

}
