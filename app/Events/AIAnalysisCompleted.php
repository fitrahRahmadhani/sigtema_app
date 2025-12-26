<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AIAnalysisCompleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $positionId;
    public $userId;
    public $results;

    public function __construct($positionId, $userId, $results)
    {
        $this->positionId = $positionId;
        $this->userId = $userId;
        $this->results = $results;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("ai-analysis.{$this->userId}.{$this->positionId}");
    }

    public function broadcastAs()
    {
        return 'ai.completed';
    }

    public function broadcastWith()
    {
        return [
            'results' => $this->results,
            'position_id' => $this->positionId,
        ];
    }
}
