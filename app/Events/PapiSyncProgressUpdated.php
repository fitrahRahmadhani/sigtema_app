<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PapiSyncProgressUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $progress;
    public $message;
    public $current;
    public $total;

    public function __construct($userId, $progress, $message = null, $current = null, $total = null)
    {
        $this->userId = $userId;
        $this->progress = $progress;
        $this->message = $message;
        $this->current = $current;
        $this->total = $total;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('papi-sync-status.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'papi.sync.progress.updated';
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
