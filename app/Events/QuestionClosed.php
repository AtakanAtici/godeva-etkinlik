<?php

namespace App\Events;

use App\Models\Question;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestionClosed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Question $question)
    {
        $this->question->load('room');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->question->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'question.closed';
    }

    public function broadcastWith(): array
    {
        return [
            'question_id' => $this->question->id,
            'room_code' => $this->question->room->code,
        ];
    }
}
