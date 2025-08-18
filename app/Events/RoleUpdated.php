<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $data;
    public int $userId;

    public function __construct(int $userId, array $data)
    {
        $this->userId = $userId;
        $this->data = $data;
    }

    public function broadcastOn(): Channel
    {
        // Notify only the affected user
        return new Channel('user-notifications-' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'role-updated';
    }
}
