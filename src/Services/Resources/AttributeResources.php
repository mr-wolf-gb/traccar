<?php

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Attribute;

class AttributeResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function fetchListComputedAttributes(?bool $all = null, ?int $userId = null, ?int $deviceId = null, ?int $groupId = null, ?bool $refresh = null, ?int $limit = null, ?int $offset = null, ?string $keyword = null): Collection
    {
        return $this->getModelCollection('attributes/computed', Attribute::class, compact('all', 'userId', 'deviceId', 'groupId', 'refresh', 'limit', 'offset', 'keyword'));
    }

    /**
     * @throws TraccarException
     */
    public function createComputedAttribute(array|Attribute $attribute): Attribute
    {
        return $this->createModelResource('attributes/computed', $attribute instanceof Attribute ? $attribute->toArray() : $attribute, Attribute::class);
    }

    /**
     * @throws TraccarException
     */
    public function updateComputedAttribute(int $id, array|Attribute $attribute): Attribute
    {
        return $this->updateModelResource("attributes/computed/$id", $attribute instanceof Attribute ? $attribute->toArray() : $attribute, Attribute::class);
    }

    /**
     * @throws TraccarException
     */
    public function deleteComputedAttribute(int $id): bool
    {
        return $this->deleteResource("attributes/computed/$id");
    }
}
