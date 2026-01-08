<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Participant;
use App\Events\ParticipantJoined;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    public function join(string $roomCode, Request $request): JsonResponse
    {
        $room = Room::where('code', $roomCode)
            ->where('status', 'active')
            ->firstOrFail();

        $validated = $request->validate([
            'nickname' => 'nullable|string|max:50'
        ]);

        $sessionId = $request->session()->getId();
        
        $participant = $room->participants()->updateOrCreate(
            ['session_id' => $sessionId],
            [
                'nickname' => $validated['nickname'],
                'ip_address' => $request->ip(),
                'last_seen_at' => now()
            ]
        );

        if ($participant->wasRecentlyCreated) {
            broadcast(new ParticipantJoined($participant))->toOthers();
        }

        return response()->json([
            'participant' => $participant,
            'room' => $room->only(['id', 'title', 'code']),
            'active_question' => $room->activeQuestion()
        ]);
    }

    public function heartbeat(string $roomCode, Request $request): JsonResponse
    {
        $room = Room::where('code', $roomCode)->firstOrFail();
        $sessionId = $request->session()->getId();
        
        $participant = $room->participants()
            ->where('session_id', $sessionId)
            ->first();

        if ($participant) {
            $participant->updateLastSeen();
        }

        return response()->json([
            'status' => 'ok',
            'active_question' => $room->activeQuestion()
        ]);
    }
}
