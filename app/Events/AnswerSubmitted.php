<?php

namespace App\Events;

use App\Models\Answer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnswerSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Answer $answer)
    {
        $this->answer->load(['question.room', 'participant']);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->answer->question->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'answer.submitted';
    }

    public function broadcastWith(): array
    {
        return [
            'answer' => [
                'id' => $this->answer->id,
                'content' => $this->answer->content,
                'submitted_at' => $this->answer->submitted_at,
                'participant_nickname' => $this->answer->participant->nickname,
            ],
            'question_id' => $this->answer->question_id,
            'room_code' => $this->answer->question->room->code,
        ];
    }
}
