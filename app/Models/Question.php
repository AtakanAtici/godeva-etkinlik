<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Question extends Model
{
    use HasUuids;

    protected $fillable = [
        'room_id', 'title', 'type', 'options', 'answer_reveal_delay', 'status', 'published_at'
    ];

    protected $casts = [
        'options' => 'array',
        'published_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function close(): void
    {
        $this->update(['status' => 'closed']);
    }

    public function reopen(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function shouldRevealAnswers(): bool
    {
        if (!$this->published_at) {
            return false;
        }

        $revealTime = $this->published_at->addSeconds($this->answer_reveal_delay);
        return now()->greaterThanOrEqualTo($revealTime);
    }

    public function getRevealTimeAttribute(): ?\Carbon\Carbon
    {
        if (!$this->published_at) {
            return null;
        }

        return $this->published_at->copy()->addSeconds($this->answer_reveal_delay);
    }
}
