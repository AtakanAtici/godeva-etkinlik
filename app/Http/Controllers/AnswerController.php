<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Participant;
use App\Events\AnswerSubmitted;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;

class AnswerController extends Controller
{
    public function store(Question $question, Request $request): JsonResponse
    {
        if ($question->status !== 'published') {
            return response()->json(['error' => 'Question is not active'], 422);
        }

        $sessionId = $request->session()->getId();
        $participant = $question->room->participants()
            ->where('session_id', $sessionId)
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'Participant not found'], 404);
        }

        $rateLimitKey = 'answer_submit:' . $participant->id;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return response()->json(['error' => 'Too many attempts'], 429);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $answer = $question->answers()->create([
            'participant_id' => $participant->id,
            'content' => $validated['content']
        ]);

        RateLimiter::hit($rateLimitKey, 60);

        $answer->load('participant');
        broadcast(new AnswerSubmitted($answer))->toOthers();

        return response()->json([
            'answer' => $answer,
            'message' => 'Answer submitted successfully'
        ], 201);
    }

    public function moderate(Answer $answer, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:flag,hide,approve'
        ]);

        switch ($validated['action']) {
            case 'flag':
                $answer->flag();
                break;
            case 'hide':
                $answer->hide();
                break;
            case 'approve':
                $answer->update(['is_flagged' => false, 'is_hidden' => false]);
                break;
        }

        return response()->json([
            'answer' => $answer->fresh(),
            'message' => 'Answer moderated successfully'
        ]);
    }
}
