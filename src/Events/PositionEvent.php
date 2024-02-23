<?php
/*
 * Author: WOLF
 * Name: PositionEvent.php
 * Modified : ven., 23 févr. 2024 14:28
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PositionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public array $position)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('traccar-websocket')
        ];
    }
}
