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
    protected $fillable = [
        'name',
        'uniqueId',
        'status',
        'disabled',
        'lastUpdate',
        'positionId',
        'groupId',
        'phone',
        'model',
        'contact',
        'category',
        'attribs',
    ];

    protected $casts = [
        'disabled' => 'boolean',
        'lastUpdate' => 'datetime', // ISO 8601 format : Y-m-d\TH:i:s\Z
        'attribs' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        if (!isset($this->connection)) {
            $this->setConnection(config('traccar.database.connection'));
        }
        parent::__construct($attributes);
    }

    public function createOrUpdate() : Device
    {
        $device = static::where('uniqueId', $this->uniqueId)->first();
        if ($device) {
            $device->update([
                'name' => $this->name,
                'uniqueId' => $this->uniqueId,
                'status' => $this->status,
                'disabled' => $this->disabled,
                'lastUpdate' => $this->lastUpdate,
                'positionId' => $this->positionId,
                'groupId' => $this->groupId,
                'phone' => $this->phone,
                'model' => $this->model,
                'contact' => $this->contact,
                'category' => $this->category,
                'attribs' => $this->attribs,
            ]);
        } else {
            $device = Device::create([
                'name' => $this->name,
                'uniqueId' => $this->uniqueId,
                'status' => $this->status,
                'disabled' => $this->disabled,
                'lastUpdate' => $this->lastUpdate,
                'positionId' => $this->positionId,
                'groupId' => $this->groupId,
                'phone' => $this->phone,
                'model' => $this->model,
                'contact' => $this->contact,
                'category' => $this->category,
                'attribs' => $this->attribs,
            ]);
        }
        return $device;
    }

    public function scopeWhereName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }

    public function scopeWhereUniqueId(Builder $query, string $uniqueId): Builder
    {
        return $query->where('uniqueId', $uniqueId);
    }

    protected function lastUpdate(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : $value,
            set: fn(?string $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : $value,
        );
    }

}
