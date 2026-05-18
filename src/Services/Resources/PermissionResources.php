<?php

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Permission;

class PermissionResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function fetchPermissions(array $query = []): Collection
    {
        return $this->getModelCollection('permissions', Permission::class, $query);
    }

    /**
     * @throws TraccarException
     */
    public function linkPermission(array|Permission $permission): bool
    {
        return $this->ensureNoContent($this->service->post(
            request: $this->service->buildAuthorizedRequest(),
            url: 'permissions',
            payload: $permission instanceof Permission ? $permission->toArray() : $permission
        ));
    }

    /**
     * @throws TraccarException
     */
    public function unlinkPermission(array|Permission $permission): bool
    {
        return $this->deleteResource('permissions', $permission instanceof Permission ? $permission->toArray() : $permission);
    }
}
