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
        'code', 'title', 'host_id', 'settings', 'status'
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

    public function activeQuestion(): ?Question
    {
        return $this->questions()->where('status', 'published')->first();
    }

    public function participantCount(): int
    {
        return $this->participants()->count();
    }
}
