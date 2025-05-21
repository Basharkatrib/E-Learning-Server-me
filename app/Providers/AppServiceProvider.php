<?php

namespace App\Providers;

use Filament\Notifications\Auth\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::createUrlUsing(function ($notifiable) {
            $temporarySignedURL = URL::temporarySignedRoute(
                "verification.verify",
                now()->addMinutes(60),
                [
                    "id" => $notifiable->getKey(),
                    "hash" => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return "http://localhost:5173/verify-email?url=" . urlencode($temporarySignedURL);
        });
    }
}
