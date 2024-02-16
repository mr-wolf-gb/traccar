<?php
/*
 * Author: WOLF
 * Name: Device.php
 * Modified : ven., 16 févr. 2024 14:57
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
        'data' => 'array'
    ];

    public function __construct(array $attributes = [])
    {
        if (!isset($this->connection)) {
            $this->setConnection(config('traccar.database.connection'));
        }
        parent::__construct($attributes);
    }

    public function createOrUpdate()
    {
        $device = static::where('uniqueId', $this->uniqueId)->first();
        if ($device) {
            $device->update([
                'name' => $this->name,
                'uniqueId' => $this->uniqueId,
                'data' => $this->attributes->except('id', 'name', 'uniqueId', 'data'),
            ]);
        } else {
            $device = Device::create([
                'id' => $this->id,
                'name' => $this->name,
                'uniqueId' => $this->uniqueId,
                'data' => $this->attributes->except('id', 'name', 'uniqueId', 'data'),
            ]);
        }
        return $device;
    }

    public function scopeWhereName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }

    protected function lastUpdate(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : $value,
            set: fn(?string $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : $value,
        );
    }

}
