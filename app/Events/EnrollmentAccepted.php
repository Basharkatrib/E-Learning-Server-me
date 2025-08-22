<?php

namespace App\Events;

use App\Models\Enrollment;
use App\Notifications\EnrollmentAcceptedNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnrollmentAccepted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $data;

    public function __construct(public Enrollment $enrollment)
    {
        $user = $enrollment->user;
        $course = $enrollment->course;
        $this->data = [
            'type' => 'enrollment.accepted',
            'message' => sprintf('You have been accepted to the course "%s". You can now view the course.', $course?->title ?? 'Course'),
            'userId' => $user?->id,
            'courseId' => $course?->id,
            'courseTitle' => $course?->title,
        ];
    }

    public function broadcastOn(): Channel
    {
        // Public channel per user (no auth needed). If you later enable private channels, switch to PrivateChannel.
        return new Channel('user-notifications-' . $this->enrollment->user_id);
    }

    public function broadcastAs(): string
    {
        return 'enrollment-accepted';
    }

    //for FireBase FCM
    public function handle(EnrollmentAccepted $event)
{
    $user = $event->enrollment->user;

    if ($user) {
        $user->notify(new EnrollmentAcceptedNotification($event->data));
    }
}
}


