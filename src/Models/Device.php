<?php
/*
 * Author: WOLF
 * Name: Device.php
 * Modified : mar., 27 févr. 2024 12:31
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use MrWolfGb\Traccar\Trait\ArrayToModel;

/**
 * MrWolfGb\Traccar\Models\Device
 *
 * @property int $id
 * @property string $name
 * @property string $uniqueId
 * @property string|null $status
 * @property bool|null $disabled
 * @property Carbon|null $lastUpdate
 * @property int|null $positionId
 * @property int|null $groupId
 * @property string|null $phone
 * @property string|null $model
 * @property string|null $contact
 * @property string|null $category
 * @property array|null $attribs
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Device newModelQuery()
 * @method static Builder|Device newQuery()
 * @method static Builder|Device query()
 * @method static Builder|Device whereAttribs($value)
 * @method static Builder|Device whereCategory($value)
 * @method static Builder|Device whereContact($value)
 * @method static Builder|Device whereCreatedAt($value)
 * @method static Builder|Device whereDisabled($value)
 * @method static Builder|Device whereGroupId($value)
 * @method static Builder|Device whereId($value)
 * @method static Builder|Device whereLastUpdate($value)
 * @method static Builder|Device whereModel($value)
 * @method static Builder|Device whereName($value)
 * @method static Builder|Device wherePhone($value)
 * @method static Builder|Device wherePositionId($value)
 * @method static Builder|Device whereStatus($value)
 * @method static Builder|Device whereUniqueId($value)
 * @method static Builder|Device whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    /** @phpstan-ignore-next-line */
    public function __construct(array $attributes = [])
    {
        if (!isset($this->connection)) {
            if (is_string(config('traccar.database.connection')))
                $this->setConnection(config('traccar.database.connection'));
        }
        parent::__construct($attributes);
    }

    /**
     * @return Device
     */
    public function createOrUpdate(): Device
    {
        $device = static::where('uniqueId', '=', $this->uniqueId)->first();
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

}
