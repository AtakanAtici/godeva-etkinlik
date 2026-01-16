<?php

namespace App\Events;

use App\Models\Question;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestionPublished implements ShouldBroadcast
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
        return 'question.published';
    }

    public function broadcastWith(): array
    {
        return [
            'question' => [
                'id' => $this->question->id,
                'title' => $this->question->title,
                'type' => $this->question->type,
                'options' => $this->question->options,
                'published_at' => $this->question->published_at,
                'answer_reveal_delay' => $this->question->answer_reveal_delay,
                'reveal_time' => $this->question->reveal_time?->toIso8601String(),
            ],
            'room_code' => $this->question->room->code,
        ];
    }
}
