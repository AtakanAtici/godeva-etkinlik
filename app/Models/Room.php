<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Room extends Model
{
    use HasUuids;

    protected $fillable = [
        'code', 'title', 'host_id', 'settings', 'status', 'active_presentation_id', 'current_slide_number'
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($room) {
            if (!$room->code) {
                $room->code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function presentations(): HasMany
    {
        return $this->hasMany(Presentation::class);
    }

    public function activePresentation()
    {
        return $this->belongsTo(Presentation::class, 'active_presentation_id');
    }

    public function currentSlide(): ?Slide
    {
        if (!$this->active_presentation_id || !$this->current_slide_number) {
            return null;
        }

        return $this->activePresentation->slides()
            ->where('slide_number', $this->current_slide_number)
            ->first();
    }

    public function navigateToSlide(int $slideNumber): void
    {
        $this->update(['current_slide_number' => $slideNumber]);

        $slide = $this->currentSlide();
        if ($slide && $slide->hasAutoPublishQuestion()) {
            $autoPublishQuestion = $slide->autoPublishQuestions()->first();
            if ($autoPublishQuestion && $autoPublishQuestion->status !== 'published') {
                $autoPublishQuestion->update(['status' => 'published']);
                broadcast(new \App\Events\QuestionPublished($autoPublishQuestion))->toOthers();
            }
        }

        broadcast(new \App\Events\SlideChanged($this, $slideNumber))->toOthers();
    }

    public function activeQuestion(): ?Question
    {
        return $this->questions()->where('status', 'published')->first();
    }

    public function participantCount(): int
    {
        return $this->participants()->count();
    }
}
