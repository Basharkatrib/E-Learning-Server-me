<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class CourseFAQ extends Model
{
    use HasTranslations;

    protected $fillable = [
        "course_id",
        "question",
        "answer"
    ];

    public $translatable = ['question', 'answer'];

    protected $table = 'course_faqs';

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
