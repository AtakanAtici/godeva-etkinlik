<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class HostRoomList extends Component
{
    public function createRoom()
    {
        $room = Room::create([
            'title' => 'Yeni Etkinlik ' . now()->format('d.m.Y H:i'),
            'host_id' => 'godeva', // Hardcoded for now as per existing logic
            'status' => 'active',
            'code' => strtoupper(Str::random(6))
        ]);

        return redirect('/host/' . $room->id);
    }

    public function render()
    {
        $rooms = Room::orderBy('created_at', 'desc')->get();
        return view('livewire.host-room-list', [
            'rooms' => $rooms
        ]);
    }
}
