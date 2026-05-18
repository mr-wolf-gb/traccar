<?php

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Maintenance;

class MaintenanceResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function fetchListMaintenance(?bool $all = null, ?int $userId = null, ?int $deviceId = null, ?int $groupId = null, ?bool $refresh = null, ?int $limit = null, ?int $offset = null, ?string $keyword = null): Collection
    {
        return $this->getModelCollection('maintenance', Maintenance::class, compact('all', 'userId', 'deviceId', 'groupId', 'refresh', 'limit', 'offset', 'keyword'));
    }

    /**
     * @throws TraccarException
     */
    public function createMaintenance(array|Maintenance $maintenance): Maintenance
    {
        return $this->createModelResource('maintenance', $this->payload($maintenance), Maintenance::class);
    }

    /**
     * @throws TraccarException
     */
    public function updateMaintenance(int $id, array|Maintenance $maintenance): Maintenance
    {
        return $this->updateModelResource("maintenance/$id", $this->payload($maintenance), Maintenance::class);
    }

    /**
     * @throws TraccarException
     */
    public function deleteMaintenance(int $id): bool
    {
        return $this->deleteResource("maintenance/$id");
    }

    private function payload(array|Maintenance $maintenance): array
    {
        $payload = $maintenance instanceof Maintenance ? $maintenance->toArray() : $maintenance;
        if (array_key_exists('attribs', $payload)) {
            $payload['attributes'] = $payload['attribs'];
            unset($payload['attribs']);
        }

        return $payload;
    }
}
