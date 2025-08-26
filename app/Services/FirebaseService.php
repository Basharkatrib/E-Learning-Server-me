<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;


class FirebaseService
{
    protected $messaging;

    public function __construct()
    {

        try {

            $credentialsPath = $this->getCredentialsPath();

            if (!$credentialsPath) {
                throw new \Exception("Firebase credentials not configured");
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->messaging = $factory->createMessaging();
        } catch (\Exception $e) {
            Log::error("Firebase initialization failed: " . $e->getMessage());
            throw new \Exception("Failed to initialize Firebase: " . $e->getMessage());
        }
    }


    protected function getCredentialsPath()
    {

        $path = env("FIREBASE_CREDENTIALS");

        if ($path && file_exists($path)) {
            return $path;
        }

        $defaultPath = storage_path("app/firebase/service-account-key.json");
        if (file_exists($defaultPath)) {
            return $defaultPath;
        }

        $contents = env('FIREBASE_CREDENTIALS_CONTENTS');
        if ($contents) {
            $tempPath = storage_path("app/firebase/temp-service-account-key.json");
            file_put_contents($tempPath, $contents);
            return $tempPath;
        }

        return null;
    }

    public function sendToDevice($deviceToken, $title, $body, $data = [], $analyticsLabel = null)
    {
        if (empty($deviceToken)) {
            throw new \Exception("Device token is required");
        }

        try {
            $message = CloudMessage::withTarget("token", $deviceToken)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            return $this->messaging->send($message);
        } catch (MessagingException $e) {
            if (strpos($e->getMessage(), "Invalid registration token") !== false) {
                DeviceToken::where("device_token", $deviceToken)->delete();
            }
            Log::error("Failed to send notification: " . $e->getMessage());
            throw new \Exception("Failed to send notification: " . $e->getMessage());
        } catch (FirebaseException $e) {
            Log::error("Firebase error: " . $e->getMessage());
            throw new \Exception("Firebase error: " . $e->getMessage());
        }
    }

    public function sendToUser($userId, $title, $body, $data = [], $analyticsLabel = null)
    {
        $tokens = DeviceToken::where("user_id", $userId)->pluck("device_token")->toArray();

        if (empty($tokens)) {
            return null;
        }

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        return $this->messaging->sendMulticast($message, $tokens);
    }

    public function sendToMultipleUsers($userIds, $title, $body, $data = [], $analyticsLabel = null)
    {
        $tokens = DeviceToken::whereIn("user_id", $userIds)->pluck("device_token")->toArray();

        if (empty($tokens)) {
            return null;
        }

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        return $this->messaging->sendMulticast($message, $tokens);
    }
}
