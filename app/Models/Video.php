<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;


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
}
