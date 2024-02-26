<?php
/*
 * Author: WOLF
 * Name: GroupResources.php
 * Modified : lun., 26 févr. 2024 11:34
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Group;

class GroupResources extends BaseResource
{

    /**
     * @param bool $all
     * @param int|null $userId
     * @return Collection
     * @throws TraccarException
     */
    public function fetchListGroups(bool $all = true, ?int $userId = null): Collection
    {
        $query = ["all" => $all];
        if ($userId != null) {
            $query["userId"] = $userId;
        }
        $response = $this->service->get(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'groups',
            query: urldecode(http_build_query($query, '', '&'))
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Group::createFromValueList($response->json());
    }

    /**
     * @param string $name
     * @param int $groupId
     * @param array $attribs
     * @return Group
     * @throws TraccarException
     */
    public function createGroup(string $name, int $groupId = 0, array $attribs = []): Group
    {
        if (empty($name)) {
            throw new TraccarException("Name cannot be empty !");
        }
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'groups',
            payload: [
                "name" => $name,
                "groupId" => $groupId,
                "attributes" => empty($attribs) ? null : $attribs
            ]
        );
        if (!$response->ok()) {
            if ($response->badRequest())
                throw new TraccarException("No permission");
            else
                throw new TraccarException($response->toException());
        }
        return Group::createFromValueArray($response->json());
    }

    /**
     * @param Group $group
     * @return Group
     * @throws TraccarException
     */
    public function createNewGroup(Group $group): Group
    {
        if (empty($group->name)) {
            throw new TraccarException("Name cannot be empty !");
        }
        $postData = $group->toArray();
        $postData["attributes"] = empty($postData["attribs"]) ? null : $postData["attribs"];
        unset($postData["attribs"]);
        $response = $this->service->post(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'groups',
            payload: $postData
        );
        if (!$response->ok()) {
            if ($response->badRequest())
                throw new TraccarException("No permission");
            else
                throw new TraccarException($response->toException());
        }
        return Group::createFromValueArray($response->json());
    }

    /**
     * @param Group $group
     * @return Group
     * @throws TraccarException
     */
    public function updateGroup(Group $group): Group
    {
        $putData = $group->toArray();
        $putData["attributes"] = empty($putData["attribs"]) ? null : $putData["attribs"];
        unset($putData["attribs"]);
        $response = $this->service->put(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'groups/' . $group->id,
            payload: $putData
        );
        if (!$response->ok()) {
            throw new TraccarException($response->toException());
        }
        return Group::createFromValueArray($response->json());
    }

    /**
     * @param int|Group $group
     * @return bool
     * @throws TraccarException
     */
    public function deleteGroup(int|Group $group): bool
    {
        $response = $this->service->delete(
            request: $this->service->buildRequestWithBasicAuth(),
            url: 'groups/' . ($group instanceof Group ? $group->id : $group)
        );
        if (!$response->noContent()) {
            throw new TraccarException($response->toException());
        }
        return true;
    }
}
