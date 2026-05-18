<?php

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Command;
use MrWolfGb\Traccar\Models\CommandType;

class CommandResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function fetchListCommands(?bool $all = null, ?int $userId = null, ?int $deviceId = null, ?int $groupId = null, ?bool $refresh = null, ?int $limit = null, ?int $offset = null, ?string $keyword = null): Collection
    {
        return $this->getModelCollection('commands', Command::class, compact('all', 'userId', 'deviceId', 'groupId', 'refresh', 'limit', 'offset', 'keyword'));
    }

    /**
     * @throws TraccarException
     */
    public function createCommand(array|Command $command): Command
    {
        return $this->createModelResource('commands', $this->payload($command), Command::class);
    }

    /**
     * @throws TraccarException
     */
    public function updateCommand(int $id, array|Command $command): Command
    {
        return $this->updateModelResource("commands/$id", $this->payload($command), Command::class);
    }

    /**
     * @throws TraccarException
     */
    public function deleteCommand(int $id): bool
    {
        return $this->deleteResource("commands/$id");
    }

    /**
     * @throws TraccarException
     */
    public function fetchSupportedCommands(int $deviceId): Collection
    {
        return $this->getModelCollection('commands/send', Command::class, compact('deviceId'));
    }

    /**
     * @throws TraccarException
     */
    public function dispatchCommand(array|Command $command, ?int $groupId = null): Command|Collection
    {
        $response = $this->ensureOk($this->service->post(
            request: $this->service->buildAuthorizedRequest()->withQueryParameters(array_filter(compact('groupId'), fn($value) => $value !== null)),
            url: 'commands/send',
            payload: $this->payload($command)
        ));

        $data = $response->json();
        if (!is_array($data)) {
            $data = [];
        }

        return array_is_list($data) ? Command::createFromValueList($data) : Command::createFromValueArray($data);
    }

    /**
     * @throws TraccarException
     */
    public function fetchCommandTypes(?int $deviceId = null, ?bool $textChannel = null): Collection
    {
        return $this->getModelCollection('commands/types', CommandType::class, compact('deviceId', 'textChannel'));
    }

    private function payload(array|Command $command): array
    {
        $payload = $command instanceof Command ? $command->toArray() : $command;
        if (array_key_exists('attribs', $payload)) {
            $payload['attributes'] = $payload['attribs'];
            unset($payload['attribs']);
        }

        return $payload;
    }
}
