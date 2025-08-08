<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentAccepted;
use App\Events\EnrollmentAccepted;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'course_user';

    protected $fillable = [
        'course_id',
        'user_id',
        'status',
        'payment_method',
        'transation_id',
        'price_paid',
        'payment_screenshot_path',
        'enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    protected static function booted(): void
    {
        static::updated(function (Enrollment $enrollment): void {
            $originalStatus = $enrollment->getOriginal('status');
            if ($originalStatus !== 'accepted' && $enrollment->status === 'accepted') {
                $enrollment->loadMissing(['user', 'course']);
                if ($enrollment->user && $enrollment->course && !empty($enrollment->user->email)) {
                    try {
                        Mail::to($enrollment->user->email)->send(new PaymentAccepted($enrollment));
                        // Broadcast real-time notification
                        event(new EnrollmentAccepted($enrollment));
                    } catch (\Throwable $e) {
                        // prevent throwing within admin UI
                    }
                }
            }
        });
    }
}


