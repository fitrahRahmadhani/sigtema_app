<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MbtiSyncProgressUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $progress;
    public $message;
    public $current;
    public $total;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $progress, $message = null, $current = null, $total = null)
    {
        $this->userId = $userId;
        $this->progress = $progress;
        $this->message = $message;
        $this->current = $current;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('mbti-sync-status.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'mbti.sync.progress.updated';
    }

    public function broadcastWith()
    {
        return [
            'progress' => $this->progress,
            'message' => $this->message,
            'current' => $this->current,
            'total' => $this->total,
            'timestamp' => now()->toISOString(),
        ];
    }
}
