<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presentation extends Model
{
    protected $fillable = [
        'room_id',
        'title',
        'file_path',
        'status',
        'total_slides',
        'error_message',
    ];

    protected $casts = [
        'total_slides' => 'integer',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class)->orderBy('slide_number');
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function markAsReady(): void
    {
        $this->update([
            'status' => 'ready',
            'error_message' => null,
        ]);
    }

    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }
}
