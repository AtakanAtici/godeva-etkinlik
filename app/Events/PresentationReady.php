<?php

namespace App\Events;

use App\Models\Presentation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresentationReady implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Presentation $presentation)
    {
        $this->presentation->load(['room', 'slides']);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->presentation->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'presentation.ready';
    }

    public function broadcastWith(): array
    {
        return [
            'presentation' => [
                'id' => $this->presentation->id,
                'title' => $this->presentation->title,
                'total_slides' => $this->presentation->total_slides,
                'status' => $this->presentation->status,
            ],
            'room_code' => $this->presentation->room->code,
        ];
    }
}
