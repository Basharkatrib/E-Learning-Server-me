<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BenefitsCourse extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'course_id', 'order'];

    public $translatable = ['title'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
