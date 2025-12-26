<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AIAnalysisFailed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $positionId;
    public $userId;
    public $error;

    public function __construct($positionId, $userId, $error)
    {
        $this->positionId = $positionId;
        $this->userId = $userId;
        $this->error = $error;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("ai-analysis.{$this->userId}.{$this->positionId}");
    }

    public function broadcastAs()
    {
        return 'ai.failed';
    }

    public function broadcastWith()
    {
        return [
            'error' => $this->error,
            'position_id' => $this->positionId,
        ];
    }
}
