<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Section extends Model
{
    use HasTranslations;

    protected $fillable = [
        "title",
        "course_id",
        "order"
    ];

    public $translatable = ['title'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function calculateDuration()
    {
        return $this->videos->sum(function ($video) {
            // Convert "HH:MM:SS" format to seconds and sum
            $parts = explode(':', $video->duration);
            $seconds = 0;

            if (count($parts) === 3) {
                $seconds += $parts[0] * 3600; // hours
                $seconds += $parts[1] * 60;   // minutes
                $seconds += $parts[2];        // seconds
            } elseif (count($parts) === 2) {
                $seconds += $parts[0] * 60;   // minutes
                $seconds += $parts[1];        // seconds
            }

            return $seconds;
        });
    }
}
