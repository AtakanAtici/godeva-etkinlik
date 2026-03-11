<?php

namespace App\Livewire;

use App\Models\Presentation;
use App\Models\Room;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SlideNavigator extends Component
{
    public Room $room;
    public ?Presentation $presentation = null;
    public int $currentSlideNumber = 1;

    protected $listeners = [
        'activate-presentation' => 'activatePresentation',
    ];

    public function mount(Room $room)
    {
        $this->room = $room->load('activePresentation.slides');

        if ($this->room->active_presentation_id) {
            $this->presentation = $this->room->activePresentation;
            $this->currentSlideNumber = $this->room->current_slide_number ?? 1;
        }
    }

    public function activatePresentation($presentationId)
    {
        $presentation = Presentation::find($presentationId);

        if (!$presentation || !$presentation->isReady()) {
            return;
        }

        Http::post("/api/rooms/{$this->room->id}/presentations/{$presentationId}/activate");

        $this->room->refresh();
        $this->presentation = $presentation->load('slides');
        $this->currentSlideNumber = 1;
    }

    public function nextSlide()
    {
        if (!$this->presentation) {
            return;
        }

        Http::post("/api/rooms/{$this->room->id}/slides/next");
        $this->room->refresh();
        $this->currentSlideNumber = $this->room->current_slide_number;
    }

    public function previousSlide()
    {
        if (!$this->presentation) {
            return;
        }

        Http::post("/api/rooms/{$this->room->id}/slides/previous");
        $this->room->refresh();
        $this->currentSlideNumber = $this->room->current_slide_number;
    }

    public function goToSlide($slideNumber)
    {
        if (!$this->presentation) {
            return;
        }

        Http::post("/api/rooms/{$this->room->id}/slides/goto", [
            'slide_number' => $slideNumber,
        ]);

        $this->room->refresh();
        $this->currentSlideNumber = $slideNumber;
    }

    public function getCurrentSlide()
    {
        if (!$this->presentation) {
            return null;
        }

        return $this->presentation->slides
            ->where('slide_number', $this->currentSlideNumber)
            ->first();
    }

    public function render()
    {
        $currentSlide = $this->getCurrentSlide();

        return view('livewire.slide-navigator', [
            'currentSlide' => $currentSlide,
            'slides' => $this->presentation?->slides ?? collect(),
        ]);
    }
}
