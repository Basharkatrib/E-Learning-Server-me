<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $table = 'course_notes';
    
    protected $fillable = [
        'user_id',
        'course_id',
<<<<<<< HEAD
        "video_id",
=======
        'title',
>>>>>>> 0ceb4aee496e315f6fa9a45be04bff85bbc41807
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
} 