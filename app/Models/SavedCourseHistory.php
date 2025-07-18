<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedCourseHistory extends Model
{
    protected $table = 'saved_courses_history';

    protected $fillable = [
        'user_id',
        'course_id',
        'action'
    ];

    /**
     * Get the user that owns this history record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course associated with this history record.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
} 