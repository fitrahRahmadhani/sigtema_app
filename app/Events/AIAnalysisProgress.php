<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class AIAnalysisProgress implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $positionId;
    public $userId;
    public $progress;
    public $message;

    public function __construct($positionId, $userId, $progress, $message)
    {
        $this->positionId = $positionId;
        $this->userId = $userId;
        $this->progress = $progress;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("ai-analysis.{$this->userId}.{$this->positionId}");
    }

    public function broadcastAs()
    {
        return 'ai.progress';
    }

    public function broadcastWith()
    {
        return [
            'progress' => $this->progress,
            'message' => $this->message,
            'position_id' => $this->positionId,
        ];
    }
}
