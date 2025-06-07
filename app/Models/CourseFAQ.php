<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseFAQ extends Model
{

    protected $table = 'course_faqs';
    
    protected $fillable = [
        "course_id",
        "question",
        "answer"
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
