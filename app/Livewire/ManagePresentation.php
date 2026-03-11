<?php

namespace App\Livewire;

use App\Jobs\ProcessPresentationJob;
use App\Models\Presentation;
use App\Models\Room;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManagePresentation extends Component
{
    use WithFileUploads;

    public Room $room;
    public $file;
    public $title;
    public $uploading = false;
    public $uploadProgress = 0;

    protected $listeners = [
        'presentation.ready' => 'refreshPresentations',
        'presentation.failed' => 'refreshPresentations',
    ];

    public function mount(Room $room)
    {
        $this->room = $room;
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|file|mimes:ppt,pptx|max:51200', // 50MB
        ]);
    }

    public function upload()
    {
        $this->validate([
            'file' => 'required|file|mimes:ppt,pptx|max:51200',
            'title' => 'nullable|string|max:255',
        ]);

        $this->uploading = true;

        try {
            $title = $this->title ?: $this->file->getClientOriginalName();
            $filename = Str::uuid() . '.' . $this->file->getClientOriginalExtension();
            $path = 'presentations/' . $this->room->id . '/original/' . $filename;

            // Store file
            Storage::put($path, file_get_contents($this->file->getRealPath()));

            // Create presentation record
            $presentation = $this->room->presentations()->create([
                'title' => $title,
                'file_path' => $path,
                'status' => 'processing',
            ]);

            // Dispatch job
            ProcessPresentationJob::dispatch($presentation);

            // Reset form
            $this->reset(['file', 'title', 'uploading']);
            $this->dispatch('presentation-uploaded');

            session()->flash('message', 'Sunum yükleniyor ve işleniyor...');

        } catch (\Exception $e) {
            $this->uploading = false;
            session()->flash('error', 'Yükleme hatası: ' . $e->getMessage());
        }
    }

    public function deletePresentation(Presentation $presentation)
    {
        Storage::deleteDirectory('presentations/' . $this->room->id);
        $presentation->delete();

        session()->flash('message', 'Sunum silindi.');
    }

    public function refreshPresentations()
    {
        $this->room->refresh();
    }

    public function render()
    {
        $presentations = $this->room->presentations()
            ->with('slides')
            ->latest()
            ->get();

        return view('livewire.manage-presentation', [
            'presentations' => $presentations,
        ]);
    }
}
