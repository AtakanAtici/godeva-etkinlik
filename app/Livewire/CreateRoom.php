<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;
use Illuminate\Support\Str;

class CreateRoom extends Component
{
    public string $title = '';
    public bool $allow_anonymous = true;
    public bool $profanity_filter = false;
    
    public function rules()
    {
        return [
            'title' => 'required|string|max:255|min:3',
        ];
    }

    public function createRoom()
    {
        $this->validate();

        $room = Room::create([
            'title' => $this->title,
            'settings' => [
                'allow_anonymous' => $this->allow_anonymous,
                'profanity_filter' => $this->profanity_filter,
            ]
        ]);

        session()->flash('success', 'Oda başarıyla oluşturuldu!');
        
        return redirect()->to("/host/{$room->id}");
    }

    public function render()
    {
        return view('livewire.create-room');
    }
}
