<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    protected $fillable = ["user_id", "quiz_attempt_id", "question_id", "option_id", "is_correct", "points_earned"];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, "quiz_attempt_id");
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
