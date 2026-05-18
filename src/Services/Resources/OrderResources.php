<?php

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Order;

class OrderResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function fetchListOrders(?bool $all = null, ?int $userId = null, ?bool $excludeAttributes = null, ?int $limit = null, ?int $offset = null, ?string $keyword = null): Collection
    {
        return $this->getModelCollection('orders', Order::class, compact('all', 'userId', 'excludeAttributes', 'limit', 'offset', 'keyword'));
    }

    /**
     * @throws TraccarException
     */
    public function createOrder(array|Order $order): Order
    {
        return $this->createModelResource('orders', $this->payload($order), Order::class);
    }

    /**
     * @throws TraccarException
     */
    public function updateOrder(int $id, array|Order $order): Order
    {
        return $this->updateModelResource("orders/$id", $this->payload($order), Order::class);
    }

    /**
     * @throws TraccarException
     */
    public function deleteOrder(int $id): bool
    {
        return $this->deleteResource("orders/$id");
    }

    private function payload(array|Order $order): array
    {
        $payload = $order instanceof Order ? $order->toArray() : $order;
        if (array_key_exists('attribs', $payload)) {
            $payload['attributes'] = $payload['attribs'];
            unset($payload['attribs']);
        }

        return $payload;
    }
}
