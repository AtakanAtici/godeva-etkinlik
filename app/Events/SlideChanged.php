<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlideChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Room $room,
        public int $slideNumber
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'slide.changed';
    }

    public function broadcastWith(): array
    {
        $slide = $this->room->currentSlide();

        return [
            'slide_number' => $this->slideNumber,
            'slide' => $slide ? [
                'id' => $slide->id,
                'slide_number' => $slide->slide_number,
                'image_url' => $slide->image_url,
                'thumbnail_url' => $slide->thumbnail_url,
            ] : null,
            'room_code' => $this->room->code,
        ];
    }
}
