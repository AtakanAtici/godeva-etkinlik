<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Question;
use App\Events\QuestionPublished;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    public function store(Room $room, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'type' => 'required|in:open_text,multiple_choice',
            'options' => 'array|nullable'
        ]);

        $question = $room->questions()->create($validated);

        return response()->json(['question' => $question], 201);
    }

    public function publish(Question $question): JsonResponse
    {
        $question->publish();
        
        $question->room()->update(['status' => 'active']);

        broadcast(new QuestionPublished($question))->toOthers();

        return response()->json([
            'question' => $question->fresh(),
            'message' => 'Question published successfully'
        ]);
    }

    public function close(Question $question): JsonResponse
    {
        $question->close();

        broadcast(new \App\Events\QuestionClosed($question))->toOthers();

        return response()->json([
            'question' => $question->fresh(),
            'message' => 'Question closed successfully'
        ]);
    }

    public function show(Question $question): JsonResponse
    {
        $question->load(['room', 'answers.participant']);
        
        return response()->json([
            'question' => $question,
            'answers_count' => $question->answers()->count(),
            'recent_answers' => $question->answers()
                ->where('is_hidden', false)
                ->with('participant')
                ->orderBy('submitted_at', 'desc')
                ->take(20)
                ->get()
        ]);
    }
}
