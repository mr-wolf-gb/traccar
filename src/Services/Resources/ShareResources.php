<?php

namespace MrWolfGb\Traccar\Services\Resources;

use MrWolfGb\Traccar\Exceptions\TraccarException;

class ShareResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function shareDevice(int $deviceId, string $expiration): string
    {
        return $this->share('share/device', compact('deviceId', 'expiration'));
    }

    /**
     * @throws TraccarException
     */
    public function shareGroup(int $groupId, string $expiration): string
    {
        return $this->share('share/group', compact('groupId', 'expiration'));
    }

    /**
     * @throws TraccarException
     */
    private function share(string $url, array $payload): string
    {
        $response = $this->ensureOk($this->service->post(
            request: $this->service->buildAuthorizedRequest()->asForm(),
            url: $url,
            payload: $payload
        ));

        return $response->body();
    }
}
