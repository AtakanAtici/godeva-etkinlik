<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Slide extends Model
{
    protected $fillable = [
        'presentation_id',
        'slide_number',
        'image_path',
        'thumbnail_path',
    ];

    protected $casts = [
        'slide_number' => 'integer',
    ];

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(Presentation::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function autoPublishQuestions(): HasMany
    {
        return $this->questions()->where('auto_publish_on_slide', true);
    }

    public function hasAutoPublishQuestion(): bool
    {
        return $this->autoPublishQuestions()->exists();
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return Storage::url($this->thumbnail_path);
    }
}
