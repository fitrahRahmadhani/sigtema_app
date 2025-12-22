<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PapiSyncStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $status;
    public $result;
    public $message;

    public function __construct($userId, $status, $result = null, $message = null)
    {
        $this->userId = $userId;
        $this->status = $status;
        $this->result = $result;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('papi-sync-status.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'papi.sync.status.updated';
    }

    public function broadcastWith()
    {
        return [
            'status' => $this->status,
            'result' => $this->result,
            'message' => $this->message,
            'timestamp' => now()->toISOString(),
        ];
    }
}
