<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Video extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title',
        'video_url',
        'video_file',
        'section_id',
        'is_preview',
        'duration',
        'thumbnail_url',
    ];

    public $translatable = ['title'];

    protected $casts = [
        'is_preview' => 'boolean',
        'duration' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'video_user')
            ->withTimestamps()
            ->withPivot('watched_at');
    }
}
