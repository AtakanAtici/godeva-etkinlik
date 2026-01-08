<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Question;
use App\Events\ParticipantJoined;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'settings' => 'array'
        ]);

        $room = Room::create([
            'title' => $validated['title'],
            'settings' => $validated['settings'] ?? [],
            'host_id' => $request->user()->id ?? null
        ]);

        return response()->json([
            'room' => $room,
            'qr_url' => url("/join/{$room->code}")
        ], 201);
    }

    public function show(string $code): JsonResponse
    {
        $room = Room::where('code', $code)
            ->with(['questions', 'participants'])
            ->firstOrFail();

        return response()->json([
            'room' => $room,
            'participant_count' => $room->participantCount(),
            'active_question' => $room->activeQuestion()
        ]);
    }

    public function dashboard(Room $room): JsonResponse
    {
        $room->load(['questions', 'participants']);
        
        return response()->json([
            'room' => $room,
            'questions' => $room->questions()->orderBy('created_at', 'desc')->get(),
            'participants' => $room->participants()->orderBy('joined_at', 'desc')->take(50)->get(),
            'stats' => [
                'total_participants' => $room->participantCount(),
                'total_questions' => $room->questions()->count(),
                'active_question' => $room->activeQuestion()
            ]
        ]);
    }

    public function updateStatus(Room $room, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,paused,closed'
        ]);

        $room->update(['status' => $validated['status']]);

        return response()->json(['room' => $room]);
    }
}
