<?php

namespace App\Events;

use App\Models\Participant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParticipantJoined implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Participant $participant)
    {
        $this->participant->load('room');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->participant->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'participant.joined';
    }

    public function broadcastWith(): array
    {
        return [
            'participant' => [
                'id' => $this->participant->id,
                'nickname' => $this->participant->nickname,
                'joined_at' => $this->participant->joined_at,
            ],
            'room_code' => $this->participant->room->code,
            'participant_count' => $this->participant->room->participantCount(),
        ];
    }
}
