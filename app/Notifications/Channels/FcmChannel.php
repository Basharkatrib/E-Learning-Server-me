<?php

namespace App\Notifications\Channels;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FcmChannel extends Notification
{
    use Queueable;

    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(base_path(env("FIREBASE_CREDENTIALS")));
        $this->messaging = $factory->createMessaging();
    }

    public function send($notifiable, Notification $notification)
    {
        $tokens = $notifiable->routeNotificationFor("fcm", $notification);

        if (empty($tokens)) {
            return;
        }

        $payload = $notification->toFcm($notifiable);

        if (is_string($tokens)) {
            $this->messaging->send(CloudMessage::fromArray([
                "token" => $tokens,
            ] + $payload));
        } elseif (is_array($tokens)) {
            $this->messaging->sendMulticast(
                CloudMessage::fromArray($payload),
                $tokens
            );
        }
    }
}
