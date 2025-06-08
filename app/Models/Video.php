<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;


class Video extends Model
{
    protected $fillable = [
        "title",
        "video_url",
        "section_id",
        "is_preview",
        "duration",
        "thumbnail_url",
    ];

    public $translatable = ['title'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
