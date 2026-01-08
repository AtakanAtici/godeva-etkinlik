<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Answer extends Model
{
    use HasUuids;

    protected $fillable = [
        'question_id', 'participant_id', 'content', 'is_flagged', 'is_hidden', 'submitted_at'
    ];

    protected $casts = [
        'is_flagged' => 'boolean',
        'is_hidden' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function flag(): void
    {
        $this->update(['is_flagged' => true]);
    }

    public function hide(): void
    {
        $this->update(['is_hidden' => true]);
    }
}
