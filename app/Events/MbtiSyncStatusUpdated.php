<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MbtiSyncStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $status;
    public $result;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $status, $result = null, $message = null)
    {
        $this->userId = $userId;
        $this->status = $status;
        $this->result = $result;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('mbti-sync-status.' . $this->userId),
        ];
    }

    /**
     * Nama event untuk broadcasting
     */
    public function broadcastAs(): string
    {
        return 'mbti.sync.status.updated';
    }

    /**
     * Data yang akan di-broadcast
     */
    public function broadcastWith(): array
    {
        return [
            'status' => $this->status,
            'result' => $this->result,
            'message' => $this->message,
            'timestamp' => now()->toDateTimeString()
        ];
    }
}
