<?php

namespace App\Events;

use App\Models\Presentation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresentationFailed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Presentation $presentation,
        public string $errorMessage
    ) {
        $this->presentation->load('room');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->presentation->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'presentation.failed';
    }

    public function broadcastWith(): array
    {
        return [
            'presentation' => [
                'id' => $this->presentation->id,
                'title' => $this->presentation->title,
                'status' => $this->presentation->status,
                'error_message' => $this->errorMessage,
            ],
            'room_code' => $this->presentation->room->code,
        ];
    }
}
