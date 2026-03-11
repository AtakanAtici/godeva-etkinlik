<?php

namespace App\Http\Controllers;

use App\Events\SlideChanged;
use App\Jobs\ProcessPresentationJob;
use App\Models\Presentation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresentationController extends Controller
{
    public function upload(Request $request, Room $room)
    {
        $request->validate([
            'file' => 'required|file|mimes:ppt,pptx|max:51200', // 50MB max
            'title' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $title = $request->input('title') ?: $file->getClientOriginalName();

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = 'presentations/' . $room->id . '/original/' . $filename;

        // Store original file
        Storage::put($path, file_get_contents($file));

        // Create presentation record
        $presentation = $room->presentations()->create([
            'title' => $title,
            'file_path' => $path,
            'status' => 'processing',
        ]);

        // Dispatch processing job
        ProcessPresentationJob::dispatch($presentation);

        return response()->json([
            'message' => 'Presentation upload started',
            'presentation' => [
                'id' => $presentation->id,
                'title' => $presentation->title,
                'status' => $presentation->status,
            ],
        ]);
    }

    public function show(Room $room, Presentation $presentation)
    {
        $presentation->load('slides');

        return response()->json([
            'presentation' => [
                'id' => $presentation->id,
                'title' => $presentation->title,
                'status' => $presentation->status,
                'total_slides' => $presentation->total_slides,
                'error_message' => $presentation->error_message,
                'slides' => $presentation->slides->map(fn($slide) => [
                    'id' => $slide->id,
                    'slide_number' => $slide->slide_number,
                    'image_url' => $slide->image_url,
                    'thumbnail_url' => $slide->thumbnail_url,
                ]),
            ],
        ]);
    }

    public function destroy(Room $room, Presentation $presentation)
    {
        // Delete files
        Storage::deleteDirectory('presentations/' . $room->id);

        // Delete database records (cascade will handle slides)
        $presentation->delete();

        return response()->json([
            'message' => 'Presentation deleted successfully',
        ]);
    }

    public function activate(Room $room, Presentation $presentation)
    {
        if (!$presentation->isReady()) {
            return response()->json([
                'message' => 'Presentation is not ready yet',
            ], 422);
        }

        $room->update([
            'active_presentation_id' => $presentation->id,
            'current_slide_number' => 1,
        ]);

        return response()->json([
            'message' => 'Presentation activated',
            'current_slide' => 1,
        ]);
    }

    public function nextSlide(Room $room)
    {
        if (!$room->active_presentation_id) {
            return response()->json(['message' => 'No active presentation'], 422);
        }

        $currentSlide = $room->current_slide_number ?? 1;
        $nextSlide = min($currentSlide + 1, $room->activePresentation->total_slides);

        if ($nextSlide > $currentSlide) {
            $room->navigateToSlide($nextSlide);
        }

        return response()->json([
            'current_slide' => $nextSlide,
        ]);
    }

    public function previousSlide(Room $room)
    {
        if (!$room->active_presentation_id) {
            return response()->json(['message' => 'No active presentation'], 422);
        }

        $currentSlide = $room->current_slide_number ?? 1;
        $prevSlide = max($currentSlide - 1, 1);

        if ($prevSlide < $currentSlide) {
            $room->navigateToSlide($prevSlide);
        }

        return response()->json([
            'current_slide' => $prevSlide,
        ]);
    }

    public function goToSlide(Request $request, Room $room)
    {
        $request->validate([
            'slide_number' => 'required|integer|min:1',
        ]);

        if (!$room->active_presentation_id) {
            return response()->json(['message' => 'No active presentation'], 422);
        }

        $slideNumber = $request->input('slide_number');

        if ($slideNumber > $room->activePresentation->total_slides) {
            return response()->json(['message' => 'Slide number out of range'], 422);
        }

        $room->navigateToSlide($slideNumber);

        return response()->json([
            'current_slide' => $slideNumber,
        ]);
    }
}
