<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;

class JoinRoom extends Component
{
    public string $room_code = '';

    public function rules()
    {
        return [
            'room_code' => 'required|string|size:6|exists:rooms,code',
        ];
    }

    public function messages()
    {
        return [
            'room_code.exists' => 'Oda bulunamadı. Kodu kontrol edin.',
            'room_code.size' => 'Oda kodu 6 karakter olmalıdır.',
        ];
    }

    public function joinRoom()
    {
        $this->room_code = strtoupper($this->room_code);
        $this->validate();

        $room = Room::where('code', $this->room_code)
            ->where('status', 'active')
            ->first();

        if (!$room) {
            $this->addError('room_code', 'Bu oda şu an aktif değil.');
            return;
        }

        return redirect()->to("/join/{$this->room_code}");
    }

    public function render()
    {
        return view('livewire.join-room');
    }
}
